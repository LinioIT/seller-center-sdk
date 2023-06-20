<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Response;

use Linio\Component\Util\Json;

class SuccessJsonResponse
{

    protected string $message;
    /**
     * @var mixed[]
     */
    protected array $data;
    
    /**
     * @var mixed[]
     */
    protected array $json;

    /**
     * @param mixed[] $json
     */
    public static function fromJson(array $json): SuccessJsonResponse
    {
        //return new self($json['message'] ?? 'Empty message', $json['data'] ?? [], $json);
        return new self('test', [],[]);
    }

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

    public function getBaseData(): string
    {
        return $this->message;
    }

    public function getDetailData(): string
    {
        return Json::encode($this->getData());
    }
}
