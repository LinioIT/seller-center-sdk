<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Exception\InvalidUrlException;
use Linio\SellerCenter\Factory\Xml\Webhook\EventsFactory;
use Linio\SellerCenter\Factory\Xml\Webhook\WebhooksFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Model\Webhook\Webhook;
use Linio\SellerCenter\Response\HandleResponse;
use Linio\SellerCenter\Transformer\Webhook\WebhookTransformer;
use Psr\Log\LoggerInterface;

class WebhookManager
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Parameters
     */
    protected $parameters;

    public function __construct(
        Configuration $configuration,
        ClientInterface $client,
        Parameters $parameters,
        LoggerInterface $logger
    ) {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->parameters = $parameters;
        $this->logger = $logger;
    }

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

        $requestId = uniqid((string) mt_rand());

        $request = new Request('POST', $this->configuration->getEndpoint(), [
            'Content-type' => 'text/xml; charset=UTF8',
            'Request-ID' => $requestId,
        ], $xml);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'url' => (string) $request->getUri(),
                'method' => $request->getMethod(),
                'body' => (string) $request->getBody(),
                'parameters' => $parameters->all(),
            ]
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_RESPONSE),
            [
                'statusCode' => $response->getStatusCode(),
                'body' => $body,
            ]
        );

        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_BUILT_RESPONSE),
            [
                'head' => $builtResponse->getHead()->asXML(),
                'body' => $builtResponse->getBody()->asXML(),
            ]
        );

        $webhookResponse = (string) $builtResponse->getBody()->Webhook->WebhookId;

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the webhook was created',
                $request->getHeaderLine('Request-ID'),
                $action
            )
        );

        return $webhookResponse;
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

        $requestId = uniqid((string) mt_rand());

        $request = new Request('POST', $this->configuration->getEndpoint(), [
            'Content-type' => 'text/xml; charset=UTF8',
            'Request-ID' => $requestId,
        ], $xml);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'url' => (string) $request->getUri(),
                'method' => $request->getMethod(),
                'body' => (string) $request->getBody(),
                'parameters' => $parameters->all(),
            ]
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_RESPONSE),
            [
                'statusCode' => $response->getStatusCode(),
                'body' => $body,
            ]
        );

        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_BUILT_RESPONSE),
            [
                'head' => $builtResponse->getHead()->asXML(),
                'body' => $builtResponse->getBody()->asXML(),
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

    protected function getWebhooks(Parameters $parameters): array
    {
        $action = 'GetWebhooks';

        $parameters->set(['Action' => $action]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $requestId = uniqid((string) mt_rand());

        $request = new Request('GET', $this->configuration->getEndpoint(), [
            'Request-ID' => $requestId,
        ]);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'url' => (string) $request->getUri(),
                'method' => $request->getMethod(),
                'body' => (string) $request->getBody(),
                'parameters' => $parameters->all(),
            ]
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_RESPONSE),
            [
                'body' => $body,
            ]
        );

        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_BUILT_RESPONSE),
            [
                'head' => $builtResponse->getHead()->asXML(),
                'body' => $builtResponse->getBody()->asXML(),
            ]
        );

        $webhooks = WebhooksFactory::make($builtResponse->getBody());

        $webhooksResponse = $webhooks->all();

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d webhooks was recovered',
                $request->getHeaderLine('Request-ID'),
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
        $action = 'GetWebhookEntities';

        $parameters = clone $this->parameters;
        $parameters->set(['Action' => $action]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $requestId = uniqid((string) mt_rand());

        $request = new Request('GET', $this->configuration->getEndpoint(), [
            'Request-ID' => $requestId,
        ]);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'url' => (string) $request->getUri(),
                'method' => $request->getMethod(),
                'body' => (string) $request->getBody(),
                'parameters' => $parameters->all(),
            ]
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_RESPONSE),
            [
                'body' => $body,
            ]
        );

        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_BUILT_RESPONSE),
            [
                'head' => $builtResponse->getHead()->asXML(),
                'body' => $builtResponse->getBody()->asXML(),
            ]
        );

        $events = EventsFactory::make($builtResponse->getBody());

        $eventsResponse = array_values($events->all());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d events was recovered',
                $request->getHeaderLine('Request-ID'),
                $action,
                count($events->all())
            )
        );

        return $eventsResponse;
    }
}
