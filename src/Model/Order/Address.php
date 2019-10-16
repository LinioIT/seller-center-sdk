<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Order;

use JsonSerializable;
use stdClass;

class Address implements JsonSerializable
{
    /**
     * @var string|null
     */
    protected $firstName;

    /**
     * @var string|null
     */
    protected $lastName;

    /**
     * @var int|null
     */
    protected $phone;

    /**
     * @var int|null
     */
    protected $phone2;

    /**
     * @var string|null
     */
    protected $address;

    /**
     * @var string|null
     */
    protected $customerEmail;

    /**
     * @var string|null
     */
    protected $city;

    /**
     * @var string|null
     */
    protected $ward;

    /**
     * @var string|null
     */
    protected $region;

    /**
     * @var string|null
     */
    protected $postCode;

    /**
     * @var string|null
     */
    protected $country;

    public function __construct(
        string $firstName,
        string $lastName,
        int $phone,
        int $phone2,
        string $address,
        string $customerEmail,
        string $city,
        string $ward,
        string $region,
        string $postCode,
        string $country
    ) {
        $this->firstName = !empty($firstName) ? $firstName : null;
        $this->lastName = !empty($lastName) ? $lastName : null;
        $this->phone = !empty($phone) ? $phone : null;
        $this->phone2 = !empty($phone2) ? $phone2 : null;
        $this->address = !empty($address) ? $address : null;
        $this->customerEmail = !empty($customerEmail) ? $customerEmail : null;
        $this->city = !empty($city) ? $city : null;
        $this->ward = !empty($ward) ? $ward : null;
        $this->region = !empty($region) ? $region : null;
        $this->postCode = !empty($postCode) ? $postCode : null;
        $this->country = !empty($country) ? $country : null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function getPhone2(): ?int
    {
        return $this->phone2;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getWard(): ?string
    {
        return $this->ward;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->firstName = $this->firstName;
        $serialized->lastName = $this->lastName;
        $serialized->phone = $this->phone;
        $serialized->phone2 = $this->phone2;
        $serialized->address = $this->address;
        $serialized->customerEmail = $this->customerEmail;
        $serialized->city = $this->city;
        $serialized->ward = $this->ward;
        $serialized->region = $this->region;
        $serialized->postCode = $this->postCode;
        $serialized->country = $this->country;

        return $serialized;
    }
}
