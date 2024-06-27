<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Category;

use JsonSerializable;
use stdClass;

class CategoryContentScoreRuleConfig implements JsonSerializable
{
    /**
     * @var int|null
     */
    protected $min;

    /**
     * @var int|null
     */
    protected $max;

    public function __construct(?int $min = null, ?int $max = null)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->min = $this->min;
        $serialized->max = $this->max;

        return $serialized;
    }
}
