<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

class FashionData
{
    public const COLOR = 'Color';
    public const BASIC_COLOR = 'ColorBasico';
    public const SIZE = 'Size';

    /**
     * @var mixed[]
     */
    protected $attributes = [];

    public function __construct(
        ?string $color = null,
        ?string $basicColor = null,
        ?string $size = null
    ) {
        $this->attributes[self::COLOR] = $color;
        $this->attributes[self::BASIC_COLOR] = $basicColor;
        $this->attributes[self::SIZE] = $size;
    }

    /**
     * @return string[]
     */
    public function all(): array
    {
        return $this->attributes;
    }
}
