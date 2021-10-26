<?php

namespace App\FactoryMethod;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class CurrencyConverter
{
    private int $amount;

    private string $conversionDate;

    private object $rates;

    private string $convertFrom;

    private string $convertTo;

    /**
     * @return string
     */
    public function getConversionDate(): string
    {
        return $this->conversionDate;
    }

    /**
     * @param string $conversionDate
     */
    public function setConversionDate(string $conversionDate): void
    {
        $this->conversionDate = $conversionDate;
    }


    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getConvertFrom(): string
    {
        return $this->convertFrom;
    }

    /**
     * @param string $convertFrom
     */
    public function setConvertFrom(string $convertFrom): void
    {
        $this->convertFrom = $convertFrom;
    }

    /**
     * @return string
     */
    public function getConvertTo(): string
    {
        return $this->convertTo;
    }

    /**
     * @param string $convertTo
     */
    public function setConvertTo(string $convertTo): void
    {
        $this->convertTo = $convertTo;
    }


    /**
     * @param int $amount
     * @param string $convertFrom
     * @param string $convertTo
     * @param string|null $conversionDate
     */
    public function __construct(int $amount, string $convertFrom, string $convertTo, string $conversionDate = null)
    {
        $this->amount = $amount;

        $this->conversionDate = $conversionDate ?? date('Y-m-d');

        $this->convertFrom = $convertFrom;

        $this->convertTo = $convertTo;
    }

    /**
     * @return $this
     * @throws RequestException
     */
    public function convert(): static
    {
        $access_key = config('commission.access_key');

        $this->rates = Http::get("http://api.exchangeratesapi.io/v1/$this->conversionDate",[
            "access_key" => $access_key,
            "format" => 1
        ]);

        if ($this->rates->failed()){

            $this->rates->throw();

        }

        return $this;

    }

    /**
     * @return mixed
     */
    public function toJson(): mixed
    {
        return $this->rates->json();
    }

    /**
     * @return mixed
     */
    public function toCollection(): mixed
    {
        return $this->rates->collect();
    }
}
