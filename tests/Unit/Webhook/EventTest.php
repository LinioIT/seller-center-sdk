<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Webhook;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Webhook\EventFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Webhook\Event;
use SimpleXMLElement;

class EventTest extends LinioTestCase
{
    public function testItCreatesAEvent(): void
    {
        $eventAlias = 'onOrderCreated';
        $eventName = 'Created';

        $event = new Event($eventAlias, $eventName);

        $this->assertInstanceOf(Event::class, $event);
        $this->assertEquals($eventAlias, $event->getAlias());
        $this->assertEquals($eventName, $event->getName());
    }

    public function testItReturnsAEventFromAnXml(): void
    {
        $eventName = 'created';
        $eventAlias = 'onOrderCreated';

        $xml = sprintf('<Event>
                                  <EventName>%s</EventName>
                                  <EventAlias>%s</EventAlias>
                             </Event>', $eventName, $eventAlias);

        $sxml = simplexml_load_string($xml);

        $event = EventFactory::make($sxml);

        $this->assertInstanceOf(Event::class, $event);
        $this->assertEquals($eventName, $event->getName());
        $this->assertEquals($eventAlias, $event->getAlias());
    }

    public function testItThrowsAExceptionWithoutAEventAliasInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Event. The property EventAlias should exist');

        $xml = '<Event>
                  <EventName>created</EventName>
             </Event>';

        EventFactory::make(new SimpleXMLElement($xml));
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $eventName = 'created';
        $eventAlias = 'onOrderCreated';

        $xml = sprintf('<Event>
                                  <EventName>%s</EventName>
                                  <EventAlias>%s</EventAlias>
                             </Event>', $eventName, $eventAlias);

        $sxml = simplexml_load_string($xml);

        $event = EventFactory::make($sxml);

        $expectedJson = sprintf(
            '{"alias":"%s", "name":"%s"}',
            $eventAlias,
            $eventName
        );

        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($event));
    }
}
