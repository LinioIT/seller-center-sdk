<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Response\SuccessResponse;
use Linio\SellerCenter\Service\BaseManager;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
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
     * @var ObjectProphecy
     */
    protected $requestFactory;

    /**
     * @var ObjectProphecy
     */
    protected $streamFactory;

    /**
     * @var BaseManager
     */
    private $baseManager;

    public function setUp(): void
    {
        $this->configurationStub = new Configuration('foo', 'bar', 'baz');
        $this->clientStub = $this->prophesize(ClientInterface::class);
        $this->parametersStub = new Parameters();
        $this->loggerStub = $this->prophesize(LoggerInterface::class);
        $this->requestFactory = $this->prophesize(RequestFactoryInterface::class);
        $this->streamFactory = $this->prophesize(StreamFactoryInterface::class);

        $this->baseManager = new BaseManager(
            $this->configurationStub,
            $this->clientStub->reveal(),
            $this->parametersStub,
            $this->loggerStub->reveal(),
            $this->requestFactory->reveal(),
            $this->streamFactory->reveal()
        );
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
        $request = $this->prophesize(RequestInterface::class);
        $request
            ->withHeader(Argument::type('string'), Argument::type('string'))
            ->shouldBeCalled()
            ->willReturn($request->reveal());
        $request
            ->withBody(Argument::type(StreamInterface::class))
            ->shouldBeCalled()
            ->willReturn($request->reveal());
        $request->getUri()->shouldBeCalled();
        $request->getBody()->shouldBeCalled();
        $request->getMethod()->shouldBeCalled();

        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()->shouldBeCalled()->willReturn($this->getRawSuccessResponse());

        $this->streamFactory
            ->createStream(Argument::type('string'))
            ->shouldBeCalled()
            ->willReturn($this->prophesize(StreamInterface::class)->reveal());

        $this->requestFactory
            ->createRequest(Argument::type('string'), Argument::type('string'))
            ->shouldBeCalled()
            ->willReturn($request->reveal());

        $this->loggerStub
            ->debug(Argument::type('string'), Argument::type('array'))
            ->shouldBeCalledTimes(3);

        $this->clientStub
            ->sendRequest(Argument::type(RequestInterface::class))
            ->shouldBeCalled()
            ->willReturn($response->reveal());

        $result = $this->baseManager->executeAction('FooAction', '', $this->parametersStub);
        $this->assertInstanceOf(SuccessResponse::class, $result);
    }

    /**
     * @dataProvider fullEndpointsProvider
     */
    public function testItBuildsFullEndpoint(string $expected, string $endpoint, array $query): void
    {
        $result = $this->baseManager->buildFullEndpoint($endpoint, $query);
        $this->assertSame($expected, $result);
    }

    public function fullEndpointsProvider()
    {
        return [
            ['http://linio.com/?foo=bar', 'http://linio.com/', ['foo' => 'bar']],
            ['http://linio.com/', 'http://linio.com/', []],
        ];
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
