<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ActiveHot extends Enum
{
    const YES = 1;
    const NO = 0;

    public static function getHotName($hot)
    {
        $typeHot = [
            self::YES => __('Yes'),
            self::NO => __('No'),
        ];
        return $typeHot[$hot];
    }
}
