<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Seller;

use JsonSerializable;
use stdClass;

class Seller implements JsonSerializable
{
    /**
     * @var string|null
     */
    protected $shortCode;

    /**
     * @var string|null
     */
    protected $companyName;

    /**
     * @var string|null
     */
    protected $emailAddress;

    /**
     * @var string|null
     */
    protected $sellerName;

    /**
     * @var string|null
     */
    protected $apiKey;

    public function __construct(
        ?string $shortCode = null,
        ?string $companyName = null,
        ?string $emailAddress = null,
        ?string $sellerName = null,
        ?string $apiKey = null
    ) {
        $this->setShortCode($shortCode);
        $this->setCompanyName($companyName);
        $this->setEmailAddress($emailAddress);
        $this->setSellerName($sellerName);
        $this->setApiKey($apiKey);
    }

    public function getShortCode(): ?string
    {
        return $this->shortCode;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function getSellerName(): ?string
    {
        return $this->sellerName;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setShortCode(?string $shortCode): void
    {
        $this->shortCode = $shortCode;
    }

    public function setCompanyName(?string $companyName): void
    {
        $this->companyName = $companyName;
    }

    public function setEmailAddress(?string $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

    public function setSellerName(?string $sellerName): void
    {
        $this->sellerName = $sellerName;
    }

    public function setApiKey(?string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->shortCode = $this->shortCode;
        $serialized->companyName = $this->companyName;
        $serialized->emailAddress = $this->emailAddress;
        $serialized->sellerName = $this->sellerName;
        $serialized->apiKey = $this->apiKey;

        return $serialized;
    }
}
