<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Application\Security;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Exception\InvalidApiKeyException;
use Linio\SellerCenter\LinioTestCase;
use ReflectionClass;

class SignatureTest extends LinioTestCase
{
    public function testItReturnsAnStringFromASignatureObject(): void
    {
        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $parameters = Parameters::fromBasics($configuration->getUser(), $configuration->getVersion());

        $signature = Signature::generate($parameters->all(), $configuration->getKey());

        $rs = new ReflectionClass(Signature::class);
        $property = $rs->getProperty('signature');
        $property->setAccessible(true);

        $property->setValue($signature, 'TestSignature');

        $this->assertEquals('TestSignature', $signature);
    }

    public function testSupportsIntegerValuesAsParameters(): void
    {
        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $parameters = Parameters::fromBasics($configuration->getUser(), $configuration->getVersion());
        $parameters->set(['integer-parameter' => 1]);

        $signature = Signature::generate($parameters->all(), $configuration->getKey());

        $rs = new ReflectionClass(Signature::class);
        $property = $rs->getProperty('signature');
        $property->setAccessible(true);

        $property->setValue($signature, 'TestSignature');

        $this->assertEquals('TestSignature', $signature);
    }

    public function testItThrowsAnExceptionWithAnEmptyApiKey(): void
    {
        $this->expectException(InvalidApiKeyException::class);
        $this->expectExceptionMessage('The API KEY cannot be null.');
        $parameters = new Parameters();
        Signature::generate($parameters->all(), '');
    }
}
