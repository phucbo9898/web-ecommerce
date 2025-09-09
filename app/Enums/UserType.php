<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserType extends Enum
{
    const ADMIN = 1;
    const SYSTEMADMIN = 2;
    const USER = 3;

    public static function getUserType($role)
    {
        $typeName = [
            self::ADMIN => __('Admin'),
            self::SYSTEMADMIN => __('System Admin'),
            self::USER => __('User'),
        ];
        return $typeName[$role];
    }

    public static function getUserTypeName()
    {
        return [
            self::ADMIN,
            self::SYSTEMADMIN,
            self::USER
        ];
    }
}
