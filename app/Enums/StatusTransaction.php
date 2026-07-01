<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class StatusTransaction extends Enum
{
    CONST PENDING = 1;
    CONST PROCESSING = 2;
    CONST COMPLETED = 3;
    CONST SHIPPING = 4;
    CONST CANCELED = 5;

    public static function getStatusTransaction($status)
    {
        $defineStatus = [
            'pending',
            'processing',
            'shipping',
            'completed',
            'canceled'
        ];

        return $defineStatus[$status];
    }
}
