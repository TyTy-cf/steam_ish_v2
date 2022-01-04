<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
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
     * @Route("/{name}", name="account_show")
     */
    public function show(string $name): Response
    {
        return $this->render('account/show.html.twig', [
            'show' => 'show',
            'name' => $name,
        ]);
    }
}
