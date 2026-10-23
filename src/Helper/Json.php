<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Helper;

final class Json
{
    public static function encode(mixed $data): string
    {
        if (is_resource($data)) {
            throw new \InvalidArgumentException('Resource types cannot be encoded');
        }

        try {
            return json_encode($data, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new \LogicException(self::getErrorMessage($exception->getCode()), 0, $exception);
        }
    }

    public static function decode(mixed $data): mixed
    {
        // Preserve the previous helper's handling of empty input, including "0".
        if (empty($data)) {
            return null;
        }

        try {
            return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            $payload = substr($data, 0, 255);

            if (strlen($data) > 255) {
                $payload .= '... (truncated)';
            }

            throw new \LogicException(sprintf('%s on \'%s\'', self::getErrorMessage($exception->getCode()), $payload), 0, $exception);
        }
    }

    private static function getErrorMessage(int $code): string
    {
        return match ($code) {
            JSON_ERROR_DEPTH => 'Invalid JSON: Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'Invalid JSON: Underflow or modes mismatch',
            JSON_ERROR_CTRL_CHAR => 'Invalid JSON: Unexpected control character found',
            JSON_ERROR_SYNTAX => 'Invalid JSON: Syntax error',
            JSON_ERROR_UTF8 => 'Invalid JSON: Malformed UTF-8 characters, possibly incorrectly encoded',
            default => 'Invalid JSON: Unknown error',
        };
    }
}
