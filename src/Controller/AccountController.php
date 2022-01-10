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
     */
    public function index(AccountRepository $accountRepository): Response
    {
        return $this->render('account/index.html.twig', [
            'accounts' => $accountRepository->findAllWithRelations(),
        ]);
    }

    /**
     * @Route("/create", name="account_create")
     */
    public function createAccount(Request $request): Response {
        $form = $this->createForm(AccountType::class, new Account());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();
            return $this->redirectToRoute('account_index');
        }
        return $this->render('account/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{name}", name="account_edit")
     */
    public function editAccount(Request $request, Account $account): Response {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();
            return $this->redirectToRoute('account_index');
        }
        return $this->render('account/edit.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{name}", name="account_show")
     */
    public function show(Account $account): Response
    {
        return $this->render('account/show.html.twig', [
            'account' => $account,
        ]);
    }

}
