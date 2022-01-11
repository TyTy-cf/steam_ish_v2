<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\CommentRepository;
use App\Repository\GameRepository;
use App\Repository\GenreRepository;
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
    private GenreRepository $genreRepository;
    private CommentRepository $commentRepository;

    /**
     * GameController constructor.
     * @param GameRepository $gameRepository
     * @param GenreRepository $genreRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct(GameRepository $gameRepository, GenreRepository $genreRepository, CommentRepository $commentRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->genreRepository = $genreRepository;
        $this->commentRepository = $commentRepository;
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
     * @Route("/{slug}", name="game_redirect")
     * @param string $slug
     * @return Response
     * @throws NonUniqueResultException
     */
    public function gameRedirect(string $slug): Response {
        if (null != $this->gameRepository->findGameBySlug($slug)) {
            return $this->forward(GameController::class . '::show', [
                'slug' => $slug,
            ]);
        }
        return $this->forward(GameController::class . '::genre', [
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/{slug}", name="game_show")
     * @param string $slug
     * @return Response
     * @throws NonUniqueResultException
     */
    public function show(string $slug): Response
    {
        $game = $this->gameRepository->findGameBySlug($slug);
        return $this->render('game/show.html.twig', [
            'game' => $game,
            'relatedGames' => $this->gameRepository->findRelatedGameByGenres($game->getGenres()),
        ]);
    }

    /**
     * @Route("/{slug}", name="game_genre")
     * @param string $slug
     * @return Response
     * @throws NonUniqueResultException
     */
    public function genre(string $slug): Response
    {
        $genre = $this->genreRepository->findWithRelations($slug);
        return $this->render('genre/show.html.twig',[
            'genre' => $genre,
            'lastComments' => $this->commentRepository->findByGenre($genre, 8),
        ]);
    }

    /**
     * @Route("/{slug}/comments", name="comments_game")
     * @param string $slug
     * @return Response
     * @throws NonUniqueResultException
     */
    public function gameComments(string $slug): Response
    {
        return $this->render('comment/index.html.twig', [
            'game' => $this->gameRepository->findGameBySlug($slug),
        ]);
    }
}
