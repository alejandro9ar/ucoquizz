<?php

namespace App\Controller;

use App\Entity\GameSession;
use App\Entity\Questionary;
use App\DTO\GameDisponible;
use App\Entity\User;
use App\Form\PasswordGameType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameSessionController extends AbstractController
{

    /**
     * Creates a new questionary entity.
     *
     * @Route("/enterkey/{id}", name="gamesession.enterkey", methods={"GET", "POST"}, requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse|Response
     */
    public function enterkey(Request $request, EntityManagerInterface $em, Questionary $questionary): Response
    {
        $questionary2 = new GameDisponible();
        $game = new GameSession();
        $form = $this->createForm(PasswordGameType::class, $questionary2);
        $form->handleRequest($request);

        $passwordform = $questionary2->getPasswordgame();

        if ($form->isSubmitted() && $form->isValid()) {

                if ($passwordform == $questionary->getPassword()) {

                    $user = $this->getUser();
                    $game->setQuestionary($questionary);
                    $game->addUser($user);

                    $em->persist($game);
                    $em->flush();

                    return $this->redirectToRoute('gamesession.gamestarting', ['id' => $game->getId()]);
                }



            return $this->redirectToRoute('questionary.show',['id' => $questionary->getId()]);

        }

        return $this->render('game_session/enterpassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a new questionary entity.
     *
     * @Route("/play/{id}", name="gamesession.play", methods={"GET", "POST"}, requirements={"id":"\d+"})
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


        $em->persist($game);
        $em->flush();

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

        ]);
    }


}
