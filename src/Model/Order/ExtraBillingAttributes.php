<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Order;

class ExtraBillingAttributes implements \JsonSerializable
{
    /**
     * @var string|null
     */
    protected $legalId;

    /**
     * @var string|null
     */
    protected $fiscalPerson;

    /**
     * @var string|null
     */
    protected $documentType;

    /**
     * @var string|null
     */
    protected $receiverRegion;

    /**
     * @var string|null
     */
    protected $receiverAddress;

    /**
     * @var string|null
     */
    protected $receiverPostcode;

    /**
     * @var string|null
     */
    protected $receiverLegalName;

    /**
     * @var string|null
     */
    protected $receiverMunicipality;

    /**
     * @var string|null
     */
    protected $receiverTypeRegimen;

    /**
     * @var string|null
     */
    protected $customerVerifierDigit;

    /**
     * @var string|null
     */
    protected $receiverLocality;

    /**
     * @var string|null
     */
    protected $receiverEmail;

    /**
     * @var string|null
     */
    protected $receiverPhonenumber;

    public function __construct(
        ?string $legalId,
        ?string $fiscalPerson,
        ?string $documentType,
        ?string $receiverRegion,
        ?string $receiverAddress,
        ?string $receiverPostcode,
        ?string $receiverLegalName,
        ?string $receiverMunicipality,
        ?string $receiverTypeRegimen,
        ?string $customerVerifierDigit,
        ?string $receiverLocality,
        ?string $receiverEmail,
        ?string $receiverPhonenumber,
    ) {
        $this->legalId = !empty($legalId) ? $legalId : null;
        $this->fiscalPerson = !empty($fiscalPerson) ? $fiscalPerson : null;
        $this->documentType = !empty($documentType) ? $documentType : null;
        $this->receiverRegion = !empty($receiverRegion) ? $receiverRegion : null;
        $this->receiverAddress = !empty($receiverAddress) ? $receiverAddress : null;
        $this->receiverPostcode = !empty($receiverPostcode) ? $receiverPostcode : null;
        $this->receiverLegalName = !empty($receiverLegalName) ? $receiverLegalName : null;
        $this->receiverMunicipality = !empty($receiverMunicipality) ? $receiverMunicipality : null;
        $this->receiverTypeRegimen = !empty($receiverTypeRegimen) ? $receiverTypeRegimen : null;
        $this->customerVerifierDigit = !empty($customerVerifierDigit) ? $customerVerifierDigit : null;
        $this->receiverLocality = !empty($receiverLocality) ? $receiverLocality : null;
        $this->receiverEmail = !empty($receiverEmail) ? $receiverEmail : null;
        $this->receiverPhonenumber = !empty($receiverPhonenumber) ? $receiverPhonenumber : null;
    }

    public function getLegalId(): ?string
    {
        return $this->legalId;
    }

    public function getFiscalPerson(): ?string
    {
        return $this->fiscalPerson;
    }

    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }

    public function getReceiverRegion(): ?string
    {
        return $this->receiverRegion;
    }

    public function getReceiverAddress(): ?string
    {
        return $this->receiverAddress;
    }

    public function getReceiverPostCode(): ?string
    {
        return $this->receiverPostcode;
    }

    public function getReceiverLegalName(): ?string
    {
        return $this->receiverLegalName;
    }

    public function getReceiverMunicipality(): ?string
    {
        return $this->receiverMunicipality;
    }

    public function getReceiverTypeRegimen(): ?string
    {
        return $this->receiverTypeRegimen;
    }

    public function getReceiverEmail(): ?string
    {
        return $this->receiverEmail;
    }

    public function getCustomerVerifierDigit(): ?string
    {
        return $this->customerVerifierDigit;
    }

    public function getReceiverLocality(): ?string
    {
        return $this->receiverLocality;
    }

    public function getReceiverPhoneNumber(): ?string
    {
        return $this->receiverPhonenumber;
    }

    public function jsonSerialize(): \stdClass
    {
        $serialized = new \stdClass();
        $serialized->legalId = $this->legalId;
        $serialized->fiscalPerson = $this->fiscalPerson;
        $serialized->documentType = $this->documentType;
        $serialized->receiverRegion = $this->receiverRegion;
        $serialized->receiverAddress = $this->receiverAddress;
        $serialized->receiverPostcode = $this->receiverPostcode;
        $serialized->receiverLegalName = $this->receiverLegalName;
        $serialized->receiverMunicipality = $this->receiverMunicipality;
        $serialized->receiverTypeRegimen = $this->receiverTypeRegimen;
        $serialized->customerVerifierDigit = $this->customerVerifierDigit;
        $serialized->receiverLocality = $this->receiverLocality;
        $serialized->receiverEmail = $this->receiverEmail;
        $serialized->receiverPhonenumber = $this->receiverPhonenumber;

        return $serialized;
    }
}
