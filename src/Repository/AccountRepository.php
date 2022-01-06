<?php

namespace App\Repository;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    /**
     * Requête optimiser pour récupérer tous les account avec leurs bibliothèques
     *
     * @param string $order
     * @return array
     */
    public function findAllWithRelations(string $order = 'account.name'): array
    {
        // SELECT *
        // FROM account AS account
        // JOIN library ON library.account_id = account.id
        // ORDER BY account.name ASC => par défaut
        return $this->createQueryBuilder('account')
            ->select('account', 'libraries')
            ->join('account.libraries', 'libraries')
            ->orderBy($order, 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
