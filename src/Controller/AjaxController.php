<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ajax')]
class AjaxController extends AbstractController
{

    /**
     * @param GameRepository $gameRepository
     */
    public function __construct(private GameRepository $gameRepository) { }

    #[Route('/game/findAllNames/{name}', name: "ajax_game_find_all_names")]
    public function index(string $name): JsonResponse
    {
        return new JsonResponse($this->gameRepository->findAllNames($name));
    }
}
