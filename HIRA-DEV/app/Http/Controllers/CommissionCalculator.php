<?php

namespace App\Http\Controllers;

use App\DataSource\OperationData;
use App\FactoryMethod\EurCommission;
use App\FactoryMethod\EurCommissionCalculator;
use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

class CommissionCalculator extends Controller
{
    public function csvImport(Request $request)
    {
        return view('csv.import');
    }

    /**
     * @throws ReflectionException
     */
    public function calculate(Request $request)
    {
        $File = $request->file('file');
        $arrResult = self::processFile($File);
        $col = collect($arrResult);
        OperationData::put($col);
        foreach ($col as $operation):
            $class = Str::ucfirst(Str::lower($operation->{Operation::OPERATION_CURRENCY})).'Commission';
            $reflectionClass = new ReflectionClass("\App\FactoryMethod\\".$class);
            $calculator = $reflectionClass->newInstanceArgs([$operation]);
            echo nl2br($calculator->calculate().PHP_EOL);
        endforeach;

        OperationData::flush();

    }

    public static function processFile(string $File): \Illuminate\Support\Collection
    {
        $arrResult  = collect(array());
        $handle     = fopen($File, "r");
        $x = 0;
        if(empty($handle) === false) {
            while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
                $csv = new Operation;
                $csv->operation_date = $data[0];
                $csv->user_id = $data[1];
                $csv->user_type = $data[2];
                $csv->operation_type = $data[3];
                $csv->operation_amount = $data[4];
                $csv->operation_currency = $data[5];
                $arrResult[$x] = $csv;
                $x++;
            }
            fclose($handle);
        }
        return $arrResult;
    }
}
