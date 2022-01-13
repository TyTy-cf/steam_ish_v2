<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameFormType;
use App\Repository\GameRepository;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/game")
 *
 * Class AdminGameController
 * @package App\Controller
 */
class AdminGameController extends AbstractController
{

    private GameRepository $gameRepository;
    private EntityManagerInterface $em;
    private TextService $textService;

    /**
     * AdminGameController constructor.
     * @param GameRepository $gameRepository
     * @param EntityManagerInterface $em
     * @param TextService $textService
     */
    public function __construct(GameRepository $gameRepository, EntityManagerInterface $em, TextService $textService)
    {
        $this->gameRepository = $gameRepository;
        $this->em = $em;
        $this->textService = $textService;
    }

    /**
     * @Route("/", name="admin_game_index")
     */
    public function index(): Response
    {
        return $this->render('admin_game/index.html.twig', [
            'controller_name' => 'AdminGameController',
        ]);
    }

    /**
     * @Route("/new", name="admin_game_new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        return $this->createFormFromEntity($request, new Game());
    }

    /**
     * @param Request $request
     * @param Game $game
     * @param string $template
     * @return Response
     */
    private function createFormFromEntity(Request $request, Game $game, string $template = 'game/admin/new.html.twig'): Response
    {
        $form = $this->createForm(GameFormType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $game->setSlug($this->textService->slugify($game->getName()));
            $this->em->persist($game); // préparer les requêtes pour la BDD => INSERT ou UPDATE
            $this->em->flush();
            return $this->redirectToRoute('admin_game_index');
        }
        return $this->render($template, [
            'form' => $form->createView(),
        ]);
    }
}
