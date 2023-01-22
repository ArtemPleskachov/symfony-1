<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController
{
    #[Route('/api')]
    public function apiDocAction()
    {
        return new JsonResponse(
          ['Hello Api Controller']
        );
    }
}