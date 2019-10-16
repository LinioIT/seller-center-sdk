<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use DateTimeImmutable;
use Exception;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Exception\ErrorResponseException;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Model\Order\FailureReason;
use Linio\SellerCenter\Model\Order\Order;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Model\Order\OrderItems;
use Linio\SellerCenter\Service\OrderManager;

class OrdersManagerTest extends LinioTestCase
{
    use ClientHelper;

    public function testItReturnsAOrder(): void
    {
        $client = $this->createClientWithResponse($this->getOrderResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $orderId = 4687503;

        $result = $sdkClient->orders()->getOrder($orderId);

        $this->assertInstanceOf(Order::class, $result);
    }

    public function testItReturnsACollectionOfOrderItems(): void
    {
        $client = $this->createClientWithResponse($this->getOrderItemsResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $orderId = 6750999;

        $result = $sdkClient->orders()->getOrderItems($orderId);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $result);
    }

    public function testItReturnAMultipleCollectionsOfOrderItems(): void
    {
        $client = $this->createClientWithResponse($this->getOrdersItemsResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $ordersId = [4687808, 6653173];

        $result = $sdkClient->orders()->getMultipleOrderItems($ordersId);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);

        foreach ($result as $order) {
            $this->assertInstanceOf(OrderItems::class, $order->getOrderItems());
            $this->assertContainsOnlyInstancesOf(OrderItem::class, $order->getOrderItems()->all());
        }
    }

    /**
     * @dataProvider dateTimesAndFilters
     */
    public function testItReturnsACollectionOfOrdersCreatedBetweenADateTime(
        ?DateTimeImmutable $createdAfter,
        ?DateTimeImmutable $createdBefore,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersCreatedBetween(
            $createdAfter,
            $createdBefore,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    /**
     * @dataProvider dateTimesAndFilters
     */
    public function testItReturnsACollectionOfOrdersUpdatedBetweenADateTime(
        ?DateTimeImmutable $updatedAfter,
        ?DateTimeImmutable $updatedBefore,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersUpdatedBetween(
            $updatedAfter,
            $updatedBefore,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    /**
     * @dataProvider dateTimesAndFilters
     */
    public function testItReturnsACollectionOfOrdersCreatedAfterADateTime(
        ?DateTimeImmutable $createdAfter,
        ?DateTimeImmutable $unused,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersCreatedAfter(
            $createdAfter,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    /**
     * @dataProvider dateTimesAndFilters
     */
    public function testItReturnsACollectionOfOrdersCreatedBeforeADateTime(
        ?DateTimeImmutable $createdBefore,
        ?DateTimeImmutable $unused,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersCreatedBefore(
            $createdBefore,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    /**
     * @dataProvider dateTimesAndFilters
     */
    public function testItReturnsACollectionOfOrdersUpdatedAfterADateTime(
        ?DateTimeImmutable $updatedAfter,
        ?DateTimeImmutable $unused,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersUpdatedAfter(
            $updatedAfter,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    /**
     * @dataProvider dateTimesAndFilters
     */
    public function testItReturnsACollectionOfOrdersUpdatedBeforeADateTime(
        ?DateTimeImmutable $updatedBefore,
        ?DateTimeImmutable $unused,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersUpdatedBefore(
            $updatedBefore,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    /**
     * @dataProvider dateTimesAndFilters
     */
    public function testItReturnsACollectionOfOrdersWithStatus(
        ?DateTimeImmutable $unused,
        ?DateTimeImmutable $unused2,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersWithStatus(
            $status,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    public function testThrowExceptionWithAInvalidStatus(): void
    {
        $this->expectException(InvalidDomainException::class);

        $this->expectExceptionMessage('The parameter Status is invalid.');

        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $status = 'invalid status';

        $result = $sdkClient->orders()->getOrdersWithStatus(
            $status,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            OrderManager::DEFAULT_SORT_BY,
            OrderManager::DEFAULT_SORT_DIRECTION
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    /**
     * @dataProvider parametersFromGetOrders
     */
    public function testItReturnsACollectionOfOrdersFromParameters(
        ?DateTimeImmutable $createdAfter,
        ?DateTimeImmutable $createdBefore,
        ?DateTimeImmutable $updatedAfter,
        ?DateTimeImmutable $updatedBefore,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersFromParameters(
            $createdAfter,
            $createdBefore,
            $updatedAfter,
            $updatedBefore,
            $status,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    public function testItThrowsAnExceptionWhenTheResponseIsAnError(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('E0125: Test Error');

        $body = '<?xml version="1.0" encoding="UTF-8"?>
        <ErrorResponse>
            <Head>
                <RequestAction>GetOrder</RequestAction>
                <ErrorType>Sender</ErrorType>
                <ErrorCode>125</ErrorCode>
                <ErrorMessage>E0125: Test Error</ErrorMessage>
            </Head>
            <Body/>
        </ErrorResponse>';

        $client = $this->createClientWithResponse($body, 400);

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $orderItems = $sdkClient->orders()->setStatusToReadyToShip([1], 'deliveryType', 'shippingProvider');

        $this->assertIsArray($orderItems);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $orderItems);

        $this->assertEquals('123456', current($orderItems)->getPurchaseOrderId());
        $this->assertEquals('ABC-123456', current($orderItems)->getPurchaseOrderNumber());
    }

    public function testItThrowsAnExceptionWhenParameterIsMissingInGetMultipleOrderItems(): void
    {
        $this->expectException(EmptyArgumentException::class);
        $this->expectExceptionMessage('The parameter OrderIdList should not be null.');

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdk = new SellerCenterSdk($configuration);

        $sdk->orders()->getMultipleOrderItems([]);
    }

    public function testItThrowsExceptionWhenSettingStatusToCanceledReturnErrorResponse(): void
    {
        $this->expectException(ErrorResponseException::class);

        $body = '<?xml version="1.0" encoding="UTF-8"?>
                <ErrorResponse>
                  <Head>
                    <RequestId></RequestId>
                    <RequestAction>SetStatusToCanceled</RequestAction>
                    <ResponseType></ResponseType>
                    <Timestamp>2013-08-27T14:44:13+0000</Timestamp>
                  </Head>
                  <Body />
                </ErrorResponse>';

        $client = $this->createClientWithResponse($body, 400);

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);
        $sdkClient->orders()->setStatusToCanceled(1, 'someReason', 'someReasonDetail');
    }

    public function testItReturnsUpdatedOrderItemsWhenSettingStatusToPackedByMarketplace(): void
    {
        $orderItemId = 1;

        $body = '<?xml version="1.0" encoding="UTF-8"?>
                <SuccessResponse>
                  <Head>
                    <RequestId></RequestId>
                    <RequestAction>SetStatusToPackedByMarketplace</RequestAction>
                    <ResponseType>OrderItems</ResponseType>
                    <Timestamp>2013-08-27T14:44:13+0000</Timestamp>
                  </Head>
                  <Body>
                    <OrderItems>
                      <OrderItem>
                        <OrderItemId>%d</OrderItemId>
                        <PurchaseOrderId>123456</PurchaseOrderId>
                        <PurchaseOrderNumber>ABC-123456</PurchaseOrderNumber>
                        <PackageId>MPDS-200131783-9800</PackageId>
                      </OrderItem>
                    </OrderItems>
                  </Body>
                </SuccessResponse>';

        $body = sprintf($body, $orderItemId);

        $client = $this->createClientWithResponse($body);

        $parameters = $this->getParameters();

        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $orderItems = $sdkClient->orders()->setStatusToPackedByMarketplace(
            [$orderItemId],
            'deliveryType',
            'shippingProvider',
            'nxsqonoqsnoc',
            '2kn412on3io1b3o'
        );

        $this->assertIsArray($orderItems);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $orderItems);
        $this->assertEquals($orderItemId, current($orderItems)->getOrderItemId());
        $this->assertEquals(123456, current($orderItems)->getPurchaseOrderId());
        $this->assertEquals('ABC-123456', current($orderItems)->getPurchaseOrderNumber());
        $this->assertEquals('MPDS-200131783-9800', current($orderItems)->getPackageId());
    }

    public function testItReturnsUpdatedOrderItemsWhenSettingStatusToReadyToShip(): void
    {
        $body = '<?xml version="1.0" encoding="UTF-8"?>
                <SuccessResponse>
                  <Head>
                    <RequestId></RequestId>
                    <RequestAction>SetStatusToReadyToShip</RequestAction>
                    <ResponseType>OrderItems</ResponseType>
                    <Timestamp>2013-08-27T14:44:13+0000</Timestamp>
                  </Head>
                  <Body>
                    <OrderItems>
                      <OrderItem>
                        <PurchaseOrderId>123456</PurchaseOrderId>
                        <PurchaseOrderNumber>ABC-123456</PurchaseOrderNumber>
                      </OrderItem>
                    </OrderItems>
                  </Body>
                </SuccessResponse>';

        $client = $this->createClientWithResponse($body);

        $parameters = $this->getParameters();

        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $orderItems = $sdkClient->orders()->setStatusToReadyToShip(
            [1],
            'deliveryType',
            'shippingProvider',
            'nxsqonoqsnoc',
            '2kn412on3io1b3o'
        );

        $this->assertIsArray($orderItems);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $orderItems);
        $this->assertEquals('123456', current($orderItems)->getPurchaseOrderId());
        $this->assertEquals('ABC-123456', current($orderItems)->getPurchaseOrderNumber());
    }

    public function testItReturnFailureReasons(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <SuccessResponse>
              <Head>
                <RequestId></RequestId>
                <RequestAction>GetFailureReasons</RequestAction>
                <ResponseType>Reasons</ResponseType>
                <Timestamp>2013-08-27T14:44:13+0000</Timestamp>
              </Head>
              <Body>
                <Reasons>
                  <Reason>
                    <Type>canceled</Type>
                    <Name>Sourcing team couldn\'t find items</Name>
                  </Reason>
                  <Reason>
                    <Type>canceled</Type>
                    <Name>Wrong address</Name>
                  </Reason>
                </Reasons>
              </Body>
            </SuccessResponse>
         ';

        $client = $this->createClientWithResponse($xml);
        $parameters = $this->getParameters();

        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $failureReasons = $sdkClient->orders()->getFailureReasons();

        $this->assertContainsOnlyInstancesOf(FailureReason::class, $failureReasons);
    }

    public function dateTimesAndFilters(): array
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2018-09-01 00:00:00');

        return [
            [$date, $date, 'created_at', 'ASC', 'pending'],
            [$date, $date, 'updated_at', 'ASC', 'canceled'],
            [$date, $date, 'created_at', 'DESC', 'ready_to_ship'],
            [$date, $date, 'updated_at', 'DESC', 'delivered'],
            [$date, $date, 'created_at', 'ASC', 'returned'],
            [$date, $date, 'updated_at', 'DESC', 'shipped'],
            [$date, $date, 'created_at', 'ASC', 'failed'],
        ];
    }

    public function parametersFromGetOrders(): array
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2018-09-01 00:00:00');

        return [
            [$date, $date, $date, $date, 'created_at', 'ASC', 'pending'],
            [null, $date, null, $date, 'updated_at', 'ASC', 'canceled'],
            [$date, null, $date, null, 'invalid', 'DESC', 'ready_to_ship'],
            [$date, $date, null, null, 'updated_at', 'invalid', 'delivered'],
            [null, $date, $date, null, 'created_at', 'ASC', 'returned'],
            [$date, null, null, $date, 'invalid', 'invalid', 'shipped'],
            [null, null, null, null, 'created_at', 'ASC', 'failed'],
        ];
    }

    public function getOrderResponse(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
                <SuccessResponse>
                    <Head>
                      <RequestId/>
                      <RequestAction>GetOrder</RequestAction>
                      <ResponseType>Order</ResponseType>
                      <Timestamp>2019-02-27T12:52:46-0500</Timestamp>
                    </Head>
                    <Body>
                      <Orders>
                           <Order>
                                <OrderId>4687503</OrderId>
                                <CustomerFirstName>first_name+4687503</CustomerFirstName>
                                <CustomerLastName>last_name+</CustomerLastName>
                                <OrderNumber>206125233</OrderNumber>
                                <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                                <Remarks/>
                                <DeliveryInfo>SE PAGA CON TARGETA DEBITO</DeliveryInfo>
                                <Price>61800.00</Price>
                                <GiftOption>0</GiftOption>
                                <GiftMessage/>
                                <VoucherCode/>
                                <CreatedAt>2018-08-27 08:48:20</CreatedAt>
                                <UpdatedAt>2018-08-28 17:09:47</UpdatedAt>
                                <AddressUpdatedAt>2018-08-27 13:48:20</AddressUpdatedAt>
                                <AddressBilling>
                                     <FirstName>Labs</FirstName>
                                     <LastName>Rocket</LastName>
                                     <Phone/>
                                     <Phone2/>
                                     <Address1>Johannisstr. 20</Address1>
                                     <Address2/>
                                     <Address3/>
                                     <Address4/>
                                     <Address5/>
                                     <CustomerEmail/>
                                     <City>Berlin</City>
                                     <Ward/>
                                     <Region/>
                                     <PostCode>10117</PostCode>
                                     <Country>Germany</Country>
                                </AddressBilling>
                                <AddressShipping>
                                     <FirstName>Rocket</FirstName>
                                     <LastName>Labs</LastName>
                                     <Phone/>
                                     <Phone2/>
                                     <Address1>Charlottenstraße 4</Address1>
                                     <Address2/>
                                     <Address3/>
                                     <Address4/>
                                     <Address5/>
                                     <CustomerEmail/>
                                     <City>Berlin</City>
                                     <Ward/>
                                     <Region/>
                                     <PostCode>10969</PostCode>
                                     <Country>Germany</Country>
                                </AddressShipping>
                                <NationalRegistrationNumber>94526898</NationalRegistrationNumber>
                                <ItemsCount>1</ItemsCount>
                                <PromisedShippingTime>2018-08-28 16:00:00</PromisedShippingTime>
                                <ExtraAttributes/>
                                <Statuses>
                                     <Status>delivered</Status>
                                     <Status>pending</Status>
                                </Statuses>
                           </Order>
                      </Orders>
                    </Body>
                </SuccessResponse>';
    }

    public function getOrdersResponse(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
                <SuccessResponse>
                     <Head>
                          <RequestId/>
                          <RequestAction>GetOrderItems</RequestAction>
                          <ResponseType>OrderItems</ResponseType>
                          <Timestamp>2019-01-18T06:54:50-0500</Timestamp>
                     </Head>
                    <Body>
                      <Orders>
                           <Order>
                                <OrderId>4687503</OrderId>
                                <CustomerFirstName>first_name+4687503</CustomerFirstName>
                                <CustomerLastName>last_name+</CustomerLastName>
                                <OrderNumber>206125233</OrderNumber>
                                <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                                <Remarks/>
                                <DeliveryInfo>SE PAGA CON TARGETA DEBITO</DeliveryInfo>
                                <Price>61800.00</Price>
                                <GiftOption>0</GiftOption>
                                <GiftMessage/>
                                <VoucherCode/>
                                <CreatedAt>2018-08-27 08:48:20</CreatedAt>
                                <UpdatedAt>2018-08-28 17:09:47</UpdatedAt>
                                <AddressUpdatedAt>2018-08-27 13:48:20</AddressUpdatedAt>
                                <AddressBilling>
                                     <FirstName>Labs</FirstName>
                                     <LastName>Rocket</LastName>
                                     <Phone/>
                                     <Phone2/>
                                     <Address1>Johannisstr. 20</Address1>
                                     <CustomerEmail/>
                                     <City>Berlin</City>
                                     <Ward/>
                                     <Region/>
                                     <PostCode>10117</PostCode>
                                     <Country>Germany</Country>
                                </AddressBilling>
                                <AddressShipping>
                                     <FirstName>Rocket</FirstName>
                                     <LastName>Labs</LastName>
                                     <Phone/>
                                     <Phone2/>
                                     <Address1>Charlottenstraße 4</Address1>
                                     <CustomerEmail/>
                                     <City>Berlin</City>
                                     <Ward/>
                                     <Region/>
                                     <PostCode>10969</PostCode>
                                     <Country>Germany</Country>
                                </AddressShipping>
                                <NationalRegistrationNumber>94526898</NationalRegistrationNumber>
                                <ItemsCount>1</ItemsCount>
                                <PromisedShippingTime>2018-08-28 16:00:00</PromisedShippingTime>
                                <ExtraAttributes/>
                                <Statuses>
                                     <Status>delivered</Status>
                                     <Status>shipped</Status>
                                </Statuses>
                           </Order>
                           <Order>
                                <OrderId>4687808</OrderId>
                                <CustomerFirstName>first_name+4687808</CustomerFirstName>
                                <CustomerLastName>last_name+</CustomerLastName>
                                <OrderNumber>800327689</OrderNumber>
                                <PaymentMethod>Avianca_Miles_Only_Payment</PaymentMethod>
                                <Remarks/>
                                <DeliveryInfo/>
                                <Price>125806.85</Price>
                                <GiftOption>0</GiftOption>
                                <GiftMessage/>
                                <VoucherCode/>
                                <CreatedAt>2018-08-27 11:40:23</CreatedAt>
                                <UpdatedAt>2018-08-28 16:09:03</UpdatedAt>
                                <AddressUpdatedAt>2018-08-27 16:40:23</AddressUpdatedAt>
                                <AddressBilling>
                                     <FirstName>Labs</FirstName>
                                     <LastName>Rocket</LastName>
                                     <Phone/>
                                     <Phone2/>
                                     <Address1>Johannisstr. 20</Address1>
                                     <Address2/>
                                     <Address3/>
                                     <Address4/>
                                     <Address5/>
                                     <CustomerEmail/>
                                     <City>Berlin</City>
                                     <Ward/>
                                     <Region/>
                                     <PostCode>10117</PostCode>
                                     <Country>Germany</Country>
                                </AddressBilling>
                                <AddressShipping>
                                     <FirstName>Rocket</FirstName>
                                     <LastName>Labs</LastName>
                                     <Phone/>
                                     <Phone2/>
                                     <Address1>Charlottenstraße 4</Address1>
                                     <Address2/>
                                     <Address3/>
                                     <Address4/>
                                     <Address5/>
                                     <CustomerEmail/>
                                     <City>Berlin</City>
                                     <Ward/>
                                     <Region/>
                                     <PostCode>10969</PostCode>
                                     <Country>Germany</Country>
                                </AddressShipping>
                                <NationalRegistrationNumber>76043323</NationalRegistrationNumber>
                                <ItemsCount>1</ItemsCount>
                                <PromisedShippingTime>2018-08-28 16:00:00</PromisedShippingTime>
                                <ExtraAttributes/>
                                <Statuses>
                                     <Status>delivered</Status>
                                     <Status>canceled</Status>
                                </Statuses>
                           </Order>
                      </Orders>
                    </Body>
                </SuccessResponse>';
    }

    public function getOrderItemsResponse(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
                <SuccessResponse>
                     <Head>
                          <RequestId/>
                          <RequestAction>GetOrderItems</RequestAction>
                          <ResponseType>OrderItems</ResponseType>
                          <Timestamp>2019-01-18T06:54:50-0500</Timestamp>
                     </Head>
                     <Body>
                          <OrderItems>
                               <OrderItem>
                                    <OrderItemId>6750999</OrderItemId>
                                    <ShopId>7208215</ShopId>
                                    <OrderId>4758978</OrderId>
                                    <Name>MEGIR 5006 RELOJ ACERO INOXIDABLE ROSA</Name>
                                    <Sku>DJFKLJOEDKLFJ</Sku>
                                    <Variation>Talla Única</Variation>
                                    <ShopSku>ME803FA0UEI9YLCO-6073653</ShopSku>
                                    <ShippingType>Dropshipping</ShippingType>
                                    <ItemPrice>89900.00</ItemPrice>
                                    <PaidPrice>89900.00</PaidPrice>
                                    <Currency>COP</Currency>
                                    <WalletCredits>0.00</WalletCredits>
                                    <TaxAmount>0.00</TaxAmount>
                                    <CodCollectableAmount/>
                                    <ShippingAmount>0.00</ShippingAmount>
                                    <ShippingServiceCost>7000.00</ShippingServiceCost>
                                    <VoucherAmount>0</VoucherAmount>
                                    <VoucherCode/>
                                    <Status>pending</Status>
                                    <IsProcessable>1</IsProcessable>
                                    <ShipmentProvider>LOGISTICA</ShipmentProvider>
                                    <IsDigital>0</IsDigital>
                                    <DigitalDeliveryInfo/>
                                    <TrackingCode>1000414030800</TrackingCode>
                                    <TrackingCodePre/>
                                    <Reason/>
                                    <ReasonDetail/>
                                    <PurchaseOrderId>0</PurchaseOrderId>
                                    <PurchaseOrderNumber/>
                                    <PackageId>1000414030800</PackageId>
                                    <PromisedShippingTime>2018-10-16 20:00:00</PromisedShippingTime>
                                    <ExtraAttributes/>
                                    <ShippingProviderType>express</ShippingProviderType>
                                    <CreatedAt>2018-10-13 23:08:34</CreatedAt>
                                    <UpdatedAt>2018-10-14 13:30:50</UpdatedAt>
                                    <ReturnStatus/>
                               </OrderItem>
                          </OrderItems>
                     </Body>
                </SuccessResponse>';
    }

    public function getOrdersItemsResponse(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
                <SuccessResponse>
                     <Head>
                          <RequestId/>
                          <RequestAction>GetMultipleOrderItems</RequestAction>
                          <ResponseType>OrderItems</ResponseType>
                          <Timestamp>2019-01-18T06:54:50-0500</Timestamp>
                     </Head>
                    <Body>
                      <Orders>
                           <Order>
                                <OrderId>4687503</OrderId>
                                <OrderNumber>206125233</OrderNumber>
                                <OrderItems>
                                     <OrderItem>
                                          <OrderItemId>6653173</OrderItemId>
                                          <ShopId>7068646</ShopId>
                                          <OrderId>4687503</OrderId>
                                          <Name>RELOJ WEIDE DE ACERO INOXIDABLE DE ALTA CALIDAD 1009 SILVER BLACK</Name>
                                          <Sku>WE817FA21TYC3LCO</Sku>
                                          <Variation>Talla Única</Variation>
                                          <ShopSku>WE895FA0Z828WLCO-2510173</ShopSku>
                                          <ShippingType>Dropshipping</ShippingType>
                                          <ItemPrice>55900.00</ItemPrice>
                                          <PaidPrice>55900.00</PaidPrice>
                                          <Currency>COP</Currency>
                                          <WalletCredits>0.00</WalletCredits>
                                          <TaxAmount>0.00</TaxAmount>
                                          <CodCollectableAmount/>
                                          <ShippingAmount>5900.00</ShippingAmount>
                                          <ShippingServiceCost>4442.02</ShippingServiceCost>
                                          <VoucherAmount>0</VoucherAmount>
                                          <VoucherCode/>
                                          <Status>delivered</Status>
                                          <IsProcessable>1</IsProcessable>
                                          <ShipmentProvider>TCC</ShipmentProvider>
                                          <IsDigital>0</IsDigital>
                                          <DigitalDeliveryInfo/>
                                          <TrackingCode>427573467</TrackingCode>
                                          <TrackingCodePre/>
                                          <Reason/>
                                          <ReasonDetail/>
                                          <PurchaseOrderId>0</PurchaseOrderId>
                                          <PurchaseOrderNumber/>
                                          <PackageId>1000405653600</PackageId>
                                          <PromisedShippingTime>2018-08-28 16:00:00</PromisedShippingTime>
                                          <ExtraAttributes/>
                                          <ShippingProviderType>standard</ShippingProviderType>
                                          <CreatedAt>2018-08-27 08:48:20</CreatedAt>
                                          <UpdatedAt>2018-08-28 17:09:47</UpdatedAt>
                                          <ReturnStatus/>
                                     </OrderItem>
                                </OrderItems>
                           </Order>
                           <Order>
                                <OrderId>4687808</OrderId>
                                <OrderNumber>800327689</OrderNumber>
                                <OrderItems>
                                     <OrderItem>
                                          <OrderItemId>6653602</OrderItemId>
                                          <ShopId>7069240</ShopId>
                                          <OrderId>4687808</OrderId>
                                          <Name>Reloj Megir ML 2020 Impermeable Japones De Cuarzo Negro</Name>
                                          <Sku>EP162FA80HRBLCO</Sku>
                                          <Variation>Talla Única</Variation>
                                          <ShopSku>ME803FA03DRNYLCO-3219643</ShopSku>
                                          <ShippingType>Dropshipping</ShippingType>
                                          <ItemPrice>119903.23</ItemPrice>
                                          <PaidPrice>119903.23</PaidPrice>
                                          <Currency>COP</Currency>
                                          <WalletCredits>0.00</WalletCredits>
                                          <TaxAmount>0.00</TaxAmount>
                                          <CodCollectableAmount/>
                                          <ShippingAmount>5903.62</ShippingAmount>
                                          <ShippingServiceCost>4020.27</ShippingServiceCost>
                                          <VoucherAmount>0</VoucherAmount>
                                          <VoucherCode/>
                                          <Status>delivered</Status>
                                          <IsProcessable>1</IsProcessable>
                                          <ShipmentProvider>Deprisa</ShipmentProvider>
                                          <IsDigital>0</IsDigital>
                                          <DigitalDeliveryInfo/>
                                          <TrackingCode>999046151929</TrackingCode>
                                          <TrackingCodePre/>
                                          <Reason/>
                                          <ReasonDetail/>
                                          <PurchaseOrderId>0</PurchaseOrderId>
                                          <PurchaseOrderNumber/>
                                          <PackageId>1000405682300</PackageId>
                                          <PromisedShippingTime>2018-08-28 16:00:00</PromisedShippingTime>
                                          <ExtraAttributes/>
                                          <ShippingProviderType>standard</ShippingProviderType>
                                          <CreatedAt>2018-08-27 11:40:23</CreatedAt>
                                          <UpdatedAt>2018-08-28 16:09:03</UpdatedAt>
                                          <ReturnStatus/>
                                     </OrderItem>
                                </OrderItems>
                           </Order>
                      </Orders>
                 </Body>
             </SuccessResponse>';
    }
}
