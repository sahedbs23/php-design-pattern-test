<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Deposit()
 * @method static static Withdraw()
 */
final class OperationType extends Enum
{
    const DEPOSIT =   'deposit';
    const WITHDRAW =   'withdraw';
}
