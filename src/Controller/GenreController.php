<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreFormType;
use App\Repository\CommentRepository;
use App\Repository\GenreRepository;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
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
    private TextService $textService;

    /**
     * @param EntityManagerInterface $em
     * @param GenreRepository $genreRepository
     * @param TextService $textService
     */
    public function __construct(
        EntityManagerInterface $em,
        GenreRepository $genreRepository,
        TextService $textService
    )
    {
        $this->em = $em;
        $this->textService = $textService;
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
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response {
        return $this->createFormFromEntity($request, new Genre(), 'genre/new.html.twig');
    }

    /**
     * @Route("/{slug}", name="genre_show")
     * @param CommentRepository $commentRepository
     * @param string $slug
     * @return Response
     * @throws NonUniqueResultException
     */
    public function show(CommentRepository $commentRepository, string $slug): Response {
        $genre = $this->genreRepository->findWithRelations($slug);
        return $this->render('genre/show.html.twig',[
            'genre' => $genre,
            'lastComments' => $commentRepository->findByGenre($genre, 8),
        ]);
    }

    /**
     * @Route("/edit/{slug}", name="genre_edit")
     * @param Request $request
     * @param Genre $genre
     * @return Response
     */
    public function edit(Request $request, Genre $genre): Response {
        return $this->createFormFromEntity($request, $genre, 'genre/edit.html.twig');
    }

    /**
     * @param Request $request
     * @param Genre $genre
     * @param string $template
     * @return Response
     */
    private function createFormFromEntity(Request $request, Genre $genre, string $template): Response {
        $form = $this->createForm(GenreFormType::class, $genre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $genre->setSlug($this->textService->slugify($genre->getName()));
            $this->em->persist($genre);
            $this->em->flush();
            return $this->redirectToRoute('genre_index');
        }
        return $this->render($template,[
            'form' => $form->createView(),
        ]);
    }
}
