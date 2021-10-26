<?php

namespace App\FactoryMethod;

use App\FactoryMethod\Contracts\CommissionFactory;
use App\Models\Operation;

abstract class Commission
{

    /**
     * @return CommissionFactory
     */
    abstract public function getCommissionCalculator(): CommissionFactory;

    /**
     *
     */
    public function calculate(): float|int
    {
        $calculator = $this->getCommissionCalculator();
        return$calculator->calculateCommission();
    }
}
