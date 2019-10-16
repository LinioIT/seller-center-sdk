<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Document;

use JsonSerializable;
use Linio\SellerCenter\Contract\DocumentInterface;
use Linio\SellerCenter\Exception\InvalidDocumentTypeException;
use Linio\SellerCenter\Exception\InvalidFileException;
use Linio\SellerCenter\Exception\InvalidMimeTypeException;
use stdClass;

class Document implements JsonSerializable
{
    /**
     * @var string
     */
    protected $documentType;

    /**
     * @var string
     */
    protected $mimeType;

    /**
     * @var string
     */
    protected $file;

    public function __construct(string $documentType, string $mimeType, string $file)
    {
        if (!in_array($documentType, DocumentInterface::DOCUMENT_TYPES)) {
            throw new InvalidDocumentTypeException();
        }

        if (empty($mimeType)) {
            throw new InvalidMimeTypeException();
        }

        if (empty($file)) {
            throw new InvalidFileException();
        }

        $this->documentType = $documentType;
        $this->mimeType = $mimeType;
        $this->file = $file;
    }

    public function getDocumentType(): string
    {
        return $this->documentType;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->documentType = $this->documentType;
        $serialized->mimeType = $this->mimeType;
        $serialized->file = $this->file;

        return $serialized;
    }
}
