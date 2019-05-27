<?php

/*
 * This file is part of the ucoquizz project.
 *
 * (c) Alejandro Arroyo Ruiz <i42arrua@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
            $entity->setToken(bin2hex(random_bytes(10)));
        }
    }
}
