<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Order;

use Linio\SellerCenter\Factory\Xml\Order\OrderFactory;
use Linio\SellerCenter\Factory\Xml\Order\OrderItemFactory;
use Linio\SellerCenter\Factory\Xml\Order\OrderItemsFactory;
use Linio\SellerCenter\Factory\Xml\Order\OrdersFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Model\Order\VoucherAmountDetails;
use Linio\SellerCenter\Model\Order\VoucherDetails;
use Linio\SellerCenter\V2\Factory\Xml\Order\OrderFactory as OrderFactoryV2;
use Linio\SellerCenter\V2\Factory\Xml\Order\OrdersFactory as OrdersFactoryV2;

/**
 * Oportunidad Única (CMR discount) contract update: TC01 - TC16 + regression checklist.
 */
class VoucherScenariosTest extends LinioTestCase
{
    private const SC_PRICE = 107900.00;
    private const CMR = 10790.00;
    private const OTHER = 22821.00;
    private const PAID_PRICE = 74289.00; // 107900 - 10790 - 22821
    private const SHIPPING_AMOUNT = 7000.00;
    private const SELLER_PCT = 35.5;

    // ---------------------------------------------------------------- helpers

    private function ordersXml(bool $v2 = false): \SimpleXMLElement
    {
        $raw = $v2 ? $this->getSchemaV2('Order/OrdersResponse.xml') : $this->getSchema('Order/OrdersResponse.xml');

        return simplexml_load_string($raw)->Body;
    }

    private function addOrderVoucherDetails(\SimpleXMLElement $order, string $cmr, string $other, string $voucher): void
    {
        $order->Voucher = $voucher;
        $details = $order->addChild('VoucherDetails');
        $details->addChild('CmrDiscount', $cmr);
        $details->addChild('OtherDiscount', $other);
    }

    private function itemsXml(): \SimpleXMLElement
    {
        return simplexml_load_string($this->getSchema('Order/OrderItemsResponse.xml'))->Body;
    }

    /**
     * Configures the (single) item of OrderItemsResponse.xml and optionally clones it.
     */
    private function configureItem(
        \SimpleXMLElement $item,
        int $id,
        string $itemPrice,
        string $paidPrice,
        string $voucherAmount,
        ?array $details,
    ): void {
        $item->OrderItemId = (string) $id;
        $item->ItemPrice = $itemPrice;
        $item->PaidPrice = $paidPrice;
        $item->ShippingAmount = number_format(self::SHIPPING_AMOUNT, 2, '.', '');
        $item->VoucherAmount = $voucherAmount;

        if ($details !== null) {
            $node = $item->addChild('VoucherAmountDetails');
            $node->addChild('CmrDiscount', $details[0]);
            $node->addChild('OtherDiscount', $details[1]);
            $node->addChild('CmrDiscountSellerFundedPct', $details[2] ?? '');
        }
    }

    private function cloneItem(\SimpleXMLElement $items): \SimpleXMLElement
    {
        $first = dom_import_simplexml($items->OrderItem[0]);
        $first->parentNode->appendChild($first->cloneNode(true));
        $count = count($items->OrderItem);
        $clone = $items->OrderItem[$count - 1];
        unset($clone->VoucherAmountDetails);

        return $clone;
    }

    private function buildMixedOrderItems(): \SimpleXMLElement
    {
        $body = $this->itemsXml();
        $items = $body->OrderItems;

        // Item A: Oportunidad Única + coupon
        $this->configureItem($items->OrderItem[0], 1, '107900.00', '74289.00', '33611.00', ['10790.00', '22821.00', '35.5']);
        // Item B: coupon only
        $b = $this->cloneItem($items);
        $this->configureItem($b, 2, '50000.00', '45000.00', '5000.00', ['0.00', '5000.00', null]);
        // Item C: no discount
        $c = $this->cloneItem($items);
        $this->configureItem($c, 3, '20000.00', '20000.00', '0.00', ['0.00', '0.00', null]);

        return $body;
    }

    // ---------------------------------------------------------------- TC01 / TC02

