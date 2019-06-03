<?php

/*
 * This file is part of the ucoquizz project.
 *
 * (c) Alejandro Arroyo Ruiz <i42arrua@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Message\AddGameSessionMessage;
use App\DTO\IntroducePassword;
use App\Entity\GameSession;
use App\Entity\Questionary;
use App\Entity\User;
use App\Form\PasswordGameType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameSessionController extends AbstractController
{

    /**
     * @var MessageBusInterface
     */
    private $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * Creates a new questionary entity.
     *
     * @Route("/enterkey", name="gamesession.enterkey", methods={"GET", "POST"})
     *
     * @param Request                $request
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse|Response
     */
    public function enterkey(Request $request, EntityManagerInterface $em): Response
    {
        $introducedpassword = new IntroducePassword();

        $form = $this->createForm(PasswordGameType::class, $introducedpassword);
        $form->handleRequest($request);

        $passwordform = $introducedpassword->getPasswordgame();

        $sessions = $this->getDoctrine()->getRepository(GameSession::class)->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($sessions as $session) {
                if ($passwordform === $session->getPassword()) {
                    $user = $this->getUser();
                    $session->addUser($user);

                    $this->bus->dispatch(
                        new AddGameSessionMessage($session)
                    );

                    return $this->redirectToRoute('gamesession.gamestarting', ['id' => $session->getId()]);
                }
            }

            return $this->redirectToRoute('gamesession.enterkey');
        }

        return $this->render('game_session/enterpassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a new questionary entity.
     *
     * @Route("/play/{id}", name="gamesession.play", methods={"GET", "POST"}, requirements={"id":"\d+"})
     *
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse|Response
     */
    public function play(EntityManagerInterface $em, Questionary $questionary): Response
    {
        $game = new GameSession();

        $user = $this->getUser();
        $game->setQuestionary($questionary);
        $game->addUser($user);

        $this->bus->dispatch(
            new AddGameSessionMessage($game)
        );

        //$em->persist($game);
        //$em->flush();

        return $this->redirectToRoute('gamesession.gamestarting', ['id' => $game->getId()]);
    }

    /**
     * Lists all questionary entities.
     *
     * @Route("/gamestarting/{id}", name="gamesession.gamestarting", methods="GET")
     *
     * @return Response
     */
    public function gamestarting(GameSession $game): Response
    {
        $this->denyAccessUnlessGranted('GAME_DISPONIBLE', $game);

        //$users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $questionary = $game->getQuestionary();
        $users = $game->getUser();

        return $this->render('game_session/gamedisponible.html.twig', [
            'users' => $users,
            'questionary' => $questionary,
            'game' => $game,
        ]);
    }
}
