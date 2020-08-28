<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Contract;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    public function send(RequestInterface $request, array $options = []): ResponseInterface;
}
