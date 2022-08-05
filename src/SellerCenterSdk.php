<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

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
use Linio\SellerCenter\Service\OrderManager;
use Linio\SellerCenter\Service\ProductManager;
use Linio\SellerCenter\Service\QualityControlManager;
use Linio\SellerCenter\Service\ShipmentManager;
use Linio\SellerCenter\Service\WebhookManager;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class SellerCenterSdk
{
    /**
     * @var BrandManager
     */
    protected $brands;

    /**
     * @var FeedManager
     */
    protected $feeds;

    /**
     * @var QualityControlManager
     */
    protected $qualityControl;

    /**
     * @var DocumentManager
     */
    protected $documents;

    /**
     * @var CategoryManager
     */
    protected $categories;

    /**
     * @var OrderManager
     */
    protected $orders;

    /**
     * @var GlobalOrderManager
     */
    protected $globalOrders;

    /**
     * @var WebhookManager
     */
    protected $webhooks;

    /**
     * @var Parameters
     */
    protected $parameters;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ProductManager
     */
    protected $products;

    /**
     * @var GlobalProductManager
     */
    protected $globalProducts;

    /**
     * @var ShipmentManager
     */
    protected $shipment;

    /**
     * @param \GuzzleHttp\ClientInterface|\Psr\Http\Client\ClientInterface|null $client
     */
    public function __construct(
        Configuration $configuration,
        $client = null,
        ?LoggerInterface $logger = null
    ) {
        $client = $client ? $client : HttpClientDiscovery::find();
        $this->setClient($client);
        $this->configuration = $configuration;
        $this->logger = $logger ?? new NullLogger();
        $this->parameters = Parameters::fromBasics($configuration->getUser(), $configuration->getVersion());
    }

    /**
     * @param \GuzzleHttp\ClientInterface|\Psr\Http\Client\ClientInterface $client
     */
    public function setClient($client): void
    {
        if (is_subclass_of($client, \GuzzleHttp\ClientInterface::class)) {
            $this->client = new GuzzleClientAdapter($client);

            return;
        }

        $this->client = new PsrClientAdapter($client);
    }

    public function brands(): BrandManager
    {
        if (empty($this->brands)) {
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
        if (empty($this->feeds)) {
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
        if (empty($this->documents)) {
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
        if (empty($this->categories)) {
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
        if (empty($this->products)) {
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
        if (empty($this->globalProducts)) {
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
        if (empty($this->orders)) {
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
        if (empty($this->globalOrders)) {
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
        if (empty($this->qualityControl)) {
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
        if (empty($this->webhooks)) {
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
        if (empty($this->shipment)) {
            $this->shipment = new ShipmentManager(
                $this->configuration,
                $this->client,
                $this->parameters,
                $this->logger
            );
        }

        return $this->shipment;
    }
}
