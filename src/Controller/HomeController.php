<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function index(
        GameRepository $gameRepository,
        CommentRepository $commentRepository
    ): Response
    {
        return $this->render('home/index.html.twig', [
            'alphaGame' => $gameRepository->findLastGames(10, true),
            'lastPublishedGames' => $gameRepository->findLastGames(4),
            'lastComments' => $commentRepository->findCommentsByLimit(5),
            'mostPlayedGame' => $gameRepository->findMostPlayedGame(10),
        ]);
    }

}
