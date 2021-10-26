<?php

namespace Tests\Unit;

use App\DataSource\OperationData;
use App\FactoryMethod\Commission;
use App\FactoryMethod\EurCommission;
use App\Http\Controllers\CommissionCalculator;
use App\Models\Operation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use ReflectionClass;
use Tests\TestCase;

class OperationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_operation()
    {
        $uploadedFile = new UploadedFile(
            resource_path('test-files/input.csv'),
            'input.csv',
            'text/csv',
            null,
            true
        );

        $arrResult = CommissionCalculator::processFile($uploadedFile->getPathname());

        $col = collect($arrResult);
        OperationData::put($col);

        $opration = $col->get('0');

        $eurCalculator = new EurCommission($opration);
        $expectedResult = $eurCalculator->calculate();
        $this->assertEquals(0.6, $expectedResult);


        $opration = $col->get('3');

        $eurCalculator = new EurCommission($opration);
        $expectedResult = $eurCalculator->calculate();
        $this->assertEquals(0.06, $expectedResult);

        foreach ($col as $operation):
            $class = Str::ucfirst(Str::lower($operation->{Operation::OPERATION_CURRENCY})).'Commission';
            $reflectionClass = new ReflectionClass("\App\FactoryMethod\\".$class);
            $calculator = $reflectionClass->newInstanceArgs([$operation]);
            $this->assertInstanceOf(Commission::class,$calculator);
            $this->assertIsFloat($calculator->calculate());
        endforeach;

        OperationData::flush();

    }
}
