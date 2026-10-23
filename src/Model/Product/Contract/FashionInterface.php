<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product\Contract;

interface FashionInterface
{
    public function getColor(): ?string;

    public function getColorBasico(): ?string;

    public function getSize(): ?string;

    public function getTalla(): ?string;

    public function setColor(string $color): void;

    public function setColorBasico(string $colorBasico): void;

    public function setSize(string $size): void;

    public function setTalla(string $talla): void;
}
