<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function index(GameRepository $gameRepository): Response
    {
        dump($gameRepository->findLastGames(10, true));
        dump($gameRepository->findLastGames(4));
        return $this->render('home/index.html.twig', [
            'alphaGame' => $gameRepository->findLastGames(10, true),
            'lastPublishedGames' => $gameRepository->findLastGames(4),
        ]);
    }

}
