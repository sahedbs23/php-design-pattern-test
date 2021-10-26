<?php

namespace App\Trait;

use App\DataSource\OperationData;
use App\Models\Operation;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

trait WithdrawCommissionTrait
{

    private static string $deposits = 'deposits';

    private static string $ClientTypePrivate = 'private';

    private static string $ClientTypeBusiness= 'business';

    /**
     * @return false|float
     */
    private function withdrawPercentageCommission(): float|bool
    {
        if ($this->operation->{Operation::USER_TYPE} === self::$ClientTypePrivate) {

            return $this->withdrawPercentageCommissionPrivate();

        }elseif ($this->operation->{Operation::USER_TYPE} === self::$ClientTypeBusiness) {

            return $this->withdrawPercentageCommissionBusiness();

        }else{

            throw new \InvalidArgumentException('Invalid client type!');
        }

    }

    /**
     * @return false|float
     */
    private function withdrawPercentageCommissionPrivate(): float|bool
    {
        $withdrawPercentagePrivate= config("commission.withdraw_percentage_private_regular");
        $withdrawWeekLimit = config("commission.withdraw_week_limit");

        $OperationDateCarbon = Carbon::parse($this->operation->{Operation::OPERATION_DATE});

        $dateFrom = $OperationDateCarbon->startOfWeek()->format('Y-m-d');
//        $dateTo = $OperationDateCarbon->endOfWeek()->format('Y-m-d');

        $OperationCollection = OperationData::getOperationData();

        $filteredCollection = $OperationCollection->where('user_id',$this->operation->{Operation::USER_ID})
            ->whereBetween('operation_date',[$dateFrom, $this->operation->{Operation::OPERATION_DATE}])
            ->where(Operation::OPERATION_TYPE,$this->operation->{Operation::OPERATION_TYPE})
            ->sortBy(Operation::OPERATION_DATE);



        if ($filteredCollection->count() > 3) {
            return ($withdrawPercentagePrivate / 100) * $this->operation->{Operation::OPERATION_AMOUNT};
        }

        $totalTransOfWeek = 0;
        foreach ($filteredCollection as $col):
            $totalTransOfWeek = $totalTransOfWeek + ($col->{Operation::OPERATION_AMOUNT} / $col->{Operation::CONVERSATION_RATE});
        endforeach;

        $crossedLimit = $totalTransOfWeek - $withdrawWeekLimit;

        if ($crossedLimit <= 0) {
            return 0.0;
        }

        if ($filteredCollection->count() <= 1){
            return ($withdrawPercentagePrivate / 100) * ($crossedLimit * $this->operation->{Operation::CONVERSATION_RATE});
        }

        $filteredCollection->pop();

        $sumWithOutCurrentOption = 0;
        foreach ($filteredCollection as $col):
            $sumWithOutCurrentOption = $sumWithOutCurrentOption + ($col->{Operation::OPERATION_AMOUNT} / $col->{Operation::CONVERSATION_RATE});
        endforeach;

        if ($sumWithOutCurrentOption > $withdrawWeekLimit){
            return ($withdrawPercentagePrivate / 100) * $this->operation->{Operation::OPERATION_AMOUNT} ;
        }


        $crossedLimit = $crossedLimit + $this->operation->{Operation::OPERATION_AMOUNT};

        return ($withdrawPercentagePrivate / 100) * ($crossedLimit * $this->operation->{Operation::CONVERSATION_RATE}) ;
    }

    /**
     * @return false|float
     */
    private function withdrawPercentageCommissionBusiness(): float|bool
    {
        $withdrawPercentageBusiness = config("commission.withdraw_percentage_business");

        return ($withdrawPercentageBusiness / 100) * $this->operation->{Operation::OPERATION_AMOUNT};
    }

    private function calculateCommissionInLocalCurrency(Operation $operation,$withdrawPercentageBusiness): float|int
    {
        return ($withdrawPercentageBusiness / 100) * $this->operation->{Operation::OPERATION_AMOUNT};
    }
}
