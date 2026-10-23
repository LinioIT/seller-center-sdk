<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Formatter;

final class LogMessageFormatter
{
    public const TYPE_REQUEST = 'RequestResponse';
    public const TYPE_BUILT_RESPONSE = 'BuiltResponse';
    public const TYPE_FACTORY = 'XmlFactoryStructureValidation';

    public static function fromAction(
        string $id,
        string $action,
        string $type,
        string $application = 'SellerCenterSdk',
    ): string {
        return sprintf('%s::%s::%s::%s', $id, $action, $type, $application);
    }

    public static function fromFactory(
        string $factory,
        string $type,
        string $application = 'SellerCenterSdk',
    ): string {
        return sprintf('%s::%s::%s', $factory, $type, $application);
    }
}
