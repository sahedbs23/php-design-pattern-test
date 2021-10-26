<?php

namespace App\FactoryMethod;

use App\Models\Operation;
use App\Trait\DepositCommissionTrait;
use App\Trait\RoundUpTrait;
use App\Trait\WithdrawCommissionTrait;

class UsdCommissionCalculator implements Contracts\CommissionFactory
{

    use DepositCommissionTrait, WithdrawCommissionTrait, RoundUpTrait;

    private static string $currency = 'USD';

    private Operation $operation;

    private float $commission;


    public function __construct(Operation $operation)
    {
        $this->operation = $operation;
    }

    public function calculateCommission(): int|float
    {
        if ($this->operation->{Operation::OPERATION_TYPE} == 'deposit') {

            return  $this->roundUp($this->depositPercentageCommission());

        }elseif($this->operation->{Operation::OPERATION_TYPE} == 'withdraw') {

            return $this->roundUp($this->withdrawPercentageCommission());

        }else{

            throw new \InvalidArgumentException('Invalid transaction type!');
        }
    }
}
