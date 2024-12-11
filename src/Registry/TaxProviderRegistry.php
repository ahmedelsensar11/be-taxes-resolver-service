<?php
namespace App\Registry;

use App\TaxProviders\BeeTax;
use App\TaxProviders\SeriousTax;

class TaxProviderRegistry
{

    private array $taxProviderMap = [];

    public function __construct()
    {
        // Map tax types to their corresponding classes
        $this->taxProviderMap = [
            'Bee' => BeeTax::class,
            'Serious' => SeriousTax::class,
        ];
    }

    public function getClass(string $type): string
    {
        if (!isset($this->taxProviderMap[$type])) {
            throw new \InvalidArgumentException("Invalid tax provider: $type");
        }

        return $this->taxProviderMap[$type];
    }
}