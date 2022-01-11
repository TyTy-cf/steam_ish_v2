<?php

namespace App\Controller;

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
 * @Route("/country")
 *
 * Class CountryController
 * @package App\Controller
 */
class CountryController extends AbstractController
{

    private CountryRepository $countryRepository;
    private EntityManagerInterface $em;
    private TextService $textService;

    /**
     * CountryController constructor.
     * @param CountryRepository $countryRepository
     * @param EntityManagerInterface $em
     * @param TextService $textService
     */
    public function __construct(CountryRepository $countryRepository, EntityManagerInterface $em, TextService $textService)
    {
        $this->countryRepository = $countryRepository;
        $this->textService = $textService;
        $this->em = $em;
    }

    /**
     * @Route("/", name="country_index")
     */
    public function index(): Response
    {
        return $this->render('country/index.html.twig', [
            'countries' => $this->countryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="country_new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        return $this->createFormFromEntity($request, new Country());
    }

    /**
     * @Route("/edit/{id}", name="country_edit")
     * @param Request $request
     * @param Country $country
     * @return Response
     */
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
    private function createFormFromEntity(Request $request, Country $country, string $template = 'country/new.html.twig'): Response
    {
        $form = $this->createForm(CountryFormType::class, $country);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $country->setCode(strtolower($country->getCode()));
            $country->setSlug($this->textService->slugify($country->getName()));
            $country->setUrlFlag('https://flagcdn.com/32x24/'.$country->getCode().'.png');
            $this->em->persist($country);
            $this->em->flush();
            return $this->redirectToRoute('country_index');
        }
        return $this->render($template, [
            'form' => $form->createView(),
        ]);
    }

}
