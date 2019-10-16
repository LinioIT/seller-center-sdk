<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Feed;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Feed\Feed;
use SimpleXMLElement;

class FeedFactory
{
    public static function make(SimpleXMLElement $xml): Feed
    {
        if (!property_exists($xml, 'Feed')) {
            throw new InvalidXmlStructureException('Feed', 'Feed');
        }

        if (!property_exists($xml, 'Status')) {
            throw new InvalidXmlStructureException('Feed', 'Status');
        }

        $id = (string) $xml->Feed;
        $status = (string) $xml->Status;
        $action = (string) $xml->Action ?: null;
        $creation = (string) $xml->CreationDate ?: null;
        $updated = (string) $xml->UpdatedDate ?: null;
        $source = (string) $xml->Source ?: null;
        $totalRecords = (int) $xml->TotalRecords ?: null;
        $processedRecords = (int) $xml->ProcessedRecords ?: null;
        $failedRecords = (int) $xml->FailedRecords ?: null;
        $errors = null;
        $warnings = null;
        $failureReports = null;

        if (!empty($xml->FeedErrors)) {
            $errors = FeedErrorsFactory::make($xml->FeedErrors);
        }

        if (!empty($xml->FeedWarnings)) {
            $warnings = FeedWarningsFactory::make($xml->FeedWarnings);
        }

        if (!empty($xml->FailureReports)) {
            $failureReports = FailureReportsFactory::make($xml->FailureReports);
        }

        return new Feed($id, $status, $action, $creation, $updated, $source, $totalRecords, $processedRecords, $failedRecords, $errors, $warnings, $failureReports);
    }
}
