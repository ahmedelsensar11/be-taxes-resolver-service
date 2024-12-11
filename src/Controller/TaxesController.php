<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

readonly class TaxesController
{
    #[Route('/taxes')]
    public function getTaxes(Request $request): Response
    {
        $country = $request->get('country');
        $state = $request->get('state');

        return new JsonResponse(['message' => 'implement me']);
    }
}
