<?php
namespace App\FactoryMethod\Contracts;

interface CommissionFactory
{
    /**
     * @return int|float
     */
    public function calculateCommission():int|float;

//    /**
//     * @return int|float
//     */
//    public function convertToEur():int|float;
//
//    /**
//     * @return int|float
//     */
//    public function convertFromEur():int|float;
}
