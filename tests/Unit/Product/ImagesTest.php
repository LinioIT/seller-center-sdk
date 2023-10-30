<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Product;

use Linio\SellerCenter\Factory\Xml\Product\ImagesFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Product\Image;
use Linio\SellerCenter\Model\Product\Images;
use stdClass;

class ImagesTest extends LinioTestCase
{
    private const IMAGES = 20;

    public function testItReturnsAnEmptyArray(): void
    {
        $images = new Images();
        $this->assertIsArray($images->all());
    }

    public function testItReturnsAnArrayWithOneImage(): void
    {
        $url = $this->getFaker()->imageUrl($width = 640, $height = 480);
        $image = new Image($url);
        $images = new Images();

        $images->add($image);
        $imagesArray = $images->all();

        $this->assertCount(1, $imagesArray);
        $this->assertInstanceOf(Image::class, current($imagesArray));
        $this->assertEquals($url, current($imagesArray)->getUrl());
    }

    /**
     * @dataProvider generatedImages
     */
    public function testItReturnAnArrayWithTheMaxAmountOfImagesByAddingIt(array $imageStack): void
    {
        $availableImages = array_slice($imageStack, 0, Images::MAX_IMAGES_ALLOWED);
        $images = new Images();

        foreach ($availableImages as $image) {
            $images->add($image);
        }

        $this->assertCount(8, $images->all());
    }

    /**
     * @dataProvider generatedImages
     */
    public function testItReturnAnArrayWithTheMaxAmountOfImagesByAddingMultipleAtOnce(array $imageStack): void
    {
        $availableImages = array_slice($imageStack, 0, Images::MAX_IMAGES_ALLOWED);
        $images = new Images();

        $images->addMany($availableImages);

        $this->assertCount(8, $images->all());
    }

    /**
     * @dataProvider generatedImages
     */
    public function testItReturnAnArrayWithTheMaxAmountOfImagesByAddingMultipleAtOnceButExceedsTheLimit(array $imageStack): void
    {
        $availableImages = array_slice($imageStack, 0, Images::MAX_IMAGES_ALLOWED + 5);
        $images = new Images();

        $images->addMany($availableImages);

        $this->assertCount(8, $images->all());
    }

    /**
     * @dataProvider generatedImages
     */
    public function testItSkipsAddingManyWithAFullyStackOfImages(array $imageStack): void
    {
        $availableImages = array_slice($imageStack, 0, Images::MAX_IMAGES_ALLOWED);
        $overflow = array_slice($imageStack, 8, Images::MAX_IMAGES_ALLOWED);

        $images = new Images();
        $images->addMany($availableImages);
        $images->addMany($overflow);

        $this->assertCount(Images::MAX_IMAGES_ALLOWED, $images->all());
    }

    public function testItIgnoresTheEmptyImages(): void
    {
        $image1 = 'http://static.somesite.com/p/image1.jpg';
        $image2 = 'http://static.somesite.com/p/image2.jpg';
        $image3 = 'http://static.somesite.com/p/image3.jpg';

        $xml = sprintf('<Images>
                  <Image>%s</Image>
                  <Image>%s</Image>
                  <Image></Image>
                  <Image>%s</Image>
                </Images>', $image1, $image2, $image3);

        $sxml = simplexml_load_string($xml);

        $images = ImagesFactory::make($sxml);

        $this->assertInstanceOf(Images::class, $images);
        $this->assertCount(3, $images->all());

        $this->assertEquals($image1, $images->all()[0]->getUrl());
        $this->assertEquals($image2, $images->all()[1]->getUrl());
    }

    /**
     * @dataProvider generatedImages
     */
    public function testItReturnAnArrayWithTheAddedImagesWithDifferentTypes(array $imageStack): void
    {
        $oneImage = current(array_slice($imageStack, 0, 1));
        $multipleImages = array_slice($imageStack, 1, 3);
        $images = new Images();

        $images->add($oneImage);
        $images->addMany($multipleImages);

        $this->assertCount(4, $images->all());
    }

    /**
     * @dataProvider generatedImages
     */
    public function testItSkipObjectsThatAreNotImages(array $imageStack): void
    {
        $multipleImages = array_slice($imageStack, 0, 3);
        $object = new stdClass();

        $multipleImages[] = $object;

        $images = new Images();

        $images->addMany($multipleImages);

        $this->assertCount(3, $images->all());
    }

    /**
     * @dataProvider generatedImagesUrls
     */
    public function testItAddsImagesFromUrlStrings(array $imageUrls): void
    {
        $limitedImages = array_slice($imageUrls, 0, 3);

        $images = new Images();
        $images->addManyFromUrls($limitedImages);

        $this->assertCount(3, $images->all());
    }

    /**
     * @dataProvider generatedImagesUrls
     */
    public function testItLimitsTheQuantityOfImages(array $imageUrls): void
    {
        $images = new Images();
        $images->addManyFromUrls($imageUrls);

        $this->assertCount(Images::MAX_IMAGES_ALLOWED, $images->all());
    }

    public function testItIgnoresValuesThatAreNotValidUrl(): void
    {
        $imageUrl = $this->getFaker()->imageUrl();
        $imageUrls = [
            'imageUrl',
            2,
            0,
            $imageUrl,
        ];

        $images = new Images();
        $images->addManyFromUrls($imageUrls);

        $this->assertCount(1, $images->all());
        $this->assertSame($imageUrl, $images->all()[0]->getUrl());
    }

    public function generatedImages(): array
    {
        $imagesMock = [];

        for ($i = 0; $i < self::IMAGES; $i++) {
            $imagesMock[] = new Image($this->getFaker()->imageUrl($width = 640, $height = 480));
        }

        return [
            [$imagesMock],
        ];
    }

    public function generatedImagesUrls(): array
    {
        $imagesMock = [];

        for ($i = 0; $i < self::IMAGES; $i++) {
            $imagesMock[] = $this->getFaker()->imageUrl($width = 640, $height = 480);
        }

        return [
            [$imagesMock],
        ];
    }
}
