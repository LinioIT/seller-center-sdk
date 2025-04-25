<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Http\Discovery\HttpClientDiscovery;
use Linio\SellerCenter\Adapter\Client\GuzzleClientAdapter;
use Linio\SellerCenter\Adapter\Client\PsrClientAdapter;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Contract\ClientInterface;
use Linio\SellerCenter\Service\BrandManager;
use Linio\SellerCenter\Service\CategoryManager;
use Linio\SellerCenter\Service\Contract\ProductManagerInterface;
use Linio\SellerCenter\Service\DocumentManager;
use Linio\SellerCenter\Service\FeedManager;
use Linio\SellerCenter\Service\GlobalOrderManager;
use Linio\SellerCenter\Service\GlobalProductManager;
use Linio\SellerCenter\Service\GlobalSellerManager;
use Linio\SellerCenter\Service\OrderManager;
use Linio\SellerCenter\Service\ProductManager;
use Linio\SellerCenter\Service\QualityControlManager;
use Linio\SellerCenter\Service\SellerManager;
use Linio\SellerCenter\Service\ShipmentManager;
use Linio\SellerCenter\Service\WebhookManager;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class SellerCenterSdk
{
    protected ?BrandManager $brands;
    protected ?FeedManager $feeds;
    protected ?QualityControlManager $qualityControl;
    protected ?DocumentManager $documents;
    protected ?CategoryManager $categories;
    protected ?OrderManager $orders;
    protected ?GlobalOrderManager $globalOrders;
    protected ?WebhookManager $webhooks;
    protected ?Parameters $parameters;
    protected Configuration $configuration;
    protected ClientInterface $client;
    protected LoggerInterface $logger;
    protected ?ProductManager $products;
    protected ?GlobalProductManager $globalProducts;
    protected ?ShipmentManager $shipment;
    protected ?SellerManager $seller;
    protected ?GlobalSellerManager $globalSeller;

    /**
     * @param GuzzleClientInterface|PsrClientInterface|null $client
     */
    public function __construct(
        Configuration $configuration,
        $client = null,
        ?LoggerInterface $logger = null,
    ) {
        $client = $client ? $client : HttpClientDiscovery::find();
        $this->setClient($client);
        $this->configuration = $configuration;
        $this->logger = $logger ?? new NullLogger();
        $this->parameters = Parameters::fromConfiguration($configuration);
    }

    /**
     * @param GuzzleClientInterface|PsrClientInterface $client
     */
    public function setClient($client): void
    {
        if ($client instanceof GuzzleClientInterface) {
            $this->client = new GuzzleClientAdapter($client);

            return;
        }

        $this->client = new PsrClientAdapter($client);
    }

    public function brands(): BrandManager
    {
        if (!isset($this->brands)) {
            $this->brands = new BrandManager(
                $this->configuration,
                $this->client,
                $this->parameters,
                $this->logger
            );
        }

        return $this->brands;
    }

    public function feeds(): FeedManager
    {
        if (!isset($this->feeds)) {
            $this->feeds = new FeedManager(
                $this->configuration,
                $this->client,
                $this->parameters,
                $this->logger
            );
        }

        return $this->feeds;
    }

    public function documents(): DocumentManager
    {
        if (!isset($this->documents)) {
            $this->documents = new DocumentManager(
                $this->configuration,
                $this->client,
                $this->parameters,
                $this->logger
            );
        }

        return $this->documents;
    }

    public function categories(): CategoryManager
    {
        if (!isset($this->categories)) {
            $this->categories = new CategoryManager(
                $this->configuration,
                $this->client,
                $this->parameters,
                $this->logger
            );
        }

        return $this->categories;
    }

    public function products(): ProductManagerInterface
    {
        if (!isset($this->products)) {
            $this->products = new ProductManager(
                $this->configuration,
                $this->client,
                $this->parameters,
                $this->logger
            );
        }

        return $this->products;
    }

    public function globalProducts(): ProductManagerInterface
    {
        if (!isset($this->globalProducts)) {
            $this->globalProducts = new GlobalProductManager(
                $this->configuration,
                $this->client,
                $this->parameters,
                $this->logger
            );
        }

        return $this->globalProducts;
    }

    public function orders(): OrderManager
    {
        if (!isset($this->orders)) {
            $this->orders = new OrderManager(
                $this->configuration,
                $this->client,
                $this->parameters,
                $this->logger
            );
        }

        return $this->orders;
    }

    public function globalOrders(): GlobalOrderManager
    {
        if (!isset($this->globalOrders)) {
            $this->globalOrders = new GlobalOrderManager(
                $this->configuration,
                $this->client,
                $this->parameters,
                $this->logger
            );
        }

        return $this->globalOrders;
    }

    public function qualityControl(): QualityControlManager
    {
        if (!isset($this->qualityControl)) {
            $this->qualityControl = new QualityControlManager(
                $this->configuration,
                $this->client,
                $this->parameters,
                $this->logger
            );
        }

        return $this->qualityControl;
    }

    public function webhooks(): WebhookManager
    {
        if (!isset($this->webhooks)) {
            $this->webhooks = new WebhookManager(
                $this->configuration,
                $this->client,
                $this->parameters,
                $this->logger
            );
        }

        return $this->webhooks;
    }

    public function shipment(): ShipmentManager
    {
        if (!isset($this->shipment)) {
            $this->shipment = new ShipmentManager(
                $this->configuration,
                $this->client,
                $this->parameters,
                $this->logger
            );
        }

        return $this->shipment;
    }

    public function seller(): SellerManager
    {
        if (!isset($this->seller)) {
            $this->seller = new SellerManager(
                $this->configuration,
                $this->client,
                $this->parameters,
                $this->logger
            );
        }

        return $this->seller;
    }

    public function globalSeller(): GlobalSellerManager
    {
        if (!isset($this->globalSeller)) {
            $this->globalSeller = new GlobalSellerManager(
                $this->configuration,
                $this->client,
                $this->parameters,
                $this->logger
            );
        }

        return $this->globalSeller;
    }
}
