<?php
namespace App\FactoryMethod\Contracts;

interface CommissionFactory
{
    /**
     * @return int|float
     */
    public function calculateCommission():int|float;

}
