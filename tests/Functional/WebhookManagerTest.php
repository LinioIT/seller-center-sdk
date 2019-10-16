<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Exception;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Exception\InvalidUrlException;
use Linio\SellerCenter\Model\Webhook\Webhook;

class WebhookManagerTest extends LinioTestCase
{
    use ClientHelper;

    protected $callbackUrl = 'http://example.com/callback';

    public function testItThrowsAnExceptionWithAnInvalidCallbackUrl(): void
    {
        $invalidUrl = 'this-is-not-an-url';

        $this->expectException(InvalidUrlException::class);

        $this->expectExceptionMessage(sprintf('The url \'%s\' is not valid', $invalidUrl));

        $client = $this->createClientWithResponse($this->getResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $sdkClient->webhooks()->createWebhook($invalidUrl);
    }

    public function testItThrowsAnExceptionWithANullWebhookId(): void
    {
        $invalidWebhookId = '';

        $this->expectException(EmptyArgumentException::class);

        $this->expectExceptionMessage('The parameter WebhookId should not be null.');

        $client = $this->createClientWithResponse($this->getResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $sdkClient->webhooks()->deleteWebhook($invalidWebhookId);
    }

    public function testItReturnsACollectionOfWebhooks(): void
    {
        $client = $this->createClientWithResponse($this->getResponse());

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->webhooks()->getAllWebhooks();

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Webhook::class, $result);
    }

    public function testItReturnsACollectionOfWebhooksByIds(): void
    {
        $client = $this->createClientWithResponse($this->getResponse());

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $webhookIds = ['7dffaa4e-1713-42c2-84ba-1d2fbd4537ab', 'fbfe60be-b282-4bc1-9e4d-2147c686d1a8'];

        $result = $sdkClient->webhooks()->getWebhooksByIds($webhookIds);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Webhook::class, $result);
    }

    public function testItThrowsAnExceptionWithANullWebhookIds(): void
    {
        $this->expectException(EmptyArgumentException::class);

        $this->expectExceptionMessage('The parameter WebhookIds should not be null.');

        $client = $this->createClientWithResponse($this->getResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

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

        $client = $this->createClientWithResponse($body, 400);

        $env = $this->getParameters();

        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $sdkClient->webhooks()->getAllWebhooks();
    }

    private function getResponse(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                         <Head>
                              <RequestId/>
                              <RequestAction>GetWebhooks</RequestAction>
                              <ResponseType>Webhooks</ResponseType>
                              <Timestamp>2016-06-07T18:35:09+0200</Timestamp>
                         </Head>
                         <Body>
                              <Webhooks>
                                   <Webhook>
                                        <WebhookId>7dffaa4e-1713-42c2-84ba-1d2fbd4537ab</WebhookId>
                                        <CallbackUrl>http://localhost/callbacks/1</CallbackUrl>
                                        <WebhookSource>web</WebhookSource>
                                        <Events>
                                             <Event>onProductCreated</Event>
                                        </Events>
                                   </Webhook>
                                   <Webhook>
                                        <WebhookId>fbfe60be-b282-4bc1-9e4d-2147c686d1a8</WebhookId>
                                        <CallbackUrl>http://localhost/callbacks/2k</CallbackUrl>
                                        <WebhookSource>api</WebhookSource>
                                        <Events>
                                             <Event>onOrderCreated</Event>
                                        </Events>
                                   </Webhook>
                              </Webhooks>
                         </Body>
                    </SuccessResponse>';
    }
}
