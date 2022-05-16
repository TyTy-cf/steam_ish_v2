<?php


namespace App\Controller\Api\Account;


use App\Entity\Account;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Drosalys\Bundle\ApiBundle\Event\PrePersistEvent;
use Drosalys\Bundle\ApiBundle\Persister\Attributes\PrePersist;
use Drosalys\Bundle\ApiBundle\Routing\Attributes\Put;
use Drosalys\Bundle\ApiBundle\Serializer\Attributes\Deserializable;
use Drosalys\Bundle\ApiBundle\Serializer\Attributes\Serializable;

/**
 * Class PutAction.php
 *
 * @author Kevin Tourret
 */
class PutAction
{

    public function __construct(
        private EntityManagerInterface $em,
        private CountryRepository $countryRepository
    ) { }



    /**
     * Update an Account
     * @param Account $account
     * @return Account
     */
    #[Put('/api/account/{id}')]
    #[Serializable(groups: 'Account'), Deserializable('account', groups: 'AccountPut')]
    #[PrePersist]
    public function __invoke(Account $account): Account
    {
        return $account;
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
