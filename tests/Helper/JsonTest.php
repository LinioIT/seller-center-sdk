<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Helper;

use PHPUnit\Framework\TestCase;

final class JsonTest extends TestCase
{
    public function testItEncodesSerializableObjectsWithTheDefaultEscaping(): void
    {
        $data = new class implements \JsonSerializable {
            public function jsonSerialize(): array
            {
                return ['url' => 'https://example.com', 'name' => 'caf' . "\u{00e9}"];
            }
        };

        $this->assertSame('{"url":"https:\/\/example.com","name":"caf\u00e9"}', Json::encode($data));
    }

    public function testItDecodesNestedObjectsAsAssociativeArrays(): void
    {
        $this->assertSame(
            ['items' => [['id' => 1]], 'enabled' => true, 'value' => null],
            Json::decode('{"items":[{"id":1}],"enabled":true,"value":null}')
        );
    }

    /**
     * @dataProvider emptyInputs
     */
    public function testItPreservesEmptyInputHandling(mixed $input): void
    {
        $this->assertNull(Json::decode($input));
    }

    public static function emptyInputs(): array
    {
        return [
            [''],
            ['0'],
            [null],
            [false],
            [0],
            [[]],
        ];
    }

    public function testItPreservesJsonScalarValues(): void
    {
        $this->assertSame(0, Json::decode(' 0 '));
        $this->assertFalse(Json::decode('false'));
        $this->assertNull(Json::decode('null'));
        $this->assertSame('', Json::decode('""'));
    }

    public function testItRejectsResources(): void
    {
        $resource = fopen('php://memory', 'r');

        try {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Resource types cannot be encoded');

            Json::encode($resource);
        } finally {
            fclose($resource);
        }
    }

    public function testItPreservesEncodingErrors(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Invalid JSON: Malformed UTF-8 characters, possibly incorrectly encoded');

        Json::encode("\xB1");
    }

    public function testItRejectsRecursiveData(): void
    {
        $data = [];
        $data['self'] = &$data;

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Invalid JSON: Unknown error');

        Json::encode($data);
    }

    public function testItIncludesInvalidInputInDecodingErrors(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Invalid JSON: Syntax error on 'invalid-json'");

        Json::decode('invalid-json');
    }

    public function testItTruncatesInvalidPayloadsInDecodingErrors(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Invalid JSON: Syntax error on '" . str_repeat('x', 255) . "... (truncated)'");

        Json::decode(str_repeat('x', 256));
    }

    public function testItRejectsExcessivelyNestedJson(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Invalid JSON: Maximum stack depth exceeded');

        Json::decode(str_repeat('[', 512) . '0' . str_repeat(']', 512));
    }
}
