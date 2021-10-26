<?php

namespace App\Models;

use App\FactoryMethod\CurrencyConverter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\RequestException;

class Operation extends Model
{
    const OPERATION_DATE = 'operation_date';

    const USER_ID = 'user_id';

    const USER_TYPE = 'user_type';

    const OPERATION_TYPE = 'operation_type';

    const OPERATION_AMOUNT = 'operation_amount';

    const OPERATION_CURRENCY = 'operation_currency';

    const CONVERSATION_RATE = 'conversation_rate';

    protected $fillable = [
        self::OPERATION_DATE,
        self::USER_ID,
        self::USER_TYPE,
        self::OPERATION_TYPE,
        self::OPERATION_AMOUNT,
        self::OPERATION_CURRENCY,
        self::CONVERSATION_RATE,
    ];

}
