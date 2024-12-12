<?php

namespace App\DTO;


class TaxDTO
{

    public function __construct(
        public string $country_code,
        public string $state,
        public string $city,
        public string $street,
        public string $postcode,
    )
    {
    }

}
