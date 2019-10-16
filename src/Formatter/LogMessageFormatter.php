<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Formatter;

final class LogMessageFormatter
{
    const TYPE_REQUEST = 'Request';
    const TYPE_RESPONSE = 'RequestResponse';
    const TYPE_BUILT_RESPONSE = 'BuiltResponse';

    public static function fromAction(
        string $id,
        string $action,
        string $type,
        string $application = 'SellerCenterSdk'
    ): string {
        return sprintf('%s::%s::%s::%s', $id, $action, $type, $application);
    }
}
