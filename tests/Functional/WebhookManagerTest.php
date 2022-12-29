<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Exception;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Exception\InvalidUrlException;
use Linio\SellerCenter\Model\Webhook\Webhook;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class WebhookManagerTest extends LinioTestCase
{
    use ClientHelper;

    /**
     * @var ObjectProphecy
     */
    protected $logger;

    protected $callbackUrl = 'http://example.com/callback';

    public function prepareLogTest(bool $debug): void
    {
        $this->logger = $this->prophesize(LoggerInterface::class);

        $this->logger->debug(
            Argument::type('string'),
            Argument::type('array')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->debug(
                Argument::type('string'),
                Argument::type('array')
            )->shouldNotBeCalled();
        }
    }

    public function testItThrowsAnExceptionWithAnInvalidCallbackUrl(): void
    {
        $invalidUrl = 'this-is-not-an-url';

        $this->expectException(InvalidUrlException::class);

        $this->expectExceptionMessage(sprintf('The url \'%s\' is not valid', $invalidUrl));

        $sdkClient = $this->getSdkClient($this->getSchema('Webhooks/GetWebhooksSuccessResponse.xml'));

        $sdkClient->webhooks()->createWebhook($invalidUrl);
    }

    public function testItThrowsAnExceptionWithANullWebhookId(): void
    {
        $invalidWebhookId = '';

        $this->expectException(EmptyArgumentException::class);

        $this->expectExceptionMessage('The parameter WebhookId should not be null.');

        $sdkClient = $this->getSdkClient($this->getSchema('Webhooks/GetWebhooksSuccessResponse.xml'));

        $sdkClient->webhooks()->deleteWebhook($invalidWebhookId);
    }

    public function testItReturnsACollectionOfWebhooks(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Webhooks/GetWebhooksSuccessResponse.xml'));

        $result = $sdkClient->webhooks()->getAllWebhooks();

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Webhook::class, $result);
    }

    public function testItReturnsACollectionOfWebhooksByIds(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Webhooks/GetWebhooksSuccessResponse.xml'));

        $webhookIds = ['7dffaa4e-1713-42c2-84ba-1d2fbd4537ab', 'fbfe60be-b282-4bc1-9e4d-2147c686d1a8'];

        $result = $sdkClient->webhooks()->getWebhooksByIds($webhookIds);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Webhook::class, $result);
    }

    public function testItThrowsAnExceptionWithANullWebhookIds(): void
    {
        $this->expectException(EmptyArgumentException::class);

        $this->expectExceptionMessage('The parameter WebhookIds should not be null.');

        $sdkClient = $this->getSdkClient($this->getSchema('Webhooks/GetWebhooksSuccessResponse.xml'));

        $sdkClient->webhooks()->getWebhooksByIds([]);
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

        $sdkClient = $this->getSdkClient(
            $body,
            null,
            400
        );

        $sdkClient->webhooks()->getAllWebhooks();
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenCreateWebhookSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);

        $sdkClient = $this->getSdkClient(
            $this->getSchema('Webhooks/CreateWebhooksSuccessReponse.xml'),
            $this->logger,
            200,
            $this->getSchema('Webhooks/GetWebhooksEntitiesSuccesResponse.xml')
        );

        $sdkClient->webhooks()->createWebhook(
            'http://example.com/callback',
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenDeleteWebhookSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Webhooks/DeleteWebhooksSuccessResponse.xml'),
            $this->logger,
            200
        );

        $sdkClient->webhooks()->deleteWebhook(
            'aa7e85d6-f3ee-4138-bc43-469a69b74bee',
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetAllWebhooksSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Webhooks/GetWebhooksSuccessResponse.xml'),
            $this->logger,
            200
        );

        $sdkClient->webhooks()->getAllWebhooks($debug);
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetWebhooksByIdsSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Webhooks/GetWebhooksSuccessResponse.xml'),
            $this->logger,
            200
        );

        $sdkClient->webhooks()->getWebhooksByIds(
            [
                '7dffaa4e-1713-42c2-84ba-1d2fbd4537ab',
                'fbfe60be-b282-4bc1-9e4d-2147c686d1a8',
            ],
            $debug
        );
    }

    public function debugParameter()
    {
        return [
            [false],
            [true],
        ];
    }

    private function getResponse(): string
    {
        return '';
    }
}
