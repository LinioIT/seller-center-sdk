<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Response;

use Linio\Component\Util\Json;

class SuccessJsonResponse
{
    const EMPTY_MESSAGE = 'Empty message';
    const MESSAGE_KEY = 'message';
    const DATA_KEY = 'data';

    /**
     * @var string
     */
    protected $message;

    /**
     * @var mixed[]
     */
    protected $data;

    /**
     * @var mixed[]
     */
    protected $json;

    /**
     * @param mixed[] $json
     */
    public static function fromJson(array $json): SuccessJsonResponse
    {
        return new self(
            $json[self::MESSAGE_KEY] ?? self::EMPTY_MESSAGE,
            $json[self::DATA_KEY] ?? [],
            $json
        );
    }

    /**
     * @param mixed[] $data
     * @param mixed[] $json
     */
    public function __construct(string $message, array $data, array $json)
    {
        $this->message = $message;
        $this->data = $data;
        $this->json = $json;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return mixed[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getDataToString(): string
    {
        return Json::encode($this->getData());
    }
}
