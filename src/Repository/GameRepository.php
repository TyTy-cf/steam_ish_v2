<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @param int $id
     * @return Game|null
     * @throws NonUniqueResultException
     */
    public function findGameById(int $id): ?Game {
        return $this->createQueryBuilder('game')
            ->select('game')
            ->join('game.genres', 'genres')
            ->where('game.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param int $limit
     * @param bool $isOrderedByName
     * @return array
     */
    public function findLastGames(int $limit = 10, bool $isOrderedByName = false): array {
        // SELECT * FROM game JOIN sur language & genre
        $qb = $this->createQueryBuilder('game');

        // En fonction des conditions, j'ajoute différent ORDER BY sur ma requête
        if ($isOrderedByName) {
            $qb->orderBy('game.name', 'ASC');
        } else {
            $qb->orderBy('game.publishedAt', 'DESC');
        }

        // LIMIT 10 ou LIMIT $limit
        return $qb->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param int $limit
     * @return array
     */
    public function findMostPlayedGame(int $limit = 10): array {
        // SELECT game.name, SUM(library.game_time) AS sumTime
        // FROM game
        // JOIN library ON game.id = library.game_id
        // GROUP BY game.name
        // ORDER BY sumTime DESC
        return $this->createQueryBuilder('g')
            ->select('g')
            ->join(Library::class, 'lib', Join::WITH, 'lib.game = g')
            ->groupBy('g.name')
            ->orderBy( 'SUM(lib.gameTime)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Return all game name
     *
     * @param string $name
     * @return array
     */
    public function findAllNames(string $name): array
    {
        return $this->createQueryBuilder('game')
            ->select('game.name', 'game.slug')
            ->where('game.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->getQuery()
            ->getResult()
        ;
    }
}
