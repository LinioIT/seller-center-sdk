<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Formatter;

final class LogMessageFormatter
{
    const TYPE_REQUEST = 'Request';
    const TYPE_RESPONSE = 'RequestResponse';
    const TYPE_BUILT_RESPONSE = 'BuiltResponse';
    const TYPE_FACTORY = 'XmlFactoryStructureValidation';

    public static function fromAction(
        string $id,
        string $action,
        string $type,
        string $application = 'SellerCenterSdk'
    ): string {
        return sprintf('%s::%s::%s::%s', $id, $action, $type, $application);
    }

    public static function fromFactory(
        string $factory,
        string $type,
        string $application = 'SellerCenterSdk'
    ): string {
        return sprintf('%s::%s::%s', $factory, $type, $application);
    }
}
