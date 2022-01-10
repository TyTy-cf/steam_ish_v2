<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreFormType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/genre")
 */
class GenreController extends AbstractController
{

    private EntityManagerInterface $em;
    private GenreRepository $genreRepository;

    /**
     * @param EntityManagerInterface $em
     * @param GenreRepository $genreRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        GenreRepository $genreRepository
    )
    {
        $this->em = $em;
        $this->genreRepository = $genreRepository;
    }

    /**
     * @Route("/", name="genre_index")
     */
    public function index(): Response {
        return $this->render('genre/index.html.twig',[
            'genres' => $this->genreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="genre_create")
     */
    public function genreCreate(Request $request): Response {
        $form = $this->createForm(GenreFormType::class, new Genre());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();
            return $this->redirectToRoute('genre_index');
        }
        return $this->render('genre/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
