<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HealthCheckController
{
    #[Route('/', name: 'health_check')]
    public function healthCheck(): Response
    {
        return new Response('OK', 200);
    }
}