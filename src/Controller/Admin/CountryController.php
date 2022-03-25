<?php

namespace App\Controller\Admin;

use App\Entity\Country;
use App\Form\CountryFormType;
use App\Repository\CountryRepository;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CountryController
 * @package App\Controller
 */
#[Route('/country')]
class CountryController extends AbstractController
{

    /**
     * CountryController constructor.
     * @param CountryRepository $countryRepository
     * @param EntityManagerInterface $em
     * @param TextService $textService
     */
    public function __construct(
        private CountryRepository $countryRepository,
        private EntityManagerInterface $em,
        private TextService $textService
    ) { }

    #[Route('/', name: "country_index")]
    public function index(): Response
    {
        return $this->render('Admin/country/index.html.twig', [
            'countries' => $this->countryRepository->findAllOrderBy(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/new', name: "country_new")]
    public function new(Request $request): Response
    {
        return $this->createFormFromEntity($request, new Country());
    }

    /**
     * @param Request $request
     * @param Country $country
     * @return Response
     *
     * => $countryRepository->findOneBy(['id' => $request->get('id')]);
     *
     */
    #[Route('/edit/{id}', name: "country_edit")]
    public function edit(Request $request, Country $country): Response
    {
        return $this->createFormFromEntity($request, $country, 'country/edit.html.twig');
    }

    /**
     * @param Request $request
     * @param Country $country
     * @param string $template
     * @return Response
     */
    private function createFormFromEntity(Request $request, Country $country, string $template = 'Admin/country/new.html.twig'): Response
    {
        $form = $this->createForm(CountryFormType::class, $country);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $country->setCode(strtolower($country->getCode()));
            $country->setSlug($this->textService->slugify($country->getName()));
            $country->setUrlFlag('https://flagcdn.com/32x24/'.$country->getCode().'.png');
            $this->em->persist($country); // préparer les requêtes pour la BDD => INSERT ou UPDATE
            $this->em->flush();
            return $this->redirectToRoute('country_index');
        }
        return $this->render($template, [
            'form' => $form->createView(),
        ]);
    }

}
