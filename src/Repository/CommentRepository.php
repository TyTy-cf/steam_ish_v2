<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Game;
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
}
