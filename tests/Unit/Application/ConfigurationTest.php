<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Application;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\LinioTestCase;

class ConfigurationTest extends LinioTestCase
{
    public function testItReturnsTheEndpoint(): void
    {
        $configuration = new Configuration('API_KEY', 'API_USERNAME', 'API_ENDPOINT', 'API_VERSION');

        $this->assertEquals($configuration->getEndpoint(), 'API_ENDPOINT');
    }

    public function testItReturnsTheVersion(): void
    {
        $configuration = new Configuration('API_KEY', 'API_USERNAME', 'API_ENDPOINT', 'API_VERSION');

        $this->assertEquals($configuration->getVersion(), 'API_VERSION');
    }

    public function testItReturnsTheDefaultVersion(): void
    {
        $configuration = new Configuration('API_KEY', 'API_USERNAME', 'API_ENDPOINT');

        $this->assertEquals($configuration->getVersion(), '1.0');
    }

    public function testItReturnsTheApiKey(): void
    {
        $configuration = new Configuration('API_KEY', 'API_USERNAME', 'API_ENDPOINT', 'API_VERSION');

        $this->assertEquals($configuration->getKey(), 'API_KEY');
    }

    public function testItReturnsTheUsername(): void
    {
        $configuration = new Configuration('API_KEY', 'API_USERNAME', 'API_ENDPOINT', 'API_VERSION');

        $this->assertEquals($configuration->getUser(), 'API_USERNAME');
    }
}
