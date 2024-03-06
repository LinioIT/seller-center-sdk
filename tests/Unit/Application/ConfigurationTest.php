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

    /**
     * @dataProvider configurationProvider
     */
    public function testItSetsUserAgent(Configuration $configuration, string $userAgent): void
    {
        $this->assertEquals($userAgent, $configuration->getUserAgent());
    }

    public function configurationProvider(): array
    {
        return [
            'default case' => [
                'configuration' => new Configuration('API_KEY', 'API_USERNAME', 'API_ENDPOINT', 'API_VERSION'),
                'userAgent' => sprintf('/PHP/%s///FalabellaSDKPHP', phpversion()),
            ],
            'full case' => [
                'configuration' => new Configuration('API_KEY', 'API_USERNAME', 'API_ENDPOINT', 'API_VERSION', 'SOURCE', 'SELLER_ID', 'NOT_PHP', '5.5', 'INTEGRATOR', 'CL'),
                'userAgent' => 'SELLER_ID/NOT_PHP/5.5/INTEGRATOR/CL/FalabellaSDKPHP',
            ],
            'missing country' => [
                'configuration' => new Configuration('API_KEY', 'API_USERNAME', 'API_ENDPOINT', 'API_VERSION', 'SOURCE', 'SELLER_ID', 'NOT_PHP', '5.5', 'INTEGRATOR'),
                'userAgent' => 'SELLER_ID/NOT_PHP/5.5/INTEGRATOR//FalabellaSDKPHP',
            ],
            'missing integrator' => [
                'configuration' => new Configuration('API_KEY', 'API_USERNAME', 'API_ENDPOINT', 'API_VERSION', 'SOURCE', 'SELLER_ID', 'NOT_PHP', '5.5', null, 'CL'),
                'userAgent' => 'SELLER_ID/NOT_PHP/5.5//CL/FalabellaSDKPHP',
            ],
        ];
    }
}
