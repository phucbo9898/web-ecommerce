<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ActiveStatus extends Enum
{
    const ACTIVE = 1;
    const INACTIVE = 2;

    public static function getStatusName($status)
    {
        $typeStatus = [
            self::ACTIVE => __('Công khai'),
            self::INACTIVE => __('Riêng tư'),
        ];
        return $typeStatus[$status];
    }
}
