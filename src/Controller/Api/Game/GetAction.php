<?php


namespace App\Controller\Api\Game;


use App\Entity\Game;
use App\Repository\GameRepository;
use Doctrine\ORM\NonUniqueResultException;
use Drosalys\Bundle\ApiBundle\Routing\Attributes\Get;
use Drosalys\Bundle\ApiBundle\Serializer\Attributes\Serializable;

/**
 * Class GetAction.php
 *
 * @author Kevin Tourret
 */
class GetAction
{

    /**
     * CollectionAction constructor.
     * @param GameRepository $gameRepository
     */
    public function __construct(private GameRepository $gameRepository) { }

    /**
     * Get User account by slug
     * @param string $slug
     * @return Game|null
     * @throws NonUniqueResultException
     */
    #[Get('/api/game/{slug}')]
    #[Serializable(groups: 'Game')]
    public function __invoke(string $slug): ?Game
    {
        return $this->gameRepository->findGameBySlug($slug);
    }

}
