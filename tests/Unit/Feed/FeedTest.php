<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit;

use Exception;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Factory\Xml\Feed\FeedFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Feed\Feed;
use Linio\SellerCenter\Model\Feed\FeedError;
use Linio\SellerCenter\Model\Feed\FeedWarning;
use Linio\SellerCenter\Model\Feed\FeedWarnings;

class FeedTest extends LinioTestCase
{
    /**
     * @dataProvider feedRandomData
     */
    public function testItReturnsAValidFeedInstanceFromXml($feedId, $status, $action, $created, $updated, $source, $totalRecords, $processedRecords, $failedRecords, $mimeType, $file): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <SuccessResponse>
             <Head>
                  <RequestId/>
                  <RequestAction>FeedStatus</RequestAction>
                  <ResponseType>FeedDetail</ResponseType>
                  <Timestamp>2018-12-18T07:55:07-0600</Timestamp>
                  <RequestParameters>
                       <FeedID>' . $feedId . '</FeedID>
                  </RequestParameters>
             </Head>
             <Body>
                  <FeedDetail>
                       <Feed>' . $feedId . '</Feed>
                       <Status>' . $status . '</Status>
                       <Action>' . $action . '</Action>
                       <CreationDate>' . $created . '</CreationDate>
                       <UpdatedDate>' . $updated . '</UpdatedDate>
                       <Source>' . $source . '</Source>
                       <TotalRecords>' . $totalRecords . '</TotalRecords>
                       <ProcessedRecords>' . $processedRecords . '</ProcessedRecords>
                       <FailedRecords>' . $failedRecords . '</FailedRecords>
                       <FeedErrors>
                            <Error>
                                 <Code>1</Code>
                                 <Message>Negative value is not allowed</Message>
                                 <SellerSku>9786077351993</SellerSku>
                            </Error>
                            <Error>
                                 <Code>2</Code>
                                 <Message>Seller SKU \'9788441418011\' not found</Message>
                                 <SellerSku>9788441418011</SellerSku>
                            </Error>
                            <Error>
                                 <Code>3</Code>
                                 <Message>Seller SKU \'9788498455984\' not found</Message>
                                 <SellerSku>9788498455984</SellerSku>
                            </Error>
                       </FeedErrors>
                       <FeedWarnings>
                            <Warning>
                                <Message>The following SKUs have been excluded...</Message>
                                <SellerSku>SKU-123</SellerSku>
                            </Warning>
                       </FeedWarnings>
                       <FailureReports>
                            <MimeType>' . $mimeType . '</MimeType>
                            <File>' . $file . '</File>
                       </FailureReports>
                  </FeedDetail>
             </Body>
        </SuccessResponse>';

        $response = simplexml_load_string($xml);
        $feed = FeedFactory::make($response->Body->FeedDetail);

        $this->assertInstanceOf(Feed::class, $feed);

        $this->assertEquals($feedId, $feed->getId());
        $this->assertEquals($status, $feed->getStatus());
        $this->assertEquals($action, $feed->getAction());

        if (!empty($created)) {
            $this->assertEquals($created, $feed->getCreationDate()->format('Y-m-d H:i:s'));
        } else {
            $this->assertNull($feed->getCreationDate());
        }

        if (!empty($updated)) {
            $this->assertEquals($updated, $feed->getUpdatedDate()->format('Y-m-d H:i:s'));
        } else {
            $this->assertNull($feed->getUpdatedDate());
        }

        $this->assertEquals($source, $feed->getSource());
        $this->assertEquals($totalRecords, $feed->getTotalRecords());
        $this->assertEquals($processedRecords, $feed->getProcessedRecords());
        $this->assertEquals($failedRecords, $feed->getFailedRecords());

        $errors = $feed->getErrors();
        $this->assertIsArray($errors->all());
        $this->assertContainsOnlyInstancesOf(FeedError::class, $errors->all());
        $this->assertCount(3, $errors->all());

