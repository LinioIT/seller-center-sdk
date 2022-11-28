<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Application;

use DateTime;
use Linio\SellerCenter\LinioTestCase;

class ParametersTest extends LinioTestCase
{
    public function testItReturnsTheParametersInOrder(): void
    {
        $parameters = new Parameters();

        $parameters->set([
            'C' => 3,
            'A' => 1,
            'B' => 2,
        ]);

        $expect = [
            'A' => '1',
            'B' => '2',
            'C' => '3',
        ];

        $result = $parameters->all(true);

        $this->assertSame($expect, $result);
    }

    public function testItReturnsAnEmptyArrayIfNoParametersWereAdded(): void
    {
        $parameters = new Parameters();

        $empty = $parameters->all();

        $this->assertEmpty($empty);
    }

    public function testItReturnsAParameterObjectWithTheBasicDataWithoutSpecifyTheFormat(): void
    {
        $parameters = Parameters::fromBasics('user', 'version');

        $basics = $parameters->all();

        $date = date_create_from_format(DATE_ATOM, $basics['Timestamp']);
        $now = new DateTime();

        $this->assertInstanceOf(Datetime::class, $date);
        $this->assertEquals($now->format('Y-m-d'), $date->format('Y-m-d'));
        $this->assertEquals('user', $basics['UserID']);
        $this->assertEquals('version', $basics['Version']);
        $this->assertEquals('XML', $basics['Format']);
    }

    public function testItReturnsAParameterObjectWithTheBasicDataWithoutSpecifyingTheFormat(): void
    {
        $parameters = Parameters::fromBasics('user', 'version', 'json');

        $basics = $parameters->all();

        $date = date_create_from_format(DATE_ATOM, $basics['Timestamp']);
        $now = new DateTime();

        $this->assertInstanceOf(Datetime::class, $date);
        $this->assertEquals($now->format('Y-m-d'), $date->format('Y-m-d'));
        $this->assertEquals('user', $basics['UserID']);
        $this->assertEquals('version', $basics['Version']);
        $this->assertEquals('json', $basics['Format']);
    }

    /**
     * @dataProvider configurationProvider
     */
    public function testItCreatesParameterFromConfiguration(Configuration $configuration): void
    {
        $parameters = Parameters::fromConfiguration($configuration);

        $basics = $parameters->all();
        $this->assertEquals('API_USERNAME', $basics['UserID']);
        $this->assertEquals('API_VERSION', $basics['Version']);
        $this->assertEquals('XML', $basics['Format']);
    }

    public function configurationProvider(): array
    {
        return [
            'default case' => [
                'configuration' => new Configuration('API_KEY', 'API_USERNAME', 'API_ENDPOINT', 'API_VERSION'),
            ],
            'full case' => [
                'configuration' => new Configuration('API_KEY', 'API_USERNAME', 'API_ENDPOINT', 'API_VERSION', 'SOURCE', 'USER_ID', 'NOT_PHP', '5.5', 'INTEGRATOR', 'CL'),
            ],
        ];
    }
}
