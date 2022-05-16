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
     * CollectionAction constructor.
     * @param AccountRepository $accountRepository
     */
    public function __construct(private AccountRepository $accountRepository) { }

    /**
     * Get User account list.
     * @return QueryBuilder
     */
    #[Get('/api/account')]
    #[Serializable(groups: 'AccountList')]
    #[Paginable(Account::class)]
    public function __invoke(): QueryBuilder
    {
        return $this->accountRepository->queryAll();
    }

}
