<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Private()
 * @method static static Business()
 */
final class UserType extends Enum
{
    const PRIVATE =   'private';
    const BUSINESS =   'business';
}
