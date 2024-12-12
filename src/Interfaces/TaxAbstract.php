<?php

namespace App\Interfaces;

use App\Cache\FileCache;
use App\DTO\ResponseDTO;
use App\DTO\TaxDTO;

abstract class TaxAbstract
{
    public ResponseDTO $responseDTO;
    protected ?FileCache $cache = null;

    public function __construct(
        public TaxDTO $taxDTO
    )
    {
        $this->taxDTO->country_code = strtoupper($this->taxDTO->country_code);
        $this->responseDTO= new ResponseDTO();
    }

    public function setCache(FileCache $cache): void
    {
        $this->cache = $cache;
    }

    protected function generateCacheKey(): string
    {
        $class_short_name = (new \ReflectionClass($this))->getShortName();
        return $class_short_name . '_' . $this->taxDTO->country_code . '_' . $this->taxDTO->state;
    }
}
