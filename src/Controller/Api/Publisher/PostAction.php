<?php


namespace App\Controller\Api\Publisher;


use App\Entity\Account;
use App\Entity\Publisher;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Drosalys\Bundle\ApiBundle\Event\PrePersistEvent;
use Drosalys\Bundle\ApiBundle\Persister\Attributes\PrePersist;
use Drosalys\Bundle\ApiBundle\Routing\Attributes\Post;
use Drosalys\Bundle\ApiBundle\Serializer\Attributes\Deserializable;
use Drosalys\Bundle\ApiBundle\Serializer\Attributes\Serializable;

/**
 * Class PostAction.php
 *
 * @author Kevin Tourret
 */
class PostAction
{

    public function __construct(
        private EntityManagerInterface $em,
        private CountryRepository $countryRepository
    ) { }

    /**
     * Create a new Publisher
     * @param Publisher $publisher
     * @return Publisher
     */
    #[Post('/api/publisher')]
    #[Serializable(groups: 'Publisher'), Deserializable('publisher', groups: 'PublisherPost')]
    #[PrePersist]
    public function __invoke(Publisher $publisher): Publisher
    {
        return $publisher;
    }

    /**
     * @param PrePersistEvent $event
     */
    public function prePersist(PrePersistEvent $event): void {
        /** @var Account $data */
        $data = $event->getData();

        if ($data->getCountry()->getSlug() !== null) {
            $country = $this->countryRepository->findOneBy(['slug' => $data->getCountry()->getSlug() ]);
            $data->setCountry($country);
        }

        $this->em->persist($data);
        $this->em->flush();
    }
}
