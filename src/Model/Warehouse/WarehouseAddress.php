<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Warehouse;

use JsonSerializable;
use stdClass;

class WarehouseAddress implements JsonSerializable
{
    /**
     * @var string|null
     */
    protected $addressLine1;

    /**
     * @var string|null
     */
    protected $addressLine2;

    /**
     * @var string|null
     */
    protected $addressLine3;

    /**
     * @var string|null
     */
    protected $municipal;

    /**
     * @var string|null
     */
    protected $postCode;

    /**
     * @var string|null
     */
    protected $email;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $contactAddress2Code;

    /**
     * @var Contacts|null
     */
    protected $contacts;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $state;

    public function __construct(
        ?string $addressLine1,
        ?string $addressLine2,
        ?string $addressLine3,
        string $municipal,
        string $city,
        string $state,
        ?string $postCode,
        string $countryCode,
        string $country,
        ?string $email,
        ?string $name,
        ?string $contactAddress2Code,
        ?Contacts $contacts
    ) {
        $this->addressLine1 = $addressLine1;
        $this->addressLine2 = $addressLine2;
        $this->addressLine3 = $addressLine3;
        $this->municipal = $municipal;
        $this->city = $city;
        $this->state = $state;
        $this->postCode = $postCode;
        $this->countryCode = $countryCode;
        $this->email = $email;
        $this->name = $name;
        $this->contactAddress2Code = $contactAddress2Code;
        $this->contacts = $contacts;
        $this->country = $country;
    }

    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    public function getAddressLine3(): ?string
    {
        return $this->addressLine3;
    }

    public function getMunicipal(): string
    {
        return $this->municipal;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getContacts(): ?Contacts
    {
        return $this->contacts;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getContactAddress2Code(): ?string
    {
        return $this->contactAddress2Code;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->addressLine1 = $this->addressLine1;
        $serialized->addressLine2 = $this->addressLine2;
        $serialized->addressLine3 = $this->addressLine3;
        $serialized->municipal = $this->municipal;
        $serialized->city = $this->city;
        $serialized->state = $this->state;
        $serialized->postCode = $this->postCode;
        $serialized->countryCode = $this->countryCode;
        $serialized->email = $this->email;
        $serialized->name = $this->name;
        $serialized->contacts = $this->contacts;
        $serialized->contactAddress2Code = $this->contactAddress2Code;
        $serialized->country = $this->country;

        return $serialized;
    }
}
