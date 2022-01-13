<?php

namespace App\Repository;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    /**
     * Requête pour récupérer tous les pays de la base de données, triés sur le paramètre '$field' et avec l'order en paramètre
     *
     * @param string $field
     * @param string $order
     * @return array
     */
    public function findAllOrderBy(string $field = 'name', string $order = 'ASC'): array
    {
        return $this->findBy([], [$field => $order]);
//        return $this->createQueryBuilder('country')
//            ->orderBy($field, $order)
//            ->getQuery()
//            ->getResult()
//        ;
    }
}
