<?php
namespace App\Trait;

trait RoundUpTrait {

    public function roundUp ( $value, $precision = 2 ): float|int
    {
        $pow = pow ( 10, $precision );
        return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow;
    }
}
