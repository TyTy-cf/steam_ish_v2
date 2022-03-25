<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Comment;
use App\Entity\Game;
use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @param int $limit
     * @return array
     */
    public function findCommentsByLimit(int $limit = 10): array
    {
        return $this->createQueryBuilder('comment')
            ->select('comment', 'game', 'account')
            ->join('comment.account', 'account')
            ->join('comment.game', 'game')
            ->orderBy('comment.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Account $account
     * @return array
     */
    public function findCommentsByAccount(Account $account): array
    {
        return $this->createQueryBuilder('comment')
            ->select('comment', 'game')
            ->join('comment.game', 'game')
            ->join('comment.account', 'account')
            ->where('account = :account')
            ->setParameter('account', $account)
            ->orderBy('comment.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Game $game le slug du jeu attendu
     * @return array
     */
    public function findCommentsByGame(Game $game): array
    {
        return $this->createQueryBuilder('comment')
            ->select('comment', 'account')
            ->join('comment.game', 'game')
            ->join('comment.account', 'account')
            ->where('game = :game')
            ->setParameter('game', $game)
            ->orderBy('comment.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Genre $genre
     * @param int $limit
     * @return int|mixed|string
     */
    public function findByGenre(Genre $genre, int $limit = 5): array
    {
        return $this->createQueryBuilder('comment')
            ->select('comment', 'account', 'game')
            ->leftJoin('comment.game', 'game')
            ->leftJoin('comment.account', 'account')
            ->leftJoin('game.genres', 'genres')
            ->where('genres = :genre')
            ->setParameter('genre', $genre)
            ->orderBy('comment.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
