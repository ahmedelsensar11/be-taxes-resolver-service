<?php
namespace App\TaxProviders;
use AllowDynamicProperties;
use App\DTO\TaxDTO;
use App\ExternalService\TaxBee\TaxBee;
use App\Interfaces\TaxAbstract;
use App\Interfaces\TaxInterface;

#[AllowDynamicProperties]
class BeeTax extends TaxAbstract implements TaxInterface
{
    public function __construct(TaxDTO $taxDTO)
    {
        parent::__construct($taxDTO);
        $this->taxBee = new TaxBee();
    }

    public function getTaxesIfo(): void
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
            $taxData = $this->taxBee->getTaxes(
                $this->taxDTO->country_code,
                $this->taxDTO->state,
                $this->taxDTO->city,
                $this->taxDTO->street,
                $this->taxDTO->postcode
            );
            // Store in cache for future use (cache for 24 hours)
            $this->cache->set($cacheKey, $taxData, 86400);

            $this->responseDTO->data = $taxData;
        } catch (\Exception $e) {
            $this->responseDTO->status = false;
            $this->responseDTO->message = $e->getMessage();
        }
    }


}