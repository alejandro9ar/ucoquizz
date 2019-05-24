<?php
namespace App\EventListener;

use App\Entity\GameSession;
use App\Entity\Question;
use Doctrine\ORM\Event\LifecycleEventArgs;

class GameSessionPasswordListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof GameSession) {
            return;
        }

        if (!$entity->getPassword()) {
            $entity->setPassword(\bin2hex(\random_bytes(3)));
        }
    }
}