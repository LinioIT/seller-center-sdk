<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Shipment;

use JsonSerializable;
use stdClass;

class ShipmentProvider implements JsonSerializable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool|null
     */
    protected $default;

    /**
     * @var bool|null
     */
    protected $apiIntegration;

    /**
     * @var bool|null
     */
    protected $cod;

    /**
     * @var string|null
     */
    protected $trackingCodeValidationRegex;

    /**
     * @var string|null
     */
    protected $trackingCodeExample;

    /**
     * @var string|null
     */
    protected $trackingUrl;

    /**
     * @var string|null
     */
    protected $trackingCodeSetOnStep;

    /**
     * @var array|null
     */
    protected $enabledDeliveryOptions;

    public function __construct(
        string $name,
        ?bool $default = null,
        ?bool $apiIntegration = null,
        ?bool $cod = null,
        ?string $trackingCodeValidationRegex = null,
        ?string $trackingCodeExample = null,
        ?string $trackingUrl = null,
        ?string $trackingCodeSetOnStep = null,
        ?array $enabledDeliveryOptions = []
    ) {
        $this->name = $name;
        $this->default = $default;
        $this->apiIntegration = $apiIntegration;
        $this->cod = $cod;
        $this->trackingCodeValidationRegex = $trackingCodeValidationRegex;
        $this->trackingCodeExample = $trackingCodeExample;
        $this->trackingUrl = $trackingUrl;
        $this->trackingCodeSetOnStep = $trackingCodeSetOnStep;
        $this->enabledDeliveryOptions = $enabledDeliveryOptions;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDefault(): ?bool
    {
        return $this->default;
    }

    public function getApiIntegration(): ?bool
    {
        return $this->apiIntegration;
    }

    public function getCod(): ?bool
    {
        return $this->cod;
    }

    public function getTrackingCodeValidationRegex(): ?string
    {
        return $this->trackingCodeValidationRegex;
    }

    public function getTrackingCodeExample(): ?string
    {
        return $this->trackingCodeExample;
    }

    public function getTrackingUrl(): ?string
    {
        return $this->trackingUrl;
    }

    public function getTrackingCodeSetOnStep(): ?string
    {
        return $this->trackingCodeSetOnStep;
    }

    public function getEnabledDeliveryOptions(): ?array
    {
        return $this->enabledDeliveryOptions;
    }

    public function jsonSerialize()
    {
        $serialized = new stdClass();
        $serialized->name = $this->name;
        $serialized->default = $this->default;
        $serialized->apiIntegration = $this->apiIntegration;
        $serialized->cod = $this->cod;
        $serialized->trackingCodeValidationRegex = $this->trackingCodeValidationRegex;
        $serialized->trackingCodeExample = $this->trackingCodeExample;
        $serialized->trackingUrl = $this->trackingUrl;
        $serialized->trackingCodeSetOnStep = $this->trackingCodeSetOnStep;
        $serialized->enabledDeliveryOptions = $this->enabledDeliveryOptions;

        return $serialized;
    }
}
