<?php

namespace App\Service;

use App\Enum\TaxTypeEnum;
use App\Helpers\TaxHelper;

class TaxTypeResolver
{
    public function resolve(string $countryCode): string
    {
        $countryCode = strtoupper($countryCode);
        if (in_array($countryCode, TaxHelper::BEE_SUPPORTED_COUNTRIES, true)) {
            return TaxTypeEnum::BEE->value;
        }

        if (in_array($countryCode, TaxHelper::SERIOUS_SUPPORTED_COUNTRIES, true)) {
            return TaxTypeEnum::SERIOUS->value;
        }

        throw new \InvalidArgumentException("No tax provider available for country: $countryCode");
    }
}