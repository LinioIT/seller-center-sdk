<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Exception\InvalidUrlException;
use Linio\SellerCenter\Factory\RequestFactory;
use Linio\SellerCenter\Factory\Xml\Webhook\EventsFactory;
use Linio\SellerCenter\Factory\Xml\Webhook\WebhooksFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Model\Webhook\Event;
use Linio\SellerCenter\Model\Webhook\Webhook;
use Linio\SellerCenter\Response\HandleResponse;
use Linio\SellerCenter\Transformer\Webhook\WebhookTransformer;

class WebhookManager extends BaseManager
{
    public function createWebhook(string $callbackUrl): string
    {
        $action = 'CreateWebhook';

        $parameters = clone $this->parameters;

        if (!filter_var($callbackUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException($callbackUrl);
        }

        $parameters->set(['Action' => $action]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $events = $this->getWebhookEntities();

        $xml = WebhookTransformer::createWebhookAsXmlString($callbackUrl, $events);

        $requestHeaders = $this->generateRequestHeaders(['Content-type' => 'text/xml; charset=UTF8']);
        $requestId = $requestHeaders[self::REQUEST_ID_HEADER];

        $request = RequestFactory::make(
            'POST',
            $this->configuration->getEndpoint(),
            $requestHeaders,
            $xml
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'request' => [
                    'url' => (string) $request->getUri(),
                    'method' => $request->getMethod(),
                    'body' => (string) $request->getBody(),
                    'parameters' => $parameters->all(),
                ],
                'response' => [
                    'head' => $builtResponse->getHead()->asXML(),
                    'body' => $builtResponse->getBody()->asXML(),
                    'statusCode' => $response->getStatusCode(),
                ],
            ]
        );

        return (string) $builtResponse->getBody()->Webhook->WebhookId;
    }

    public function deleteWebhook(string $webhookId): void
    {
        $action = 'DeleteWebhook';

        $parameters = clone $this->parameters;

        if (empty($webhookId)) {
            throw new EmptyArgumentException('WebhookId');
        }

        $parameters->set(['Action' => $action]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $xml = WebhookTransformer::deleteWebhookAsXmlString($webhookId);

        $requestHeaders = $this->generateRequestHeaders(['Content-type' => 'text/xml; charset=UTF8']);
        $requestId = $requestHeaders[self::REQUEST_ID_HEADER];

        $request = RequestFactory::make(
            'POST',
            $this->configuration->getEndpoint(),
            $requestHeaders,
            $xml
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();
        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'request' => [
                    'url' => (string) $request->getUri(),
                    'method' => $request->getMethod(),
                    'body' => (string) $request->getBody(),
                    'parameters' => $parameters->all(),
                ],
                'response' => [
                    'head' => $builtResponse->getHead()->asXML(),
                    'body' => $builtResponse->getBody()->asXML(),
                    'statusCode' => $response->getStatusCode(),
                ],
            ]
        );

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the webhook was deleted',
                $request->getHeaderLine('Request-ID'),
                $action
            )
        );
    }

    /**
     * @return Webhook[]
     */
    protected function getWebhooks(Parameters $parameters): array
    {
        $action = 'GetWebhooks';

        $parameters->set(['Action' => $action]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $requestHeaders = $this->generateRequestHeaders();
        $requestId = $requestHeaders[self::REQUEST_ID_HEADER];

        $request = RequestFactory::make(
            'GET',
            $this->configuration->getEndpoint(),
            $requestHeaders
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'request' => [
                    'url' => (string) $request->getUri(),
                    'method' => $request->getMethod(),
                    'body' => (string) $request->getBody(),
                    'parameters' => $parameters->all(),
                ],
                'response' => [
                    'head' => $builtResponse->getHead()->asXML(),
                    'body' => $builtResponse->getBody()->asXML(),
                ],
            ]
        );

        $webhooks = WebhooksFactory::make($builtResponse->getBody());

        return $webhooks->all();
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
     * @param string[] $webhookIds
     *
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

    /**
     * @return Event[]
     */
    protected function getWebhookEntities(): array
    {
        $action = 'GetWebhookEntities';

        $parameters = clone $this->parameters;
        $parameters->set(['Action' => $action]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $requestHeaders = $this->generateRequestHeaders();
        $requestId = $requestHeaders[self::REQUEST_ID_HEADER];

        $request = RequestFactory::make(
            'GET',
            $this->configuration->getEndpoint(),
            $requestHeaders
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'request' => [
                    'url' => (string) $request->getUri(),
                    'method' => $request->getMethod(),
                    'body' => (string) $request->getBody(),
                    'parameters' => $parameters->all(),
                ],
                'response' => [
                    'head' => $builtResponse->getHead()->asXML(),
                    'body' => $builtResponse->getBody()->asXML(),
                ],
            ]
        );

        $events = EventsFactory::make($builtResponse->getBody());

        return array_values($events->all());
    }
}
