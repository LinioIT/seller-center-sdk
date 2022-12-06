<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Contract\ClientInterface;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Service\BaseManager;
use PHPUnit\Framework\MockObject\MockObject;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class BaseManagerTest extends LinioTestCase
{
    /**
     * @var ObjectProphecy
     */
    protected $configurationStub;

    /**
     * @var ObjectProphecy
     */
    protected $clientStub;

    /**
     * @var Parameters
     */
    protected $parametersStub;

    /**
     * @var ObjectProphecy
     */
    protected $loggerStub;

    /**
     * @var BaseManager|MockObject
     */
    private $baseManager;

    public function setUp(): void
    {
        $this->configurationStub = new Configuration('foo', 'bar', 'baz');
        $this->clientStub = $this->prophesize(ClientInterface::class);
        $this->parametersStub = new Parameters();
        $this->loggerStub = $this->prophesize(LoggerInterface::class);

        $this->baseManager = $this->getMockForAbstractClass(BaseManager::class, [
            $this->configurationStub,
            $this->clientStub->reveal(),
            $this->parametersStub,
            $this->loggerStub->reveal(),
        ]);
    }

    public function testItGeneratesRequestIds(): void
    {
        $requestId = $this->baseManager->generateRequestId();
        $this->assertIsString($requestId);
        $this->assertNotEmpty($requestId);
    }

    public function testItBuildQueryParameters(): void
    {
        $this->parametersStub->set(['foo' => true]);
        $queryParameters = $this->baseManager->buildQuery($this->parametersStub);
        $this->assertCount(2, $queryParameters);
        $this->assertArrayHasKey('foo', $queryParameters);
        $this->assertArrayHasKey('Signature', $queryParameters);
    }

    public function testItExecutesAction(): void
    {
        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()->shouldBeCalled()->willReturn($this->getRawSuccessResponse());

        $this->loggerStub
            ->debug(Argument::type('string'), Argument::type('array'))
            ->shouldBeCalledTimes(1);

        $this->clientStub
            ->send(
                Argument::type(RequestInterface::class),
                Argument::type('array')
            )
            ->shouldBeCalled()
            ->willReturn($response->reveal());

        $this->baseManager->executeAction('FooAction', $this->parametersStub, '');
        $this->assertTrue(true);
    }

    private function getRawSuccessResponse(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                         <Head>
                         </Head>
                         <Body>
                         </Body>
                    </SuccessResponse>';
    }
}
