<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Linio\SellerCenter\Application\Configuration;
use ReflectionClass;

class SellerCenterSdkTest extends LinioTestCase
{
    public function testItCreatesAnInstanceWithTheMinimumParameters(): void
    {
        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdk = new SellerCenterSdk($configuration);

        $sdkReflection = new ReflectionClass(SellerCenterSdk::class);
        $property = $sdkReflection->getProperty('configuration');
        $property->setAccessible(true);

        $this->assertInstanceOf(Configuration::class, $property->getValue($sdk));
    }
}
