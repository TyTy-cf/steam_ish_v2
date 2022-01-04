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
     * @return array
     */
    public function findAllWithRelations(): array
    {
        return $this->createQueryBuilder('account')
            ->select('account', 'libraries')
            ->join('account.libraries', 'libraries')
            ->orderBy('account.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
