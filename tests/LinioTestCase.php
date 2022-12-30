<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Faker;
use Faker\Generator;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Configuration;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use RuntimeException;

class LinioTestCase extends TestCase
{
    use ClientHelper;

    public function getFaker(): Generator
    {
        if (empty($this->faker)) {
            $this->faker = Faker\Factory::create();
        }

        return $this->faker;
    }

    public function getParameters()
    {
        return [
            'key' => 'API-KEY',
            'username' => 'API-USERNAME',
            'endpoint' => 'API-ENDPOINT',
            'version' => '1.0',
        ];
    }

    public function getEnvironmentParameters(string $appName)
    {
        $appData = getenv($appName);

        if (!$appData) {
            throw new RuntimeException(sprintf('"%s" is not a valid app environment', $appName));
        }

        return Json::decode($appData);
    }

    public function getSchema(string $schema): string
    {
        return file_get_contents(__DIR__ . '/_schemas/' . $schema);
    }

    public function getSdkClient(
        string $xml,
        ?ObjectProphecy $logger = null,
        int $statusCode = 200,
        ?string $extraResponse = null
    ): SellerCenterSdk {
        $client = $this->createClientWithResponse($xml, $statusCode, $extraResponse);

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        if (empty($logger)) {
            return new SellerCenterSdk($configuration, $client);
        }

        return new SellerCenterSdk($configuration, $client, $logger->reveal());
    }
}
