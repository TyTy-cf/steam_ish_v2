<?php

namespace App\Controller\Admin;

use App\Entity\Publisher;
use App\Form\PublisherFormType;
use App\Repository\PublisherRepository;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/publisher')]
class PublisherController extends AbstractController
{

    /**
     * PublisherController constructor.
     * @param PublisherRepository $publisherRepository
     * @param EntityManagerInterface $em
     * @param TextService $textService
     */
    public function __construct(
        private PublisherRepository $publisherRepository,
        private EntityManagerInterface $em,
        private TextService $textService
    ) { }

    #[Route('/', name: "publisher_index")]
    public function index(): Response
    {
        return $this->render('Admin/publisher/index.html.twig', [
            'publishers' => $this->publisherRepository->findAllRelations(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/new', name: "publisher_new")]
    public function new(Request $request): Response
    {
        return $this->createFormFromEntity($request, new Publisher());
    }

    /**
     * @param Request $request
     * @param Publisher $publisher
     * @return Response
     *
     * => $publisherRepository->findOneBy(['slug' => $request->get('slug')]);
     */
    #[Route('/edit/{slug}', name: "publisher_edit")]
    public function edit(Request $request, Publisher $publisher): Response
    {
        return $this->createFormFromEntity($request, $publisher, 'Admin/publisher/edit.html.twig');
    }

    /**
     * @param Request $request
     * @param Publisher $publisher
     * @param string $template
     * @return Response
     */
    private function createFormFromEntity(Request $request, Publisher $publisher, string $template = 'Admin/publisher/new.html.twig'): Response
    {
        $form = $this->createForm(PublisherFormType::class, $publisher);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($publisher); // préparer les requêtes pour la BDD => INSERT ou UPDATE
            $this->em->flush();
            return $this->redirectToRoute('publisher_index');
        }
        return $this->render($template, [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Publisher $publisher
     * @return Response
     */
    #[Route('/delete/{slug}', name: "publisher_delete")]
    public function delete(Publisher $publisher): Response
    {
        $this->em->remove($publisher); // => DELETE FROM publisher WHERE slug = $publisher->getSlug()
        $this->em->flush();
        return $this->redirectToRoute('publisher_index');
    }
}
