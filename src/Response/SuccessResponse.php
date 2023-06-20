<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Response;

use Linio\SellerCenter\Contract\SuccessResponse as ContractSuccessResponse;
use SimpleXMLElement;

class SuccessResponse
{
    /**
     * @var SimpleXMLElement
     */
    protected $body;

    /**
     * @var SimpleXMLElement
     */
    protected $head;

    /**
     * @var SimpleXMLElement|null
     */
    protected $xml;

    public static function fromXml(SimpleXMLElement $xml): SuccessResponse
    {
        return new self($xml->Head, $xml->Body, $xml);
    }

    public function __construct(SimpleXMLElement $head, SimpleXMLElement $body, ?SimpleXMLElement $xml = null)
    {
        $this->head = $head;
        $this->body = $body;
        $this->xml = $xml;
    }

    public function getBody(): SimpleXMLElement
    {
        return $this->body;
    }

    public function getHead(): SimpleXMLElement
    {
        return $this->head;
    }

    public function getBaseData(): string
    {
        return $this->getHead()->asXML() ?: '';
    }

    public function getDetailData(): string
    {
        return $this->getBody()->asXML() ?: '';
    }
}
