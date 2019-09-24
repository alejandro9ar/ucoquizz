<?php

declare(strict_types=1);

/*
 * This file is part of the `scav` project.
 *
 * (c) Servicio de Informática de la Universidad de Córdoba
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login-", name="connect_uco_start")
     */
    public function connect(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('uco')
            ->redirect([
                'openid'
            ]);
    }

    /**
     * @Route("login-/check-ssp", name="connect_uco_check")
     */
    public function check(): void
    {
        throw new RuntimeException('This method should not be called.');
    }

    /**
     * @Route("/logout-", name="logout")
     */
    public function logout(): void
    {
        throw new RuntimeException('This method should not be called.');
    }
}
