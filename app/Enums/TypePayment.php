<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TypePayment extends Enum
{
    CONST VNPAY = 1;
    CONST MOMO = 2;
    CONST NORMAL = 3;
}
