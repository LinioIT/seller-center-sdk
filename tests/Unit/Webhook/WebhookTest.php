<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Webhook;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Webhook\WebhookFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Webhook\Events;
use Linio\SellerCenter\Model\Webhook\Webhook;
use SimpleXMLElement;

class WebhookTest extends LinioTestCase
{
    public function testItCreatesAWebhookFromAnXml(): void
    {
        $webhookId = '7dffaa4e-1713-42c2-84ba-1d2fbd4537ab';
        $callbackUrl = 'http://localhost/callbacks/1';
        $webhookSource = 'api';
        $event1 = 'onProductCreated';
        $event2 = 'onOrderCreated';
        $event3 = 'onOrderItemsStatusChanged';

        $xml = sprintf(
            '<Webhook>
                    <WebhookId>%s</WebhookId>
                    <CallbackUrl>%s</CallbackUrl>
                    <WebhookSource>%s</WebhookSource>
                    <Events>
                        <Event>%s</Event>
                        <Event>%s</Event>
                        <Event>%s</Event>
                    </Events>        
                </Webhook>',
            $webhookId,
            $callbackUrl,
            $webhookSource,
            $event1,
            $event2,
            $event3
        );

        $webhook = WebhookFactory::make(new SimpleXMLElement($xml));

        $this->assertInstanceOf(Webhook::class, $webhook);
        $this->assertEquals($webhook->getWebhookId(), $webhookId);
        $this->assertEquals($webhook->getCallbackUrl(), $callbackUrl);
        $this->assertEquals($webhook->getWebhookSource(), $webhookSource);
        $this->assertInstanceOf(Events::class, $webhook->getEvents());
        $this->assertIsArray($webhook->getEvents()->all());
    }

    public function testItThrowsAnExceptionWithoutAWebhookIdInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Webhook. The property WebhookId should exist');

        $xml = '<Webhook>
                    <CallbackUrl>http://localhost/callbacks/1</CallbackUrl>
                    <WebhookSource>web</WebhookSource>
                    <Events>
                         <Event>onProductCreated</Event>
                    </Events>
               </Webhook>';

        WebhookFactory::make(new SimpleXMLElement($xml));
    }

    public function testItThrowsAnExceptionWithoutACallbackUrlInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Webhook. The property CallbackUrl should exist');

        $xml = '<Webhook>
                    <WebhookId>7dffaa4e-1713-42c2-84ba-1d2fbd4537ab</WebhookId>
                    <WebhookSource>web</WebhookSource>
                    <Events>
                         <Event>onProductCreated</Event>
                    </Events>
               </Webhook>';

        WebhookFactory::make(new SimpleXMLElement($xml));
    }

    public function testItThrowsAnExceptionWithoutAWebhookSourceInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Webhook. The property WebhookSource should exist');

        $xml = '<Webhook>
                    <WebhookId>7dffaa4e-1713-42c2-84ba-1d2fbd4537ab</WebhookId>
                    <CallbackUrl>http://localhost/callbacks/1</CallbackUrl>
                    <Events>
                         <Event>onProductCreated</Event>
                    </Events>
               </Webhook>';

        WebhookFactory::make(new SimpleXMLElement($xml));
    }

    public function testItThrowsAnExceptionWithoutAnEventsInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Webhook. The property Events should exist');

        $xml = '<Webhook>
                    <WebhookId>7dffaa4e-1713-42c2-84ba-1d2fbd4537ab</WebhookId>
                    <CallbackUrl>http://localhost/callbacks/1</CallbackUrl>
                    <WebhookSource>web</WebhookSource>
               </Webhook>';

        WebhookFactory::make(new SimpleXMLElement($xml));
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $webhookId = '7dffaa4e-1713-42c2-84ba-1d2fbd4537ab';
        $callbackUrl = 'http://localhost/callbacks/1';
        $webhookSource = 'api';
        $event1 = 'onProductCreated';
        $event2 = 'onOrderCreated';
        $event3 = 'onOrderItemsStatusChanged';

        $xml = sprintf(
            '<Webhook>
                    <WebhookId>%s</WebhookId>
                    <CallbackUrl>%s</CallbackUrl>
                    <WebhookSource>%s</WebhookSource>
                    <Events>
                        <Event>%s</Event>
                        <Event>%s</Event>
                        <Event>%s</Event>
                    </Events>        
                </Webhook>',
            $webhookId,
            $callbackUrl,
            $webhookSource,
            $event1,
            $event2,
            $event3
        );

        $simpleXml = simplexml_load_string($xml);

        $webhook = WebhookFactory::make($simpleXml);

        $expectedJson = sprintf(
            '{"webhookId":"%s","callbackUrl":"%s","webhookSource":"%s", "events":[{"alias":"%s", "name":null},{"alias":"%s", "name":null},{"alias":"%s", "name":null}]}',
            $webhookId,
            $callbackUrl,
            $webhookSource,
            $event1,
            $event2,
            $event3
        );

        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($webhook));
    }
}
