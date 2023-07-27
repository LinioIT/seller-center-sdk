<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Exception;
use Linio\SellerCenter\Model\Document\Document;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class DocumentsManagerTest extends LinioTestCase
{
    use ClientHelper;

    /**
     * @var ObjectProphecy
     */
    protected $logger;

    public function prepareLogTest(bool $debug): void
    {
        $this->logger = $this->prophesize(LoggerInterface::class);

        $this->logger->debug(
            Argument::type('string'),
            Argument::type('array')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->debug(
                Argument::type('string'),
                Argument::type('array')
            )->shouldNotBeCalled();
        }
    }

    public function testItReturnsACollectionOfDocuments(): void
    {
        $body = $this->getSchema('Document/GetDocumentSuccessResponse.xml');
        $sdkClient = $this->getSdkClient($body);

        $document = $sdkClient->documents()->getDocument('invoice', [12345]);

        $this->assertInstanceOf(Document::class, $document);
    }

    public function testItThrowsAnExceptionWhenTheResponseIsAnError(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('E020: "65758123" Invalid Order Item ID');

        $body = '<?xml version="1.0" encoding="UTF-8"?>
                <ErrorResponse>
                     <Head>
                          <RequestAction>GetDocument</RequestAction>
                          <ErrorType>Sender</ErrorType>
                          <ErrorCode>20</ErrorCode>
                          <ErrorMessage>E020: "65758123" Invalid Order Item ID</ErrorMessage>
                     </Head>
                     <Body/>
                </ErrorResponse>';

        $sdkClient = $this->getSdkClient($body, null, 400);

        $sdkClient->documents()->getDocument('invoice', [12345]);
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetDocumentStatusSuccessResponse(bool $debug): void
    {
        $body = $this->getSchema('Document/GetDocumentSuccessResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->documents()->getDocument(
            'invoice',
            [12345],
            $debug
        );
    }

    public function debugParameter()
    {
        return [
            [false],
            [true],
        ];
    }
}
