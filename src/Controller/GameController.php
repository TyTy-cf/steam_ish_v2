<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/game")
 */
class GameController extends AbstractController
{

    private GameRepository $gameRepository;

    /**
     * @param GameRepository $gameRepository
     */
    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /**
     * "/" : chemin de la route, c'est le chemin qui sera affiché dans l'URL
     * "game_index" : c'est le nom de la route, on s'en servira pour les redirections (href)
     *
     * @Route("/", name="game_index")
     */
    public function index(): Response
    {
        return $this->render('game/index.html.twig', [
            'games' => $this->gameRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    /**
     * @Route("/detail/{slug}", name="game_show")
     * @throws NonUniqueResultException
     */
    public function show(string $slug): Response
    {
        $game = $this->gameRepository->findGameBySlug($slug);
        return $this->render('game/show.html.twig', [
            'game' => $game,
        ]);
    }
}
