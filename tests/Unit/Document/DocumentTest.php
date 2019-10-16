<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Document;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidDocumentTypeException;
use Linio\SellerCenter\Exception\InvalidFileException;
use Linio\SellerCenter\Exception\InvalidMimeTypeException;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Document\DocumentFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Document\Document;

class DocumentTest extends LinioTestCase
{
    public function testItReturnsTheValueWithEachAccessor(): void
    {
        $documentType = 'invoice';
        $mimeType = 'text/html';
        $file = 'kJPHRkIHWxlPd';

        $simpleXml = simplexml_load_string(sprintf('<Document>
                            <DocumentType>%s</DocumentType>
                            <MimeType>%s</MimeType>
                            <File>%s</File>
                          </Document>', $documentType, $mimeType, $file));

        $document = DocumentFactory::make($simpleXml);

        $this->assertEquals($document->getDocumentType(), $documentType);
        $this->assertEquals($document->getMimeType(), $mimeType);
        $this->assertEquals($document->getFile(), $file);
    }

    public function testItThrowsAnExceptionWithoutADocumentType(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Document. The property DocumentType should exist.');

        $mimeType = 'text/html';
        $file = 'kJPHRkIHWxlPd';

        $simpleXml = simplexml_load_string(sprintf('<Document>
                            <MimeType>%s</MimeType>
                            <File>%s</File>
                          </Document>', $mimeType, $file));

        DocumentFactory::make($simpleXml);
    }

    public function testItThrowsAnExceptionWithoutAMimeType(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Document. The property MimeType should exist.');

        $documentType = 'invoice';
        $file = 'kJPHRkIHWxlPd';

        $simpleXml = simplexml_load_string(sprintf('<Document>
                            <DocumentType>%s</DocumentType>
                            <File>%s</File>
                          </Document>', $documentType, $file));

        DocumentFactory::make($simpleXml);
    }

    public function testItThrowsAnExceptionWithoutAFile(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Document. The property File should exist.');

        $documentType = 'invoice';
        $mimeType = 'text/html';

        $simpleXml = simplexml_load_string(sprintf('<Document>
                            <DocumentType>%s</DocumentType>
                            <MimeType>%s</MimeType>
                          </Document>', $documentType, $mimeType));

        DocumentFactory::make($simpleXml);
    }

    public function testThrowsAnExceptionAIfTheDocumentTypedIsNull(): void
    {
        $this->expectException(InvalidDocumentTypeException::class);

        new Document('', 'test-mimetype', 'test-file');
    }

    public function testThrowsAnExceptionIfTheMimeTypeIsNull(): void
    {
        $this->expectException(InvalidMimeTypeException::class);

        new Document('invoice', '', 'test-file');
    }

    public function testThrowsAnExceptionIfTheFileIsNUll(): void
    {
        $this->expectException(InvalidFileException::class);

        new Document('invoice', 'test-mimetype', '');
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $documentType = 'invoice';
        $mimeType = 'text/html';
        $file = 'klm1k23n190';

        $simpleXml = simplexml_load_string(sprintf('<Document>
                            <DocumentType>%s</DocumentType>
                            <MimeType>%s</MimeType>
                            <File>%s</File>
                          </Document>', $documentType, $mimeType, $file));

        $document = DocumentFactory::make($simpleXml);

        $expectedJson = sprintf('{"documentType": "%s", "mimeType": "%s", "file": "%s"}', $documentType, $mimeType, $file);
        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($document));
    }
}
