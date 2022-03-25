<?php

namespace App\Controller\Admin;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use App\Repository\CommentRepository;
use App\Repository\LibraryRepository;
use App\Service\TimeService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account')]
class AccountController extends AbstractController
{

    /**
     * AccountController constructor.
     * @param EntityManagerInterface $em
     * @param LibraryRepository $libraryRepository
     */
    public function __construct(
        private EntityManagerInterface $em,
        private LibraryRepository $libraryRepository
    ) { }

    #[Route('/', name: "account_index")]
    public function index(AccountRepository $accountRepository): Response
    {
        return $this->render('Admin/account/index.html.twig', [
            'accounts' => $accountRepository->findAllWithRelations(),
        ]);
    }

    /**
     * @param Request $request => requête http qui passe pour afficher votre page web avec ses informations GET/POST, attributs etc
     * @return Response
     */
    #[Route('/new', name: "account_create")]
    public function createAccount(Request $request): Response {
        // Créer le formulaire => le Type du formulaire souhaité ; l'entité sur laquelle initialiser le formulaire
        $form = $this->createForm(AccountType::class, new Account());
        // On indique au formulaire qu'il va intercepter la requête HTTP (en POST) pour récupérer les données du formulaire
        $form->handleRequest($request);
        // On vérifie si le formulaire a été soumis et s'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // le form->getData() récupère les données du formulaire, autrement dit, directement un objet de type Account
            // (pour l'exemple) avec ses attributs remplis à partir du formulaire
            /** @var Account $account */
            $account = $form->getData();
//            $account->setSlug($account->getName());
            // préparer les "requêtes" pour insérer/updater notre entité
            $this->em->persist($account);
            // déclenche le fait d'exécuter les requêtes en BDD
            $this->em->flush();
            return $this->redirectToRoute('account_index');
        }
        return $this->render('Admin/account/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param string $name
     * @param AccountRepository $accountRepository
     * @param CommentRepository $commentRepository
     * @param TimeService $timeService
     * @return Response
     * @throws NonUniqueResultException
     */
    #[Route('/{name}', name: "account_show")]
    public function show(
        string $name,
        AccountRepository $accountRepository,
        CommentRepository $commentRepository,
        TimeService $timeService
    ): Response {
        $account = $accountRepository->findAllByName($name);
        return $this->render('Admin/account/show.html.twig', [
            'account' => $account,
            'comments' => $commentRepository->findCommentsByAccount($account),
            'totalGameTime' => $timeService->getTimeConverter($this->libraryRepository->getTotalGameTimeByAccount($account)),
        ]);
    }

    /**
     * @param Request $request
     * @param Account $account
     * @return Response
     */
    #[Route('/edit/{name}', name: "account_edit")]
    public function editAccount(Request $request, Account $account): Response {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($account);
            $this->em->flush();
            return $this->redirectToRoute('account_index');
        }
        return $this->render('Admin/account/edit.html.twig',[
            'form' => $form->createView(),
        ]);
    }

}
