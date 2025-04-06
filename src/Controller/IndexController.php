<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CatComida;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/')]
class IndexController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $categorias = $this->entityManager->getRepository(CatComida::class)->findAll();
        // dump($categorias);
        // die;
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'categorias' => $categorias,
        ]);
    }
    #[Route('/servicios', name: 'app_index_servicios')]
    public function servicios(): Response{
        return $this->render('index/servicios.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
