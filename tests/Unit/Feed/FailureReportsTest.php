<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Feed;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Feed\FailureReportsFactory;
use Linio\SellerCenter\LinioTestCase;

class FailureReportsTest extends LinioTestCase
{
    public function testItThrowsExceptionIfMimeTypeIsMissing(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a FailureReports. The property MimeType should exist.');

        $xml = simplexml_load_string('
            <FailureReports>
                 <File>IkVycm9yIjsiV2FybmluZyI7IlNlbGdFN....</File>
            </FailureReports>
        ');

        FailureReportsFactory::make($xml);
    }

    public function testItThrowsExceptionIfFileIsMissing(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a FailureReports. The property File should exist.');

        $xml = simplexml_load_string('
            <FailureReports>
                 <MimeType>text/csv</MimeType>
            </FailureReports>
        ');

        FailureReportsFactory::make($xml);
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $mimeType = 'text';
        $file = 'file';

        $simpleXml = simplexml_load_string(sprintf('
            <FailureReports>
                 <MimeType>%s</MimeType>
                 <File> %s</File>
            </FailureReports>', $mimeType, $file));

        $failureReports = FailureReportsFactory::make($simpleXml);

        $expectedJson = sprintf('{"mimeType": "%s", "file": " %s"}', $mimeType, $file);
        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($failureReports));
    }
}
