<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Feed;

use DateTimeImmutable;
use JsonSerializable;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use stdClass;

class Feed implements JsonSerializable
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string|null
     */
    protected $action;

    /**
     * @var DateTimeImmutable|null
     */
    protected $creation = null;

    /**
     * @var DateTimeImmutable|null
     */
    protected $updated = null;

    /**
     * @var string|null
     */
    protected $source;

    /**
     * @var int|null
     */
    protected $totalRecords;

    /**
     * @var int|null
     */
    protected $processedRecords;

    /**
     * @var int|null
     */
    protected $failedRecords;

    /**
     * @var FeedErrors
     */
    protected $errors = null;

    /**
     * @var FeedWarnings
     */
    protected $warnings = null;

    /**
     * @var FailureReports|null
     */
    protected $failureReports = null;

    public function __construct(
        string $id,
        string $status,
        ?string $action = null,
        ?string $creation = null,
        ?string $updated = null,
        ?string $source = null,
        ?int $totalRecords = null,
        ?int $processedRecords = null,
        ?int $failedRecords = null,
        ?FeedErrors $errors = null,
        ?FeedWarnings $warnings = null,
        ?FailureReports $failureReports = null
    ) {
        if (empty($id)) {
            throw new EmptyArgumentException('Id');
        }

        if (empty($status)) {
            throw new EmptyArgumentException('Status');
        }

        $this->id = $id;
        $this->status = $status;
        $this->action = $action;

        if ($creation) {
            $this->creation = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $creation) ?: null;
        }

        if ($updated) {
            $this->updated = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $updated) ?: null;
        }

        $this->source = $source;
        $this->totalRecords = $totalRecords;
        $this->processedRecords = $processedRecords;
        $this->failedRecords = $failedRecords;
        $this->errors = $errors ?? new FeedErrors();
        $this->warnings = $warnings ?? new FeedWarnings();
        $this->failureReports = $failureReports;

        if (!empty($creation)) {
            $this->creation = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $creation) ?: null;
        }

        if (!empty($updated)) {
            $this->updated = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $updated) ?: null;
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getCreationDate(): ?DateTimeImmutable
    {
        if (!$this->creation) {
            return null;
        }

        return $this->creation;
    }

    public function getUpdatedDate(): ?DateTimeImmutable
    {
        if (!$this->updated) {
            return null;
        }

        return $this->updated;
    }

    public function getTotalRecords(): ?int
    {
        return $this->totalRecords;
    }

    public function getProcessedRecords(): ?int
    {
        return $this->processedRecords;
    }

    public function getFailedRecords(): ?int
    {
        return $this->failedRecords;
    }

    public function getErrors(): FeedErrors
    {
        return $this->errors;
    }

    public function getWarnings(): FeedWarnings
    {
        return $this->warnings;
    }

    public function getFailureReports(): ?FailureReports
    {
        return $this->failureReports;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->id = $this->id;
        $serialized->status = $this->status;
        $serialized->action = $this->action;
        $serialized->creation = $this->creation;
        $serialized->updated = $this->updated;
        $serialized->source = $this->source;
        $serialized->totalRecords = $this->totalRecords;
        $serialized->processedRecords = $this->processedRecords;
        $serialized->failedRecords = $this->failedRecords;
        $serialized->errors = $this->errors;
        $serialized->warnings = $this->warnings;
        $serialized->failureReports = $this->failureReports;

        return $serialized;
    }
}
