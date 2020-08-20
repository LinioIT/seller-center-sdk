<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Exception\InvalidUrlException;
use Linio\SellerCenter\Factory\Xml\Webhook\EventsFactory;
use Linio\SellerCenter\Factory\Xml\Webhook\WebhooksFactory;
use Linio\SellerCenter\Model\Webhook\Webhook;
use Linio\SellerCenter\Transformer\Webhook\WebhookTransformer;

class WebhookManager extends BaseManager
{
    private const CREATE_WEBHOOK_ACTION = 'CreateWebhook';
    private const DELETE_WEBHOOK_ACTION = 'DeleteWebhook';
    private const GET_WEBHOOKS_ACTION = 'GetWebhooks';
    private const GET_WEBHOOK_ENTITIES_ACTION = 'GetWebhookEntities';

    public function createWebhook(string $callbackUrl): string
    {
        $action = self::CREATE_WEBHOOK_ACTION;

        if (!filter_var($callbackUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException($callbackUrl);
        }

        $events = $this->getWebhookEntities();

        $xml = WebhookTransformer::createWebhookAsXmlString($callbackUrl, $events);

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId, null, 'POST', $xml);

        $webhookResponse = (string) $builtResponse->getBody()->Webhook->WebhookId;

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the webhook was created',
                $requestId,
                $action
            )
        );

        return $webhookResponse;
    }

    public function deleteWebhook(string $webhookId): void
    {
        $action = self::DELETE_WEBHOOK_ACTION;

        if (empty($webhookId)) {
            throw new EmptyArgumentException('WebhookId');
        }

        $xml = WebhookTransformer::deleteWebhookAsXmlString($webhookId);

        $requestId = $this->generateRequestId();

        $this->executeAction($action, $requestId, null, 'POST', $xml);

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the webhook was deleted',
                $requestId,
                $action
            )
        );
    }

    protected function getWebhooks(Parameters $parameters): array
    {
        $action = self::GET_WEBHOOKS_ACTION;

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId, $parameters);

        $webhooks = WebhooksFactory::make($builtResponse->getBody());

        $webhooksResponse = $webhooks->all();

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d webhooks was recovered',
                $requestId,
                $action,
                count($webhooks->all())
            )
        );

        return $webhooksResponse;
    }

    /**
     * @return Webhook[]
     */
    public function getAllWebhooks(): array
    {
        $parameters = clone $this->parameters;

        return $this->getWebhooks($parameters);
    }

    /**
     * @return Webhook[]
     */
    public function getWebhooksByIds(array $webhookIds): array
    {
        $parameters = clone $this->parameters;

        if (empty($webhookIds)) {
            throw new EmptyArgumentException('WebhookIds');
        }

        $parameters->set(['WebhookIds' => Json::encode($webhookIds)]);

        return $this->getWebhooks($parameters);
    }

    protected function getWebhookEntities(): array
    {
        $action = self::GET_WEBHOOK_ENTITIES_ACTION;

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId);

        $events = EventsFactory::make($builtResponse->getBody());

        $eventsResponse = array_values($events->all());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d events was recovered',
                $requestId,
                $action,
                count($events->all())
            )
        );

        return $eventsResponse;
    }
}
