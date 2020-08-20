<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Response;

use DateTimeImmutable;
use Linio\SellerCenter\Exception\EmptyArgumentException;

class FeedResponse
{
    /**
     * @var string|null
     */
    protected $requestId;

    /**
     * @var string
     */
    protected $requestAction;

    /**
     * @var string|null
     */
    protected $responseType;

    /**
     * @var DateTimeImmutable|null
     */
    protected $timestamp;

    public function __construct(?string $requestId, string $requestAction, string $responseType, string $timestamp)
    {
        if (empty($requestAction)) {
            throw new EmptyArgumentException('RequestAction');
        }

        if (empty($timestamp)) {
            throw new EmptyArgumentException('Timestamp');
        }

        $date = DateTimeImmutable::createFromFormat(DATE_ATOM, $timestamp);

        $this->requestId = $requestId;
        $this->requestAction = $requestAction;
        $this->responseType = $responseType ?? null;
        $this->timestamp = $date ? $date : null;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    public function getRequestAction(): string
    {
        return $this->requestAction;
    }

    public function getResponseType(): ?string
    {
        return $this->responseType;
    }

    public function getTimestamp(): ?DateTimeImmutable
    {
        return $this->timestamp;
    }
}
