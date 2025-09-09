<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PublishEnum extends Enum
{
    const PUBLISHED = 1;
    const UNPUBLISHED = 2;

    public static function getStatusName($status)
    {
        $typeStatus = [
            1 => __('Đã xuất bản'),
            2 => __('Không công bố')
        ];
        return $typeStatus[$status];
    }
}
