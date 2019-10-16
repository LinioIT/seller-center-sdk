<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Product;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidUrlException;
use Linio\SellerCenter\Factory\Xml\Product\ImagesFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Product\Image;

class ImageTest extends LinioTestCase
{
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->getFaker();
    }

    public function testItCreatesAImage(): void
    {
        $url = $this->faker->imageUrl($width = 640, $height = 480);
        $image = new Image($url);

        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals($url, $image->getUrl());
    }

    public function testItThrowsAnExceptionWhenTheUrlIsNotValid(): void
    {
        $invalidUrl = 'this-is-not-an-url';
        $this->expectException(InvalidUrlException::class);
        $this->expectExceptionMessage(sprintf('The url \'%s\' is not valid', $invalidUrl));

        new Image($invalidUrl);
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $url = $this->faker->imageUrl($width = 640, $height = 480);

        $simpleXml = simplexml_load_string(sprintf('<Images>
          <Image>%s</Image>
        </Images>', $url));

        $images = ImagesFactory::make($simpleXml);

        $expectedJson = sprintf('[{"url": "%s"}]', $url);

        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($images));
    }
}
