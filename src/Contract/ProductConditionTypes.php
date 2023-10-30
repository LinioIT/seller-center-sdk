<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Contract;

interface ProductConditionTypes
{
    public const CONDITION_NEW = 'Nuevo';
    public const CONDITION_NEW_WITH_DETAIL = 'Sin uso con detalle estético';
    public const CONDITION_OPEN_BOX = 'Open Box';
    public const CONDITION_RECONDITIONED = 'Reacondicionado';
    public const CONDITION_RECONDITIONED_EXCELLENT = 'Reacondicionado excelente (A)';
    public const CONDITION_RECONDITIONED_WITH_DETAIL = 'Reacondicionado detalle estético (B)';
    public const CONDITION_SECOND_HAND_NEW = 'Segunda mano nuevo con etiqueta';
    public const CONDITION_SECOND_HAND_AS_NEW = 'Segunda mano como nuevo';
    public const CONDITION_SECOND_HAND_WITH_DETAIL = 'Segunda mano con detalles';

    public const CONDITION_TYPES = [
        self::CONDITION_NEW,
        self::CONDITION_NEW_WITH_DETAIL,
        self::CONDITION_OPEN_BOX,
        self::CONDITION_RECONDITIONED,
        self::CONDITION_RECONDITIONED_EXCELLENT,
        self::CONDITION_RECONDITIONED_WITH_DETAIL,
        self::CONDITION_SECOND_HAND_NEW,
        self::CONDITION_SECOND_HAND_AS_NEW,
        self::CONDITION_SECOND_HAND_WITH_DETAIL,
    ];
}
