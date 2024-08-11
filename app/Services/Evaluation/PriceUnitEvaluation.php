<?php

namespace App\Services\Evaluation;

class PriceUnitEvaluation
{
    protected array $priceUnits;

    public function __construct()
    {
        $this->priceUnits = [];
    }

    public function addPriceUnit($priceUnit): void
    {
        $this->priceUnits[] = $priceUnit;
    }

    public function getPriceUnits(): array
    {
        return $this->priceUnits;
    }

    public function evaluate(string $type): float
    {
        $result = 0;
        if ($this->getCount() == 0) {
            return $result;
        }
        switch ($type) {
            case 'min':
                $result = min($this->priceUnits);
                break;
            case 'max':
                $result = max($this->priceUnits);
                break;
            case 'avg':
                $result = array_sum($this->priceUnits) / count($this->priceUnits);
                break;
            default:
                break;
        }
        return $result;
    }


    public function getCount(): int
    {
        return count($this->priceUnits);
    }
}
