<?php


namespace App\Controller\Api\Account;


use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\NonUniqueResultException;
use Drosalys\Bundle\ApiBundle\Routing\Attributes\Get;
use Drosalys\Bundle\ApiBundle\Serializer\Attributes\Serializable;

/**
 * Class GetAction.php
 *
 * @author Kevin Tourret
 */
class GetAction
{

    /**
     * CollectionAction constructor.
     * @param AccountRepository $accountRepository
     */
    public function __construct(private AccountRepository $accountRepository) { }

    /**
     * Get User account by slug
     * @param string $slug
     * @return Account|null
     * @throws NonUniqueResultException
     */
    #[Get('/api/account/{slug}')]
    #[Serializable(groups: 'Account')]
    public function __invoke(string $slug): ?Account
    {
        return $this->accountRepository->findOneBySlug($slug);
    }

}
