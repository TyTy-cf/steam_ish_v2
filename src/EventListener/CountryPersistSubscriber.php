<?php

namespace App\EventListener;

use App\Entity\Country;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;


/**
 * Class UserEventListener.php
 *
 * @author Kevin Tourret
 */
class CountryPersistSubscriber implements EventSubscriber
{

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->index($args);
    }

    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Country) {
            $entity->setUrlFlag('https://flagcdn.com/32x24/'. $entity->getCode(). '.png');
        }
    }
}
