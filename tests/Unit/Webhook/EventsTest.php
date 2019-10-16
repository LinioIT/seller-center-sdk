<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Webhook;

use Linio\SellerCenter\Factory\Xml\Webhook\EventsFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Webhook\Event;
use Linio\SellerCenter\Model\Webhook\Events;

class EventsTest extends LinioTestCase
{
    public function testItReturnsAnEmptyArray(): void
    {
        $events = new Events();
        $this->assertIsArray($events->all());
    }

    public function testItReturnsAnArrayWithOneEvent(): void
    {
        $eventAlias = 'onProductCreated';
        $event = new Event($eventAlias, null);
        $events = new Events();

        $events->add($event);
        $eventsArray = $events->all();

        $this->assertCount(1, $eventsArray);
        $this->assertInstanceOf(Event::class, current($eventsArray));
        $this->assertEquals($eventAlias, current($eventsArray)->getAlias());
    }

    public function testItFindsAndReturnTheEventByAlias(): void
    {
        $response = $this->getResponseMock();
        $events = EventsFactory::make($response->Body);

        $eventAlias = 'onMetricsUpdated';

        $event = $events->findByAlias($eventAlias);

        $this->assertInstanceOf(Events::class, $events);
        $this->assertContainsOnlyInstancesOf(Event::class, $events->all());
        $this->assertInstanceOf(Event::class, $event);
        $this->assertEquals($eventAlias, $event->getAlias());
    }

    public function testItReturnsAnEmptyValueWhenNoEventWasFound(): void
    {
        $response = $this->getResponseMock();
        $events = EventsFactory::make($response->Body);

        $event = $events->findByAlias('non-existent-event-alias');

        $this->assertNull($event);
    }

    public function testItIgnoresTheEmptyEvents(): void
    {
        $event1 = 'onProductCreated';
        $event2 = 'onOrderCreated';
        $event3 = 'onOrderItemsStatusChanged';

        $xml = sprintf('<Events>
                  <Event>%s</Event>
                  <Event>%s</Event>
                  <Event></Event>
                  <Event>%s</Event>
                </Events>', $event1, $event2, $event3);

        $sxml = simplexml_load_string($xml);

        $events = EventsFactory::makeFromWebhook($sxml);

        $this->assertInstanceOf(Events::class, $events);
        $this->assertCount(3, $events->all());

        $this->assertEquals($event1, $events->findByAlias($event1)->getAlias());
        $this->assertEquals($event2, $events->findByAlias($event2)->getAlias());
        $this->assertEquals($event3, $events->findByAlias($event3)->getAlias());
    }

    public function getResponseMock($xml = null)
    {
        if (empty($xml)) {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                         <Head>
                              <RequestId/>
                              <RequestAction>GetWebhookEntities</RequestAction>
                              <ResponseType>Entities</ResponseType>
                              <Timestamp>2016-06-02T11:07:53+0200</Timestamp>
                         </Head>
                         <Body>
                              <Entities>
                                   <Entity>
                                        <Name>Feed</Name>
                                        <Events>
                                             <Event>
                                                  <EventName>Completed</EventName>
                                                  <EventAlias>onFeedCompleted</EventAlias>
                                             </Event>
                                             <Event>
                                                  <EventName>Created</EventName>
                                                  <EventAlias>onFeedCreated</EventAlias>
                                             </Event>
                                        </Events>
                                   </Entity>
                                   <Entity>
                                        <Name>Metrics</Name>
                                        <Events>
                                             <Event>
                                                  <EventName>Updated</EventName>
                                                  <EventAlias>onMetricsUpdated</EventAlias>
                                             </Event>
                                        </Events>
                                   </Entity>
                                   <Entity>
                                        <Name>Order</Name>
                                        <Events>
                                             <Event>
                                                  <EventName>Created</EventName>
                                                  <EventAlias>onOrderCreated</EventAlias>
                                             </Event>
                                             <Event>
                                                  <EventName>StatusChanged</EventName>
                                                  <EventAlias>onOrderItemsStatusChanged</EventAlias>
                                             </Event>
                                        </Events>
                                   </Entity>
                                   <Entity>
                                        <Name>Product</Name>
                                        <Events>
                                             <Event>
                                                  <EventName>Created</EventName>
                                                  <EventAlias>onProductCreated</EventAlias>
                                             </Event>
                                             <Event>
                                                  <EventName>QcStatusChanged</EventName>
                                                  <EventAlias>onProductQcStatusChanged</EventAlias>
                                             </Event>
                                             <Event>
                                                  <EventName>Updated</EventName>
                                                  <EventAlias>onProductUpdated</EventAlias>
                                             </Event>
                                        </Events>
                                   </Entity>
                                   <Entity>
                                        <Name>Statistics</Name>
                                        <Events>
                                             <Event>
                                                  <EventName>Updated</EventName>
                                                  <EventAlias>onStatisticsUpdated</EventAlias>
                                             </Event>
                                        </Events>
                                   </Entity>
                              </Entities>
                         </Body>
                    </SuccessResponse>';
        }

        return simplexml_load_string($xml);
    }
}