    public function testTc01GetOrdersReturnsVoucherDetails(): void
    {
        foreach ([false, true] as $v2) {
            $body = $this->ordersXml($v2);
            $this->addOrderVoucherDetails($body->Orders->Order[0], '10790.00', '22821.00', '33611.00');

            $orders = $v2 ? OrdersFactoryV2::make($body) : OrdersFactory::make($body);
            $target = array_values($orders->all())[0];

            $details = $target->getVoucherDetails();
            $this->assertInstanceOf(VoucherDetails::class, $details);
            $this->assertSame(self::CMR, $details->getCmrDiscount());
            $this->assertSame(self::OTHER, $details->getOtherDiscount());
            $this->assertSame(self::CMR + self::OTHER, $details->getTotal());
            $this->assertSame($target->getVoucher(), $details->getTotal());

            // Second order has no VoucherDetails -> backward compatible
            $this->assertNull(array_values($orders->all())[1]->getVoucherDetails());
        }
    }

    public function testTc02GetOrderReturnsVoucherDetails(): void
    {
        foreach ([false, true] as $v2) {
            $order = $this->ordersXml($v2)->Orders->Order[0];
            $this->addOrderVoucherDetails($order, '10,790.00', '22,821.00', '33,611.00');

            $model = $v2 ? OrderFactoryV2::make($order) : OrderFactory::make($order);

            $this->assertSame(self::CMR, $model->getVoucherDetails()->getCmrDiscount());
            $this->assertSame(self::OTHER, $model->getVoucherDetails()->getOtherDiscount());
            $this->assertSame(33611.0, $model->getVoucher());

            $json = json_decode(json_encode($model), true);
            $this->assertEquals(['cmrDiscount' => self::CMR, 'otherDiscount' => self::OTHER], $json['voucherDetails']);
        }
    }

    // ---------------------------------------------------------------- TC03 - TC08

    private function singleCmrItem(): OrderItem
    {
        $body = $this->itemsXml();
        $this->configureItem($body->OrderItems->OrderItem[0], 1, '107900.00', '74289.00', '33611.00', ['10790.00', '22821.00', (string) self::SELLER_PCT]);

        return OrderItemsFactory::make($body)->all()[1];
    }

    public function testTc03ItemPriceIsSellerCenterPriceWithoutCmr(): void
    {
        $item = $this->singleCmrItem();

        $this->assertSame(self::SC_PRICE, $item->getItemPrice());
        $this->assertNotSame(self::SC_PRICE - self::CMR, $item->getItemPrice());
        $this->assertSame(self::PAID_PRICE, $item->getPaidPrice());
    }

    public function testTc04VoucherAmountIncludesCmrAndOtherDiscounts(): void
    {
        $item = $this->singleCmrItem();

        $this->assertSame(self::CMR + self::OTHER, $item->getVoucherAmount());
        $this->assertSame($item->getVoucherAmount(), $item->getVoucherAmountDetails()->getTotal());
        $this->assertSame($item->getItemPrice() - $item->getVoucherAmount(), $item->getPaidPrice());
    }

    public function testTc05VoucherAmountDetailsIsReturnedCorrectly(): void
    {
        $item = $this->singleCmrItem();
        $details = $item->getVoucherAmountDetails();

        $this->assertInstanceOf(VoucherAmountDetails::class, $details);
        $this->assertSame(self::CMR, $details->getCmrDiscount());
        $this->assertSame(self::OTHER, $details->getOtherDiscount());
        $this->assertSame(self::SELLER_PCT, $details->getCmrDiscountSellerFundedPct());

        $json = json_decode(json_encode($item), true);
        $this->assertSame(
            ['cmrDiscount', 'otherDiscount', 'cmrDiscountSellerFundedPct'],
            array_keys($json['voucherAmountDetails'])
        );
    }

    public function testTc06CmrDiscountSellerFundedPct(): void
    {
        $details = $this->singleCmrItem()->getVoucherAmountDetails();

        $this->assertIsFloat($details->getCmrDiscountSellerFundedPct());
        $this->assertSame(self::SELLER_PCT, $details->getCmrDiscountSellerFundedPct());
        $this->assertSame(round(self::CMR * self::SELLER_PCT / 100, 2), $details->getCmrDiscountSellerFundedAmount());
    }

