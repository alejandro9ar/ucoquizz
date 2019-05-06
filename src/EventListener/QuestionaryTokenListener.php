<?php
namespace App\EventListener;

use App\Entity\Questionary;
use Doctrine\ORM\Event\LifecycleEventArgs;

class QuestionaryTokenListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Questionary) {
            return;
        }

        if (!$entity->getToken()) {
            $entity->setToken(\bin2hex(\random_bytes(10)));
        }
    }
}