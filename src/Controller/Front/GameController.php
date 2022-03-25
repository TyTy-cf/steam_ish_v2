<?php

namespace App\Controller\Front;

use App\Entity\Game;
use App\Repository\CommentRepository;
use App\Repository\GameRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game')]
class GameController extends AbstractController
{

    /**
     * GameController constructor.
     * @param GameRepository $gameRepository
     * @param GenreRepository $genreRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        private GameRepository $gameRepository,
        private GenreRepository $genreRepository,
        private CommentRepository $commentRepository
    ) { }

    /**
     * "/" : chemin de la route, c'est le chemin qui sera affiché dans l'URL
     * "game_index" : c'est le nom de la route, on s'en servira pour les redirections (href)
     */
    #[Route('/', name: "game_index")]
    public function index(): Response
    {
        return $this->render('Front/game/index.html.twig', [
            'games' => $this->gameRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    /**
     * @param string $slug
     * @return Response
     * @throws NonUniqueResultException
     */
    #[Route('/{slug}', name: "game_redirect")]
    #[Route('/forum/{slugCateg}/{slugSubCateg}', name: "forum_game_categ")]
    public function gameRedirect(string $slug): Response {
        if (null != $game = $this->gameRepository->findGameBySlug($slug)) {
            return $this->forward(GameController::class . '::show', [
                'game' => $game,
            ]);
        }
        return $this->forward(GameController::class . '::genre', [
            'slug' => $slug,
        ]);
    }

    /**
     * @param Game $game
     * @return Response
     */
    public function show(Game $game): Response
    {
        return $this->render('Front/game/show.html.twig', [
            'game' => $game,
            'relatedGames' => $this->gameRepository->findRelatedGameByGenres($game->getGenres()),
        ]);
    }

    /**
     * @param string $slug
     * @return Response
     * @throws NonUniqueResultException
     */
    #[Route('/{slug}', name: "game_genre")]
    public function genre(string $slug): Response
    {
        $genre = $this->genreRepository->findWithRelations($slug);
        return $this->render('Front/genre/show.html.twig',[
            'genre' => $genre,
            'lastComments' => $this->commentRepository->findByGenre($genre, 8),
        ]);
    }

    /**
     * @param string $slug
     * @return Response
     * @throws NonUniqueResultException
     */
    #[Route('/{slug}/comments', name: "comments_game")]
    public function gameComments(string $slug): Response
    {
        return $this->render('Front/comment/index.html.twig', [
            'game' => $this->gameRepository->findGameBySlug($slug),
        ]);
    }
}