        $warnings = $feed->getWarnings();
        $this->assertInstanceOf(FeedWarnings::class, $warnings);
        $this->assertIsArray($warnings->all());
        $this->assertContainsOnlyInstancesOf(FeedWarning::class, $warnings->all());
        $this->assertCount(1, $warnings->all());

        $failureReports = $feed->getFailureReports();
        $this->assertEquals($mimeType, $failureReports->getMimeType());
        $this->assertEquals($file, $failureReports->getFile());
    }

    public function feedRandomData()
    {
        $faker = $this->getFaker();

        $id = $this->getFaker()->uuid;
        $status = 'Finished';
        $action = 'ProductUpdate';
        $created = $faker->dateTime()->format('Y-m-d H:i:s');
        $updated = $faker->dateTime()->format('Y-m-d H:i:s');
        $source = 'api';
        $totalRecords = $faker->randomDigit;
        $processedRecords = $faker->randomDigit;
        $failedRecords = $faker->randomDigit;
        $mimeType = $faker->mimeType;
        $file = $faker->sha256;

        return [
            [$id, $status, $action, $created, $updated, $source, $totalRecords, $processedRecords, $failedRecords, $mimeType, $file],
            [$id, $status, '', '', '', '', '', '', '', '', ''],
        ];
    }

    public function testItThrowsExceptionIfResponseIsEmpty(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The xml structure is not valid for a Feed. The property Feed should exist.');

        $simplexml = simplexml_load_string('<xml></xml>');

        FeedFactory::make($simplexml);
    }

    public function testItThrowsExceptionIfFeedIdIsEmpty(): void
    {
        $this->expectException(EmptyArgumentException::class);
        $this->expectExceptionMessage('The parameter Id should not be null.');

        new Feed('', 'Status');
    }

    public function testItThrowsExceptionIfFeedStatusIsEmpty(): void
    {
        $this->expectException(EmptyArgumentException::class);
        $this->expectExceptionMessage('The parameter Status should not be null.');

        new Feed('ID', '');
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $faker = $this->getFaker();

        $feedId = $this->getFaker()->uuid;
        $status = 'Finished';
        $action = 'ProductUpdate';
        $created = $faker->dateTime()->format('Y-m-d H:i:s');
        $updated = $faker->dateTime()->format('Y-m-d H:i:s');
        $source = 'api';
        $totalRecords = $faker->randomDigitNotNull;
        $processedRecords = $faker->randomDigitNotNull;
        $failedRecords = $faker->randomDigitNotNull;

        $xml = '<FeedDetail>
                       <Feed>' . $feedId . '</Feed>
                       <Status>' . $status . '</Status>
                       <Action>' . $action . '</Action>
                       <CreationDate>' . $created . '</CreationDate>
                       <UpdatedDate>' . $updated . '</UpdatedDate>
                       <Source>' . $source . '</Source>
                       <TotalRecords>' . $totalRecords . '</TotalRecords>
                       <ProcessedRecords>' . $processedRecords . '</ProcessedRecords>
                       <FailedRecords>' . $failedRecords . '</FailedRecords>
                       <FeedErrors/>
                       <FeedWarnings/>
                       <FailureReports/>
                  </FeedDetail>';

        $simpleXml = simplexml_load_string($xml);

        $feed = FeedFactory::make($simpleXml);

        $expectedJson = sprintf(
            '{"id": "%s", "status": "%s", "action": "%s", "creation": %s, "updated": %s, "source": "%s", "totalRecords": %d, "processedRecords": %d, "failedRecords": %d, "errors": [], "warnings": [], "failureReports": null}',
            $feedId,
            $status,
            $action,
            Json::encode($feed->getCreationDate()),
            Json::encode($feed->getUpdatedDate()),
            $source,
            $totalRecords,
            $processedRecords,
            $failedRecords
        );
        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($feed));
    }
}
