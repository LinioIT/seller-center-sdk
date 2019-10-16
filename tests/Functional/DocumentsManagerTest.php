<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Exception;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Model\Document\Document;

class DocumentsManagerTest extends LinioTestCase
{
    use ClientHelper;

    public function testItReturnsACollectionOfDocuments(): void
    {
        $body = '<?xml version="1.0" encoding="UTF-8"?>
                <SuccessResponse>
                     <Head>
                          <RequestId/>
                          <RequestAction>GetDocument</RequestAction>
                          <ResponseType>Document</ResponseType>
                          <Timestamp>2019-01-16T12:56:50-0500</Timestamp>
                     </Head>
                     <Body>
                          <Documents>
                               <Document>
                                    <DocumentType>invoice</DocumentType>
                                    <MimeType>text/html</MimeType>
                                    <File>kJPHRkIHWxlPd</File>
                               </Document>
                          </Documents>
                     </Body>
                </SuccessResponse>';

        $client = $this->createClientWithResponse($body);

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

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

        $client = $this->createClientWithResponse($body, 400);

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $sdkClient->documents()->getDocument('invoice', [12345]);
    }
}
