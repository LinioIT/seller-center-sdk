<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Webhook;

use Linio\SellerCenter\Factory\Xml\Webhook\WebhooksFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Webhook\Webhook;
use Linio\SellerCenter\Model\Webhook\Webhooks;

class WebhooksTest extends LinioTestCase
{
    public function testItFindsAndReturnTheWebhookById(): void
    {
        $response = $this->getResponseMock();
        $webhooks = WebhooksFactory::make($response->Body);

        $webhookId = 'fbfe60be-b282-4bc1-9e4d-2147c686d1a8';

        $webhook = $webhooks->findByWebhookId($webhookId);

        $this->assertInstanceOf(Webhooks::class, $webhooks);
        $this->assertContainsOnlyInstancesOf(Webhook::class, $webhooks->all());
        $this->assertInstanceOf(Webhook::class, $webhook);
        $this->assertEquals($webhookId, $webhook->getWebhookId());
    }

    public function testItReturnsAnEmptyValueWhenNoWebhookWasFound(): void
    {
        $response = $this->getResponseMock();
        $webhooks = WebhooksFactory::make($response->Body);

        $webhook = $webhooks->findByWebhookId('non-existent-webhook-id');

        $this->assertNull($webhook);
    }

    public function testItCreatesAWebHookFromAXml(): void
    {
        $response = $this->getResponseMock();
        $webhooks = WebhooksFactory::make($response->Body);

        $webhookId = '7dffaa4e-1713-42c2-84ba-1d2fbd4537ab';

        $webhook = $webhooks->findByWebhookId($webhookId);

        $xmlWebhook = $response->Body->Webhooks->Webhook[0];

        $this->assertInstanceOf(Webhooks::class, $webhooks);
        $this->assertInstanceOf(WebHook::class, $webhook);
        $this->assertEquals($webhook->getWebhookId(), $xmlWebhook->WebhookId);
    }

    public function getResponseMock($xml = null)
    {
        if (empty($xml)) {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>
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

        return simplexml_load_string($xml);
    }
}
