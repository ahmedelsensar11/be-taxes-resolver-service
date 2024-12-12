<?php

namespace App\Controller;

use App\Cache\FileCache;
use App\DTO\TaxDTO;
use App\Factories\TaxFactory;
use App\Helpers\JsonResponseHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

readonly class TaxesController
{
    public TaxFactory $taxFactory;
    public function __construct(TaxFactory $taxFactory)
    {
        $this->taxFactory = $taxFactory;
    }

    #[Route('/taxes')]
    public function getTaxes(Request $request, FileCache $cache): Response
    {
        try {
            //validate request data
            //todo refactor it into request validation layer
            $country = $request->get('country') ?? '';
            $state = $request->get('state') ?? '';
            $city = $request->get('city') ?? '';
            $street = $request->get('street') ?? '';
            $postcode = $request->get('postcode') ?? '';
            //resolve taxes info
            $dto = new TaxDTO(country_code: $country, state: $state, city: $city, street: $street, postcode: $postcode);
            $taxProvider = $this->taxFactory->make($dto);
            $taxProvider->setCache($cache);
            $taxProvider->getTaxesInfo();
            return JsonResponseHelper::success($taxProvider->responseDTO->message, $taxProvider->responseDTO->data);
        } catch (\Exception $e){
            return JsonResponseHelper::error($e->getMessage());
        }
    }
}
