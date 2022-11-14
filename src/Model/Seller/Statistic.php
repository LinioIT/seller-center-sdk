<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Seller;

use JsonSerializable;
use stdClass;

class Statistic implements JsonSerializable
{
    /**
     * @var array
     */
    protected $productStatistics;

    /**
     * @var array
     */
    protected $orderStatistics;

    public static function build(): Statistic
    {
        $statistics = new self();

        $statistics->productStatistics = [];
        $statistics->orderStatistics = [];

        return $statistics;
    }

    public function getProductStatistics(): array
    {
        return $this->productStatistics;
    }

    public function getOrderStatistics(): array
    {
        return $this->orderStatistics;
    }

    public function getProductStatistic(string $key): int
    {
        return (int) $this->productStatistics[$key];
    }

    public function getOrderStatistic(string $key): int
    {
        return (int) $this->orderStatistics[$key];
    }

    public function addProductStatistic(string $key, int $value): void
    {
        $this->productStatistics[$key] = $value;
    }

    public function addOrderStatistic(string $key, int $value): void
    {
        $this->orderStatistics[$key] = $value;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->productStatistics = $this->productStatistics;
        $serialized->orderStatistics = $this->orderStatistics;

        return $serialized;
    }
}
