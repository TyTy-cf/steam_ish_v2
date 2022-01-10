<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\GameRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
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

    /**
     * @Route("comment/new", name="comment_create")
     */
    public function genreCreate(Request $request): Response {
        $form = $this->createForm(CommentFormType::class, new Comment());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            /** @var Comment $comment */
            $comment->setCreatedAt(new DateTime());
            $this->em->persist($form->getData());
            $this->em->flush();
            return $this->redirectToRoute('game_index');
        }
        return $this->render('comment/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }

}
