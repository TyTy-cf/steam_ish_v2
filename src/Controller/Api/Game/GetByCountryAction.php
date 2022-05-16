<?php


namespace App\Controller\Api\Game;


use App\Entity\Game;
use App\Repository\GameRepository;
use Doctrine\ORM\QueryBuilder;
use Drosalys\Bundle\ApiBundle\Pagination\Attributes\Paginable;
use Drosalys\Bundle\ApiBundle\Routing\Attributes\Get;
use Drosalys\Bundle\ApiBundle\Serializer\Attributes\Serializable;

/**
 * Class GetByCountryAction.php
 *
 * @author Kevin Tourret
 */
class GetByCountryAction
{
    /**
     * CollectionAction constructor.
     * @param GameRepository $gameRepository
     */
    public function __construct(private GameRepository $gameRepository) { }

    /**
     * Get Game by Country slug
     * @param string $slug
     * @return QueryBuilder
     */
    #[Get('/api/game/country/{slug}')]
    #[Serializable(groups: 'GameList')]
    #[Paginable(Game::class)]
    public function __invoke(string $slug): QueryBuilder
    {
        return $this->gameRepository->findGamesByCountrySlug($slug);
    }
}
