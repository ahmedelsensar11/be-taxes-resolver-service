<?php
namespace App\TaxProviders;
use AllowDynamicProperties;
use App\DTO\TaxDTO;
use App\ExternalService\SeriousTax\Location;
use App\ExternalService\SeriousTax\SeriousTaxService;
use App\ExternalService\TaxBee\TaxResult;
use App\ExternalService\TaxBee\TaxResultType;
use App\Interfaces\TaxAbstract;
use App\Interfaces\TaxInterface;

#[AllowDynamicProperties]
class SeriousTax extends TaxAbstract implements TaxInterface
{
    public function __construct(TaxDTO $taxDTO)
    {
        parent::__construct($taxDTO);
        $this->seriousTax = new SeriousTaxService();
        $this->location = new Location($this->taxDTO->country_code, $this->taxDTO->state);
    }

    public function getTaxesInfo(): void
    {
        try {
            $cacheKey = $this->generateCacheKey();
            // Try to get data from cache first
            $cachedData = $this->cache->get($cacheKey);
            if ($cachedData !== null) {
                $this->responseDTO->data = $cachedData;
                return;
            }

            //get taxes
            $taxRate = $this->seriousTax->getTaxesResult($this->location);
            $taxData = [new TaxResult(TaxResultType::VAT, $taxRate)];

            // Store in cache for future use (cache for 24 hours)
            $this->cache->set($cacheKey, $taxData, 86400);

            $this->responseDTO->data = $taxData;
        }catch (\Exception $e) {
            $this->responseDTO->status = false;
            $this->responseDTO->message = $e->getMessage();
        }
    }
}