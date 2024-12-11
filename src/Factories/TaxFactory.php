<?php
declare(strict_types=1);
namespace App\Factories;

use App\DTO\TaxDTO;
use App\Interfaces\TaxInterface;
use App\Registry\TaxProviderRegistry;
use App\Service\TaxTypeResolver;

readonly class TaxFactory
{
    public function __construct(
        private TaxTypeResolver     $taxTypeResolver,
        private TaxProviderRegistry $taxProviderRegistry
    )
    {
    }

    public function make(TaxDTO $taxDTO): TaxInterface|\Exception
    {
        //resolve tax provider type
        $type = $this->taxTypeResolver->resolve($taxDTO->country_code);
        // Get the corresponding tax provider class from the registry
        $className = $this->taxProviderRegistry->getClass($type);
        //return new instant from tax class
        return new $className($taxDTO);
    }
}