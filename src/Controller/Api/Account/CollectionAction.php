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
     * @param AccountRepository $accountRepository
     * @return QueryBuilder
     */
    #[Get('/api/account')]
    #[Serializable(groups: 'Account')]
    #[Paginable(Account::class)]
    public function __invoke(AccountRepository $accountRepository): QueryBuilder
    {
        return $accountRepository->queryAll();
    }

}
