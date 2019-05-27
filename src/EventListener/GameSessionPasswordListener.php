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

use App\Entity\GameSession;
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
            $entity->setPassword(bin2hex(random_bytes(3)));
        }
    }
}
