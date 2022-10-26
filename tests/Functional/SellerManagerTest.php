<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Exception;
use Linio\SellerCenter\Application\Configuration;

class SellerManagerTest extends LinioTestCase
{
    use ClientHelper;

    public function testItReturnsACollectionOfBrands(): void
    {
        $body = '<?xml version="1.0" encoding="UTF-8"?>
        <SuccessResponse>
            <Head>
                <RequestId/>
                <RequestAction>GetStatistics</RequestAction>
                <ResponseType/>
                <Timestamp>2022-10-26T15:27:57-0500</Timestamp>
            </Head>
            <Body>
                <Products>
                    <Status>
                        <Active>5036</Active>
                        <All>5963</All>
                        <Deleted>90</Deleted>
                        <ImageMissing>1156</ImageMissing>
                        <Inactive>927</Inactive>
                        <Live>1486</Live>
                        <Pending>103</Pending>
                        <PoorQuality>1790</PoorQuality>
                        <SoldOut>2084</SoldOut>
                    </Status>
                    <Total>6053</Total>
                </Products>
                <Orders>
                    <Status>
                        <Canceled>436</Canceled>
                        <Delivered>2824</Delivered>
                        <Digital>0</Digital>
                        <Economy>1</Economy>
                        <Express>0</Express>
                        <Failed>45</Failed>
                        <NoExtInvoiceKey>3113</NoExtInvoiceKey>
                        <NotPrintedPending>4</NotPrintedPending>
                        <NotPrintedReadyToShip>0</NotPrintedReadyToShip>
                        <Pending>4</Pending>
                        <ReadyToShip>4</ReadyToShip>
                        <ReturnRejected>2</ReturnRejected>
                        <ReturnShippedByCustomer>0</ReturnShippedByCustomer>
                        <ReturnWaitingForApproval>0</ReturnWaitingForApproval>
                        <Returned>123</Returned>
                        <Shipped>10</Shipped>
                        <Standard>3</Standard>
                    </Status>
                    <Total>3449</Total>
                </Orders>
                <OrdersItemsPending>
                    <Today>1</Today>
                    <Yesterday>1</Yesterday>
                    <Older>3</Older>
                </OrdersItemsPending>
                <AccountHealth>
                    <Day>
                        <TwoDaysShippedPercentage>
                            <Percentage>0.00</Percentage>
                            <Text>bad</Text>
                        </TwoDaysShippedPercentage>
                        <ReturnPercentage>
                            <Percentage>0.00</Percentage>
                            <Text>excellent</Text>
                        </ReturnPercentage>
                        <CancellationPercentage>
                            <Percentage>0.00</Percentage>
                            <Text>excellent</Text>
                        </CancellationPercentage>
                    </Day>
                    <Week>
                        <TwoDaysShippedPercentage>
                            <Percentage>100.00</Percentage>
                            <Text>excellent</Text>
                        </TwoDaysShippedPercentage>
                        <ReturnPercentage>
                            <Percentage>0.00</Percentage>
                            <Text>excellent</Text>
                        </ReturnPercentage>
                        <CancellationPercentage>
                            <Percentage>0.00</Percentage>
                            <Text>excellent</Text>
                        </CancellationPercentage>
                    </Week>
                    <Month>
                        <TwoDaysShippedPercentage>
                            <Percentage>64.00</Percentage>
                            <Text>improvable</Text>
                        </TwoDaysShippedPercentage>
                        <ReturnPercentage>
                            <Percentage>4.00</Percentage>
                            <Text>verygood</Text>
                        </ReturnPercentage>
                        <CancellationPercentage>
                            <Percentage>0.00</Percentage>
                            <Text>excellent</Text>
                        </CancellationPercentage>
                    </Month>
                    <Alltime>
                        <TwoDaysShippedPercentage>
                            <Percentage>1.00</Percentage>
                            <Text>bad</Text>
                        </TwoDaysShippedPercentage>
                        <ReturnPercentage>
                            <Percentage>4.00</Percentage>
                            <Text>verygood</Text>
                        </ReturnPercentage>
                        <CancellationPercentage>
                            <Percentage>3.00</Percentage>
                            <Text>improvable</Text>
                        </CancellationPercentage>
                    </Alltime>
                </AccountHealth>
            </Body>
        </SuccessResponse>';

        $client = $this->createClientWithResponse($body);

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->seller()->getStatistics();

        $this->assertIsArray($result);
        $this->assertEquals($result['Products']['Total'], 6053);
        $this->assertEquals($result['Products']['Active'], 5036);
        $this->assertEquals($result['Products']['All'], 5963);
        $this->assertEquals($result['Products']['Deleted'], 90);
        $this->assertEquals($result['Products']['ImageMissing'], 1156);
        $this->assertEquals($result['Products']['Inactive'], 927);
        $this->assertEquals($result['Products']['Live'], 1486);
        $this->assertEquals($result['Products']['Pending'], 103);
        $this->assertEquals($result['Products']['PoorQuality'], 1790);
        $this->assertEquals($result['Products']['SoldOut'], 2084);
        $this->assertEquals($result['Orders']['Canceled'], 436);
        $this->assertEquals($result['Orders']['Delivered'], 2824);
        $this->assertEquals($result['Orders']['Digital'], 0);
        $this->assertEquals($result['Orders']['Economy'], 1);
        $this->assertEquals($result['Orders']['Express'], 0);
        $this->assertEquals($result['Orders']['Failed'], 45);
        $this->assertEquals($result['Orders']['NoExtInvoiceKey'], 3113);
        $this->assertEquals($result['Orders']['NotPrintedPending'], 4);
        $this->assertEquals($result['Orders']['NotPrintedReadyToShip'], 0);
        $this->assertEquals($result['Orders']['Pending'], 4);
        $this->assertEquals($result['Orders']['ReadyToShip'], 4);
        $this->assertEquals($result['Orders']['ReturnRejected'], 2);
        $this->assertEquals($result['Orders']['ReturnShippedByCustomer'], 0);
        $this->assertEquals($result['Orders']['ReturnWaitingForApproval'], 0);
        $this->assertEquals($result['Orders']['Returned'], 123);
        $this->assertEquals($result['Orders']['Shipped'], 10);
        $this->assertEquals($result['Orders']['Standard'], 3);
        $this->assertEquals($result['Orders']['Total'], 3449);
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

        $env = $this->getParameters();

        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $sdkClient->seller()->getStatistics();
    }
}
