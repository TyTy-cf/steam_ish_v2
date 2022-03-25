<?php

namespace App\Controller\Front;

use App\Entity\Comment;
use App\Form\CommentFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
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

    #[Route('/new', name: "comment_create")]
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
        return $this->render('Front/comment/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }

}
