<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AfiController extends AbstractController
{
    #[Route('/Afi', name: 'app_afi')]
    public function index(): Response
    {
        return $this->render('afi/index.html.twig', [
            'controller_name' => 'AfiController',
        ]);
    }
}
