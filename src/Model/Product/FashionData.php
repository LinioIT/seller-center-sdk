<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

class FashionData
{
    public const COLOR = 'Color';
    public const BASIC_COLOR = 'ColorBasico';
    public const SIZE = 'Size';
    public const TALLA = 'Talla';

    /**
     * @var mixed[]
     */
    protected $attributes = [];

    public function __construct(
        ?string $color = null,
        ?string $basicColor = null,
        ?string $size = null,
        ?string $talla = null
    ) {
        $this->attributes[self::COLOR] = $color;
        $this->attributes[self::BASIC_COLOR] = $basicColor;
        $this->attributes[self::SIZE] = $size;
        $this->attributes[self::TALLA] = $talla;
    }

    /**
     * @return string[]
     */
    public function all(): array
    {
        return $this->attributes;
    }
}
