<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Category;

use JsonSerializable;
use stdClass;

class CategoryContentScoreRule implements JsonSerializable
{
    /**
     * @var string
     */
    protected $rule;

    /**
     * @var string
     */
    protected $field;

    /**
     * @var int
     */
    protected $score;

    /**
     * @var CategoryContentScoreRuleConfig
     */
    protected $config;

    public function __construct(string $rule, string $field, int $score = null, ?CategoryContentScoreRuleConfig $config = null)
    {
        $this->rule = $rule;
        $this->field = $field;
        $this->score = $score;
        $this->config = $config;
    }

    public function getRule(): string
    {
        return $this->rule;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getConfig(): ?CategoryContentScoreRuleConfig
    {
        return $this->config;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->rule = $this->rule;
        $serialized->field = $this->field;
        $serialized->score = $this->score;
        $serialized->config = $this->config;

        return $serialized;
    }
}
