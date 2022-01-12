<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Mixed_;

/**
 * @method Library|null find($id, $lockMode = null, $lockVersion = null)
 * @method Library|null findOneBy(array $criteria, array $orderBy = null)
 * @method Library[]    findAll()
 * @method Library[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Library::class);
    }

    /**
     * @param Account $account
     * @return string
     */
    public function getTotalGameTimeByAccount(Account $account): int {
        $qb = $this->createQueryBuilder('l')
            ->select('SUM(l.gameTime)')
            ->join('l.account', 'account')
            ->where('l.account = :account')
            ->setParameter('account', $account)
            ->getQuery()
            ->getResult()[0][1]
        ;
        if ($qb == null){
            $qb = '0';
        }
        return intval($qb);
    }
}
