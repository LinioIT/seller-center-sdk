<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Exception\InvalidUrlException;
use Linio\SellerCenter\Factory\Xml\Webhook\EventsFactory;
use Linio\SellerCenter\Factory\Xml\Webhook\WebhooksFactory;
use Linio\SellerCenter\Model\Webhook\Event;
use Linio\SellerCenter\Model\Webhook\Webhook;
use Linio\SellerCenter\Transformer\Webhook\WebhookTransformer;

class WebhookManager extends BaseManager
{
    public function createWebhook(
        string $callbackUrl,
        bool $debug = true
    ): string {
        $action = 'CreateWebhook';

        $parameters = $this->makeParametersForAction($action);

        if (!filter_var($callbackUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException($callbackUrl);
        }

        $events = $this->getWebhookEntities($debug);

        $xml = WebhookTransformer::createWebhookAsXmlString($callbackUrl, $events);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'POST',
            $debug,
            $xml
        );

        return (string) $builtResponse->getBody()->Webhook->WebhookId;
    }

    public function deleteWebhook(
        string $webhookId,
        bool $debug = true
    ): void {
        $action = 'DeleteWebhook';

        $parameters = $this->makeParametersForAction($action);

        if (empty($webhookId)) {
            throw new EmptyArgumentException('WebhookId');
        }

        $xml = WebhookTransformer::deleteWebhookAsXmlString($webhookId);

        $this->executeAction(
            $action,
            $parameters,
            null,
            'POST',
            $debug,
            $xml
        );
    }

    /**
     * @return Webhook[]
     */
    protected function getWebhooks(
        Parameters $parameters,
        bool $debug = true
    ): array {
        $action = 'GetWebhooks';

        $parameters->set(['Action' => $action]);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        $webhooks = WebhooksFactory::make($builtResponse->getBody());

        return $webhooks->all();
    }

    /**
     * @return Webhook[]
     */
    public function getAllWebhooks(bool $debug = true): array
    {
        $parameters = clone $this->parameters;

        return $this->getWebhooks(
            $parameters,
            $debug
        );
    }

    /**
     * @param string[] $webhookIds
     *
     * @return Webhook[]
     */
    public function getWebhooksByIds(
        array $webhookIds,
        bool $debug = true
    ): array {
        $parameters = clone $this->parameters;

        if (empty($webhookIds)) {
            throw new EmptyArgumentException('WebhookIds');
        }

        $parameters->set(['WebhookIds' => Json::encode($webhookIds)]);

        return $this->getWebhooks(
            $parameters,
            $debug
        );
    }

    /**
     * @return Event[]
     */
    protected function getWebhookEntities(bool $debug = true): array
    {
        $action = 'GetWebhookEntities';

        $parameters = $this->makeParametersForAction($action);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        $events = EventsFactory::make($builtResponse->getBody());

        return array_values($events->all());
    }
}
