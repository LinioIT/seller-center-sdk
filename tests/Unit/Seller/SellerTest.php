<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Seller;

use Linio\Component\Util\Json;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Seller\Seller;

class SellerTest extends LinioTestCase
{
    /**
     * @var string
     */
    private $shortCode = 'TEST123';

    /**
     * @var string
     */
    private $companyName = 'testCompanyName';

    /**
     * @var string
     */
    private $sellerName = 'testSeller';

    /**
     * @var string
     */
    private $emailAddress = 'testSeller@falabella.cl';

    /**
     * @var string
     */
    private $apiKey = 'testApiKey123';

    public function testItSetsSndGetValuesCorrectly(): void
    {
        // $body = $this->getSchema('Seller/GetSellerByUserSuccessResponse.xml');
        // $xml = simplexml_load_string($body);

        // $seller = SellerFactory::make($xml->Body);
        $seller = new Seller();

        $seller->setShortCode($this->shortCode);
        $seller->setCompanyName($this->companyName);
        $seller->setSellerName($this->sellerName);
        $seller->setEmailAddress($this->emailAddress);
        $seller->setApiKey($this->apiKey);

        $this->assertEquals($seller->getShortCode(), $this->shortCode);
        $this->assertEquals($seller->getCompanyName(), $this->companyName);
        $this->assertEquals($seller->getSellerName(), $this->sellerName);
        $this->assertEquals($seller->getEmailAddress(), $this->emailAddress);
        $this->assertEquals($seller->getApiKey(), $this->apiKey);
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $seller = new Seller(
            $this->shortCode,
            $this->companyName,
            $this->sellerName,
            $this->emailAddress,
            $this->apiKey
        );

        $expectedJson = Json::decode($this->getSchema('Seller/Seller.json'));

        $this->assertJsonStringEqualsJsonString(Json::encode($expectedJson), Json::encode($seller));
    }
}
