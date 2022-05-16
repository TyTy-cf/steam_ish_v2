<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
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

    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('game')
            ->select('game')
        ;
    }

    public function findGamesByGenreSlug(string $slug): QueryBuilder
    {
        return $this->createQueryBuilder('game')
            ->leftJoin('game.genres', 'genres')
            ->where('genres.slug = :slug')
            ->setParameter('slug', $slug)
        ;
    }

    public function findGamesByCountrySlug(string $slug): QueryBuilder
    {
        return $this->createQueryBuilder('game')
            ->leftJoin('game.countries', 'countries')
            ->where('countries.slug = :slug')
            ->setParameter('slug', $slug)
        ;
    }

    /**
     * @param string $slug
     * @return Game|null
     * @throws NonUniqueResultException
     */
    public function findGameBySlug(string $slug): ?Game {
        return $this->createQueryBuilder('g')
            ->select('g', 'genres', 'countries', 'comments', 'account')
            ->join('g.genres', 'genres')
            ->leftJoin('g.comments', 'comments')
            ->leftJoin('g.countries', 'countries')
            ->leftJoin('comments.account', 'account')
            ->where('g.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('comments.createdAt', 'DESC')
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

    /**
     * @param Collection $genres
     * @param int $limit
     * @return array
     */
    public function findRelatedGameByGenres(Collection $genres, int $limit = 5): array
    {
        return $this->createQueryBuilder('game')
            ->select('game', 'genres')
            ->join('game.genres', 'genres')
            ->where('genres IN(:genres)')
            ->setParameter('genres', $genres)
            ->orderBy('game.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }
}
