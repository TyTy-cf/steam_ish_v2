<?php


namespace App\Controller\Api\Publisher;


use App\Entity\Publisher;
use App\Repository\PublisherRepository;
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
     * @param PublisherRepository $publisherRepository
     */
    public function __construct(private PublisherRepository $publisherRepository) { }

    /**
     * Get Publisher by slug
     * @param string $slug
     * @return Publisher|null
     */
    #[Get('/api/publisher/{slug}')]
    #[Serializable(groups: 'Publisher')]
    public function __invoke(string $slug): ?Publisher
    {
        return $this->publisherRepository->findOneBySlug($slug);
    }

}
