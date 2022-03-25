<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherFormType;
use App\Repository\PublisherRepository;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/publisher")
 */
class PublisherController extends AbstractController
{

    private PublisherRepository $publisherRepository;
    private EntityManagerInterface $em;
    private TextService $textService;

    /**
     * PublisherController constructor.
     * @param PublisherRepository $publisherRepository
     * @param EntityManagerInterface $em
     * @param TextService $textService
     */
    public function __construct(PublisherRepository $publisherRepository, EntityManagerInterface $em, TextService $textService)
    {
        $this->publisherRepository = $publisherRepository;
        $this->em = $em;
        $this->textService = $textService;
    }

    /**
     * @Route("/", name="publisher_index")
     */
    public function index(): Response
    {
        return $this->render('publisher/index.html.twig', [
            'publishers' => $this->publisherRepository->findAllRelations(),
        ]);
    }

    /**
     * @Route("/new", name="publisher_new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        return $this->createFormFromEntity($request, new Publisher());
    }

    /**
     * @Route("/edit/{slug}", name="publisher_edit")
     * @param Request $request
     * @param Publisher $publisher
     * @return Response
     *
     * => $publisherRepository->findOneBy(['slug' => $request->get('slug')]);
     */
    public function edit(Request $request, Publisher $publisher): Response
    {
        return $this->createFormFromEntity($request, $publisher, 'publisher/edit.html.twig');
    }

    /**
     * @param Request $request
     * @param Publisher $publisher
     * @param string $template
     * @return Response
     */
    private function createFormFromEntity(Request $request, Publisher $publisher, string $template = 'publisher/new.html.twig'): Response
    {
        $form = $this->createForm(PublisherFormType::class, $publisher);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $publisher->setSlug($this->textService->slugify($publisher->getName()));
            $this->em->persist($publisher); // préparer les requêtes pour la BDD => INSERT ou UPDATE
            $this->em->flush();
            return $this->redirectToRoute('publisher_index');
        }
        return $this->render($template, [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{slug}", name="publisher_delete")
     * @param Publisher $publisher
     * @return Response
     */
    public function delete(Publisher $publisher): Response
    {
        $this->em->remove($publisher); // => DELETE FROM publisher WHERE slug = $publisher->getSlug()
        $this->em->flush();
        return $this->redirectToRoute('publisher_index');
    }
}