    public function testTc07PaidPriceUnchanged(): void
    {
        $withDetails = $this->singleCmrItem();

        $body = $this->itemsXml();
        $this->configureItem($body->OrderItems->OrderItem[0], 1, '107900.00', '74289.00', '33611.00', null);
        $withoutDetails = OrderItemFactory::make($body->OrderItems->OrderItem[0]);

        $this->assertSame($withoutDetails->getPaidPrice(), $withDetails->getPaidPrice());
        $this->assertSame(self::PAID_PRICE, $withDetails->getPaidPrice());
    }

    public function testTc08ShippingAmountUnchanged(): void
    {
        $this->assertSame(self::SHIPPING_AMOUNT, $this->singleCmrItem()->getShippingAmount());
    }

    // ---------------------------------------------------------------- TC09 - TC12

    public function testTc09OrderVoucherEqualsSumOfItemVoucherAmounts(): void
    {
        $items = OrderItemsFactory::make($this->buildMixedOrderItems())->all();
        $sum = array_sum(array_map(static function (OrderItem $i): float {
            return $i->getVoucherAmount();
        }, $items));

        $body = $this->ordersXml();
        $this->addOrderVoucherDetails($body->Orders->Order[0], '10790.00', '27821.00', (string) $sum);
        $order = array_values(OrdersFactory::make($body)->all())[0];

        $this->assertSame(38611.0, $sum);
        $this->assertSame($sum, $order->getVoucher());
    }

    /**
     * TC10 / TC11 / TC12: totals are identical with and without the new attributes.
     */
    public function testTc10To12TotalsUnchanged(): void
    {
        foreach ([false, true] as $v2) {
            $factory = $v2 ? OrderFactoryV2::class : OrderFactory::class;

            $before = $factory::make($this->ordersXml($v2)->Orders->Order[0]);

            $xml = $this->ordersXml($v2)->Orders->Order[0];
            $details = $xml->addChild('VoucherDetails');
            $details->addChild('CmrDiscount', '10790.00');
            $details->addChild('OtherDiscount', '22821.00');
            $after = $factory::make($xml);

            $this->assertSame(46200.0, $after->getProductTotal());
            $this->assertSame(22990.0, $after->getShippingFeeTotal());
            $this->assertSame(69190.0, $after->getGrandTotal());
            $this->assertSame($before->getProductTotal(), $after->getProductTotal());
            $this->assertSame($before->getShippingFeeTotal(), $after->getShippingFeeTotal());
            $this->assertSame($before->getGrandTotal(), $after->getGrandTotal());
            $this->assertSame($before->getVoucher(), $after->getVoucher());
            $this->assertSame($after->getProductTotal() + $after->getShippingFeeTotal() - $after->getVoucher(), $after->getGrandTotal());
        }
    }

    // ---------------------------------------------------------------- TC13 - TC15

    public function testTc13OnlyOportunidadUnica(): void
    {
        $body = $this->itemsXml();
        $this->configureItem($body->OrderItems->OrderItem[0], 1, '107900.00', '97110.00', '10790.00', ['10790.00', '0.00', '100']);
        $item = OrderItemsFactory::make($body)->all()[1];

        $this->assertSame(self::CMR, $item->getVoucherAmountDetails()->getCmrDiscount());
        $this->assertSame(0.0, $item->getVoucherAmountDetails()->getOtherDiscount());
        $this->assertSame($item->getVoucherAmount(), $item->getVoucherAmountDetails()->getTotal());

        $order = $this->ordersXml()->Orders->Order[0];
        $this->addOrderVoucherDetails($order, '10790.00', '0.00', '10790.00');
        $model = OrderFactory::make($order);
        $this->assertSame(0.0, $model->getVoucherDetails()->getOtherDiscount());
        $this->assertSame($model->getVoucher(), $model->getVoucherDetails()->getTotal());
    }

