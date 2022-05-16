<?php


namespace App\Controller\Api\Game;


use App\Entity\Game;
use App\Form\Api\GameFilterFormType;
use App\Repository\GameRepository;
use Doctrine\ORM\QueryBuilder;
use Drosalys\Bundle\ApiBundle\Filter\Attributes\Filterable;
use Drosalys\Bundle\ApiBundle\Pagination\Attributes\Paginable;
use Drosalys\Bundle\ApiBundle\Routing\Attributes\Get;
use Drosalys\Bundle\ApiBundle\Serializer\Attributes\Serializable;

/**
 * Class CollectionAction.php
 *
 * @author Kevin Tourret
 */
class CollectionAction
{
    /**
     * CollectionAction constructor.
     * @param GameRepository $gameRepository
     */
    public function __construct(private GameRepository $gameRepository) { }

    /**
     * Get User account list.
     * @return QueryBuilder
     */
    #[Get('/api/game')]
    #[Serializable(groups: 'GameList')]
    #[Paginable(Game::class)]
    public function __invoke(): QueryBuilder
    {
        return $this->gameRepository->queryAll();
    }
}
