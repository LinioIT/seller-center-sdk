<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory;

use Linio\SellerCenter\LinioTestCase;
use Psr\Http\Message\RequestInterface;

class RequestFactoryTest extends LinioTestCase
{
    public function testItIsGeneratingARequest(): void
    {
        $request = RequestFactory::make('GET', '');
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function testItIsInsertingTheHeaders(): void
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Request-Id' => '123456789',
            'foo' => 'bar',
        ];

        $request = RequestFactory::make('GET', '', $headers);
        $requestHeaders = $request->getHeaders();
        $this->assertNotEmpty($requestHeaders);

        foreach ($headers as $header => $value) {
            $this->assertArrayHasKey($header, $requestHeaders);
            $this->assertEquals($value, reset($requestHeaders[$header]));
        }
    }

    public function testItIsAppendingTheBody(): void
    {
        $body = 'foo';

        $request = RequestFactory::make('GET', '', [], $body);
        $this->assertEquals($body, (string) $request->getBody());
    }
}
