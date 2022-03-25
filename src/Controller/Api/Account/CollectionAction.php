<?php

namespace App\Controller\Api\Account;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\QueryBuilder;
use Drosalys\Bundle\ApiBundle\Pagination\Attributes\Paginable;
use Drosalys\Bundle\ApiBundle\Routing\Attributes\Get;
use Drosalys\Bundle\ApiBundle\Serializer\Attributes\Serializable;

class CollectionAction
{

    /**
     * Get User account list.
     */
    #[Get('/api/accounts')]
    #[Serializable(groups: 'Accounts')]
    #[Paginable(Account::class)]
    public function __invoke(AccountRepository $accountRepository): QueryBuilder
    {
        return $accountRepository->queryAll();
    }

}