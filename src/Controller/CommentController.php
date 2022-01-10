<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\GameRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("game/{slug}/comments", name="comments_game")
     * @throws NonUniqueResultException
     */
    public function index(
        GameRepository $gameRepository,
        string $slug
    ): Response
    {
        return $this->render('comment/index.html.twig', [
            'game' => $gameRepository->findGameBySlug($slug),
        ]);
    }
}
