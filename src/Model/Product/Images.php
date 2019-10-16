<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use JsonSerializable;
use Linio\SellerCenter\Contract\CollectionInterface;
use Linio\SellerCenter\Exception\MaxImagesExceededException;

class Images implements CollectionInterface, JsonSerializable
{
    public const MAX_IMAGES_ALLOWED = 8;

    /**
     * @var Image[]
     */
    protected $collection = [];

    /**
     * @return Image[]
     */
    public function all(): array
    {
        return $this->collection;
    }

    public function add(Image $image): void
    {
        if (count($this->collection) >= self::MAX_IMAGES_ALLOWED) {
            throw new MaxImagesExceededException(self::MAX_IMAGES_ALLOWED);
        }

        $this->collection[] = $image;
    }

    /**
     * @param Image[] $images
     */
    public function addMany(array $images): void
    {
        $filtered = $images;

        foreach ($images as $key => $image) {
            if (!$image instanceof Image) {
                unset($filtered[$key]);
            }
        }

        $available = self::MAX_IMAGES_ALLOWED - count($this->collection);

        $items = array_slice($filtered, 0, $available);

        $this->collection = array_merge($this->collection, $items);
    }

    /**
     * @param string[] $images
     */
    public function addManyFromUrls(array $images): void
    {
        $filtered = [];

        foreach ($images as $key => $image) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                $filtered[] = new Image($image);
            }
        }

        $available = self::MAX_IMAGES_ALLOWED - count($this->collection);

        $items = array_slice($filtered, 0, $available);

        $this->collection = array_merge($this->collection, $items);
    }

    public function jsonSerialize(): array
    {
        return $this->collection;
    }
}
