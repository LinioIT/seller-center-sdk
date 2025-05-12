<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Product;

use Linio\SellerCenter\Model\Product\VariationAttributes;
use PHPStan\Testing\TestCase;

class VariationAttributesTest extends TestCase
{
    public function testItCreatesVariationAttributes(): void
    {
        $variationAttributesArray = [
            'ColorBasicoVariation' => 'Green',
            'TallaBicicleta' => 'L',
        ];

        $variationAttributes = new VariationAttributes();

        $variationAttributes->add('ColorBasicoVariation', 'Green');
        $variationAttributes->add('TallaBicicleta', 'L');

        $this->assertInstanceOf(VariationAttributes::class, $variationAttributes);
        $this->assertEquals($variationAttributes->all(), $variationAttributesArray);
    }

    public function testItReturnsNullInGetVariationAttributeWhenThisParameterDoesNotExist(): void
    {
        $variationAttributes = new VariationAttributes();

        $variationAttributes->add('ColorBasicoVariation', 'Green');
        $variationAttributes->add('TallaBicicleta', 'L');

        $this->assertInstanceOf(VariationAttributes::class, $variationAttributes);
        $this->assertEquals($variationAttributes->getVariationAttribute('ColorBasicoVariation'), 'Green');
        $this->assertEquals($variationAttributes->getVariationAttribute('TallaBicicleta'), 'L');
        $this->assertNull($variationAttributes->getVariationAttribute('TallaCama'));
    }
}
