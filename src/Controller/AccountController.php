<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
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
     * @Route("/", name="account_index")
     * @param AccountRepository $accountRepository
     * @return Response
     */
    public function index(AccountRepository $accountRepository): Response
    {
        return $this->render('account/index.html.twig', [
            'accounts' => $accountRepository->findAllWithRelations(),
        ]);
    }

    /**
     * @Route("/new", name="account_create")
     * @param Request $request => requête http qui passe pour afficher votre page web avec ses informations GET/POST, attributs etc
     * @return Response
     */
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
        return $this->render('account/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{name}", name="account_show")
     * @param Account $account
     * @return Response
     */
    public function show(Account $account): Response
    {
        return $this->render('account/show.html.twig', [
            'account' => $account,
        ]);
    }

    /**
     * @Route("/edit/{name}", name="account_edit")
     * @param Request $request
     * @param Account $account
     * @return Response
     */
    public function editAccount(Request $request, Account $account): Response {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($account);
            $this->em->flush();
            return $this->redirectToRoute('account_index');
        }
        return $this->render('account/edit.html.twig',[
            'form' => $form->createView(),
        ]);
    }

}
