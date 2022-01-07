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
     * @param string $slug
     * @return Game|null
     * @throws NonUniqueResultException
     */
    public function findGameBySlug(string $slug): ?Game {
        return $this->createQueryBuilder('g')
            ->select('g')
            ->join('g.genres', 'genres')
            ->join('g.languages', 'languages')
            ->where('g.slug = :slug')
            ->setParameter('slug', $slug)
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
        // FROM game AS g
        return $this->createQueryBuilder('g')
            // SELECT g.*
            ->select('g')
            // JOIN library AS lib ON g.id = lib.game_id
            ->join(Library::class, 'lib', Join::WITH, 'lib.game = g')
            // GROUP BY g.name
            ->groupBy('g.name')
            // ORDER BY SUM(lib.game_time) DESC
            ->orderBy( 'SUM(lib.gameTime)', 'DESC')
            // LIMIT $limit (par défaut 10)
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
        // FROM game AS game
        return $this->createQueryBuilder('game')
            // SELECT game.name, game.slug
            ->select('game.name', 'game.slug')
            // WHERE game.name LIKE '%$name%' => $name est un paramètre
            ->where('game.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->getQuery()
            ->getResult()
        ;
    }
}
