<?php

namespace App\DataSource;

use App\FactoryMethod\CurrencyConverter;
use App\Models\Operation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class OperationData
{
    /**
     * @param Collection $collection
     */
    public static function put(Collection $collection)
    {
        $dataExists = [];
        $collection->map(function ($item) use ($dataExists){
            $key = $item->{Operation::OPERATION_DATE}.'_'.Operation::OPERATION_CURRENCY;
            $dataExists[$key] = self::conversionRateCalculate($item);
            $item->{Operation::CONVERSATION_RATE} = $dataExists[$key] ?? self::conversionRateCalculate($item);
            return $item;
        });
        Session::put('operation_data',$collection);
    }

    /**
     * @return mixed
     */
    public static function getOperationData(): mixed
    {
        return Session::get('operation_data');
    }

    public static function flush()
    {
        Session::flash('operation_data');
    }

    private static function conversionRateCalculate(Operation $operation)
    {
        if ($operation->{Operation::OPERATION_CURRENCY} === 'EUR'){
            return 1;
        }
        $converter = new CurrencyConverter($operation->{Operation::OPERATION_AMOUNT},$operation->{Operation::OPERATION_CURRENCY}, 'EUR',$operation->{Operation::OPERATION_DATE});

        $ratesCol= $converter->convert()->toCollection();

        $rates = collect($ratesCol->get('rates'));

        return $rates->get($operation->{Operation::OPERATION_CURRENCY});

    }
}
