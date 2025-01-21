<?php

namespace App\Controller;

use App\Repository\CubesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CubesRepository $cubesRepository): Response
    {

        $lastestCubeById = $cubesRepository->findLastCubeId();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            "lastestCubeById" => $lastestCubeById,
        ]);
    }
}
