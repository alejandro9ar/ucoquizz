<?php
namespace App\EventListener;

use App\Entity\Question;
use Doctrine\ORM\Event\LifecycleEventArgs;

class QuestionTokeListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Question) {
            return;
        }

        if (!$entity->getToke()) {
            $entity->setToke(\bin2hex(\random_bytes(10)));
        }
    }
}