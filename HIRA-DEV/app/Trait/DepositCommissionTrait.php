<?php

namespace App\Trait;

use App\Models\Operation;
use Illuminate\Support\Facades\Http;

trait DepositCommissionTrait
{
    /**
     * @return false|float
     */
    private function depositPercentageCommission(): float|bool
    {
        $depositPercentage = config("commission.deposit_percentage");


        return ($depositPercentage / 100) * $this->operation->{Operation::OPERATION_AMOUNT};
    }

}