    public function testTc14OnlyTraditionalCoupons(): void
    {
        // New contract: CmrDiscount = 0
        $body = $this->itemsXml();
        $this->configureItem($body->OrderItems->OrderItem[0], 1, '50000.00', '45000.00', '5000.00', ['0.00', '5000.00', null]);
        $item = OrderItemsFactory::make($body)->all()[1];

        $this->assertSame(0.0, $item->getVoucherAmountDetails()->getCmrDiscount());
        $this->assertSame(5000.0, $item->getVoucherAmountDetails()->getOtherDiscount());
        $this->assertNull($item->getVoucherAmountDetails()->getCmrDiscountSellerFundedPct());
        $this->assertSame(5000.0, $item->getVoucherAmount());

        // Legacy response (no new tags): voucher calculations unchanged
        $legacy = OrderItemsFactory::make(simplexml_load_string($this->getSchema('Order/OrderItemsResponse.xml'))->Body)->all()[6750999];
        $this->assertNull($legacy->getVoucherAmountDetails());
        $this->assertSame(13.65, $legacy->getVoucherAmount());
        $this->assertSame(89900.0, $legacy->getPaidPrice());
    }

    public function testTc15NoDiscounts(): void
    {
        $body = $this->itemsXml();
        $this->configureItem($body->OrderItems->OrderItem[0], 1, '20000.00', '20000.00', '0.00', ['0.00', '0.00', null]);
        $item = OrderItemsFactory::make($body)->all()[1];

        $this->assertSame(0.0, $item->getVoucherAmount());
        $this->assertSame(0.0, $item->getVoucherAmountDetails()->getTotal());
        $this->assertNull($item->getVoucherAmountDetails()->getCmrDiscountSellerFundedAmount());
        $this->assertSame($item->getItemPrice(), $item->getPaidPrice());

        $order = OrderFactory::make($this->ordersXml()->Orders->Order[0]);
        $this->assertSame(0.0, $order->getVoucher());
        $this->assertNull($order->getVoucherDetails());
    }

    // ---------------------------------------------------------------- TC16

    public function testTc16MultiItemMixedDiscounts(): void
    {
        $items = OrderItemsFactory::make($this->buildMixedOrderItems());
        $this->assertCount(3, $items->all());

        $a = $items->findByOrderItemId(1)->getVoucherAmountDetails();
        $b = $items->findByOrderItemId(2)->getVoucherAmountDetails();
        $c = $items->findByOrderItemId(3)->getVoucherAmountDetails();

        $this->assertSame([10790.0, 22821.0, 35.5], [$a->getCmrDiscount(), $a->getOtherDiscount(), $a->getCmrDiscountSellerFundedPct()]);
        $this->assertSame([0.0, 5000.0, null], [$b->getCmrDiscount(), $b->getOtherDiscount(), $b->getCmrDiscountSellerFundedPct()]);
        $this->assertSame([0.0, 0.0, null], [$c->getCmrDiscount(), $c->getOtherDiscount(), $c->getCmrDiscountSellerFundedPct()]);

        $cmr = $other = $voucherAmount = 0.0;
        foreach ($items->all() as $item) {
            $this->assertSame($item->getVoucherAmount(), $item->getVoucherAmountDetails()->getTotal());
            $cmr += $item->getVoucherAmountDetails()->getCmrDiscount();
            $other += $item->getVoucherAmountDetails()->getOtherDiscount();
            $voucherAmount += $item->getVoucherAmount();
        }

        $body = $this->ordersXml();
        $this->addOrderVoucherDetails($body->Orders->Order[0], '10790.00', '27821.00', '38611.00');
        $order = array_values(OrdersFactory::make($body)->all())[0];

        $this->assertSame($cmr, $order->getVoucherDetails()->getCmrDiscount());
        $this->assertSame($other, $order->getVoucherDetails()->getOtherDiscount());
        $this->assertSame($voucherAmount, $order->getVoucher());
    }

    // ---------------------------------------------------------------- Regression

    public function testRegressionLegacyResponsesStillParse(): void
    {
        $orders = OrdersFactory::make($this->ordersXml());
        foreach ($orders->all() as $order) {
            $this->assertNull($order->getVoucherDetails());
        }

        $ordersV2 = OrdersFactoryV2::make($this->ordersXml(true));
        foreach ($ordersV2->all() as $order) {
            $this->assertNull($order->getVoucherDetails());
        }

        $items = OrderItemsFactory::make(simplexml_load_string($this->getSchema('Order/OrderItemsResponse.xml'))->Body);
        foreach ($items->all() as $item) {
            $this->assertNull($item->getVoucherAmountDetails());
        }
    }
}
