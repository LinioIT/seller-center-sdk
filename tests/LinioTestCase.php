<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Faker;
use Faker\Generator;
use Linio\Component\Util\Json;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class LinioTestCase extends TestCase
{
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
}
