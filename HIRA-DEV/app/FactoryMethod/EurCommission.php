<?php

namespace App\FactoryMethod;

use App\FactoryMethod\Contracts\CommissionFactory;
use App\Models\Operation;

class EurCommission extends Commission
{
    private Operation $operation;

    public function __construct(Operation $operation)
    {
        $this->operation = $operation;
    }

    /**
     * @inheritDoc
     */
    public function getCommissionCalculator(): CommissionFactory
    {
        return new EurCommissionCalculator($this->operation);
    }
}
