<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use Linio\SellerCenter\Model\Order\VoucherAmountDetails;
use Linio\SellerCenter\Model\Order\VoucherDetails;
use SimpleXMLElement;

final class VoucherDetailsFactory
{
    public static function make(SimpleXMLElement $element): ?VoucherDetails
    {
        if (!isset($element->VoucherDetails)) {
            return null;
        }

        $details = $element->VoucherDetails;

        return new VoucherDetails(
            self::toFloat($details->CmrDiscount) ?? 0.0,
            self::toFloat($details->OtherDiscount) ?? 0.0,
        );
    }

    public static function makeAmountDetails(SimpleXMLElement $element): ?VoucherAmountDetails
    {
        if (!isset($element->VoucherAmountDetails)) {
            return null;
        }

        $details = $element->VoucherAmountDetails;

        return new VoucherAmountDetails(
            self::toFloat($details->CmrDiscount) ?? 0.0,
            self::toFloat($details->OtherDiscount) ?? 0.0,
            self::toFloat($details->CmrDiscountSellerFundedPct)
        );
    }

    /**
     * Parses amounts such as "10790.00" or "10,790.00"; returns null when empty.
     */
    public static function toFloat(?SimpleXMLElement $value): ?float
    {
        $raw = str_replace(',', '', trim((string) $value));

        return $raw === '' || !is_numeric($raw) ? null : (float) $raw;
    }
}
