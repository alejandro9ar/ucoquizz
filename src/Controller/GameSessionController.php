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

use App\Entity\PlayerAnswer;
use App\DTO\PlayerAnswerDTO;
use App\Entity\Question;
use App\Form\PlayerAnswerType;
use App\Message\AddGameSessionMessage;
use App\DTO\IntroducePassword;
use App\Entity\GameSession;
use App\Entity\Questionary;
use App\Entity\User;
use App\Form\PasswordGameType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Calculation\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Tests\Compiler\D;
use Symfony\Component\DependencyInjection\Tests\Compiler\G;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mukadi\Chart\Utils\RandomColorFactory;
use Mukadi\Chart\Chart;
use Mukadi\Chart\Builder;

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
        $introducedPassword = new IntroducePassword();
        $form = $this->createForm(PasswordGameType::class, $introducedPassword);
        $form->handleRequest($request);
        $passwordForm = $introducedPassword->getPasswordgame();
        $sessions = $this->getDoctrine()->getRepository(GameSession::class)->findAll();

        $games= $this->getDoctrine()->getRepository(



    GameSession::class)->findAll();

        $user = $this->getUser()->getId();

        $variable = 0;
        for ($i = 0; $i <= \count($games) - 1; ++$i) {
            $usersOfGame = $games[$i]->getUser();

            for ($a = 0; $a <= \count($usersOfGame) - 1; ++$a) {

                if ($usersOfGame[$a]->getId() == $user) {

                    $variable = 1;

                }
            }
        }


        if ($form->isSubmitted() && $form->isValid()) {



            foreach ($sessions as $session) {

                if ($passwordForm === $session->getPassword()) {

                    if($variable ==0 ) {
                        if (($session->getStarted() != 1)|| ($session->getClosed() == 1)) {

                            $user = $this->getUser();
                            $session->addUser($user);
                            $em->persist($session);
                            $em->flush();

                            return $this->redirectToRoute('gamesession.gamestarting', ['id' => $session->getId()]);
                        } else {
                            $this->addFlash('notice4', 'El juego ya ha empezado o esta cerrado.');
                            return $this->redirectToRoute('gamesession.enterkey');

                        }
                    }else{
                        $this->addFlash('notice5', 'Ya estas en juego.');
                        return $this->redirectToRoute('gamesession.gamestarting', ['id' => $session->getId()]);

                    }
                }
            }
            $this->addFlash('notice3', 'La clave introducida no coincide con la de ninguna sesión de juego actual.');
            return $this->redirectToRoute('gamesession.enterkey');
        }
        return $this->render('game_session/enterpassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a new session game
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
        $games = $this->getDoctrine()->getRepository(GameSession::class)->findAll();
        $user = $this->getUser();

        $gameFoundedForUser = 0;

        for ($i = 0; $i <= \count($games) - 1; ++$i) {
            if ($games[$i]->getUserCreator() == $user) {
                $gameFoundedForUser = 1;
            } else {
                $gameFoundedForUser = 0;
            }
        }

        if ($gameFoundedForUser == 1) {
            $this->addFlash('notice3', 'El usuario ya tiene una partida creada.');
            return $this->redirectToRoute('questionary.show', ['id' => $questionary->getId()]);
        }

        $game->setQuestionary($questionary);
        $game->setUserCreator($user);
        $game->addUser($user);
        $games = $this->getDoctrine()->getRepository(GameSession::class)->findAll();

        $user = $this->getUser()->getId();

        if (($questionary->getType() == "privado" and $questionary->getUser() == $this->getUser()) or ($questionary->getType() == "público")){
            for ($i = 0; $i <= \count($games) - 1; ++$i) {
                $usersOfGame = $games[$i]->getUser();

                for ($a = 0; $a <= \count($usersOfGame) - 1; ++$a) {

                    if ($usersOfGame[$a]->getId() == $user) {

                        $gameFoundedForUser = $games[$i]->getId();
                        return $this->redirectToRoute('gamesession.gamestarting', ['id' => $games[$i]->getId()]);
                    }
                }
            }
        }elseif($questionary->getType() == "privado" and $this->getUser()==$questionary->getUser()){
            $this->addFlash('notice3', 'El cuestionario es privado, no puedes iniciar partida.');
            return $this->redirectToRoute('questionary.show', ['id' => $questionary->getId()]);

        }else{
            $this->addFlash('notice3', 'El cuestionario es privado, no puedes iniciar partida.');
            return $this->redirectToRoute('questionary.list');

        }

        if($questionary->getQuestion()->isEmpty() == true){
            $this->addFlash('notice3', 'No se puede iniciar partida porque este cuestionario no tiene preguntas');
            return $this->redirectToRoute('questionary.show', ['id' => $questionary->getId()]);

        }

        if ($questionary->getState() == '1') {
            $this->bus->dispatch(
                new AddGameSessionMessage($game)
            );
        } else {
            $this->addFlash('notice3', 'El cuestionario esta cerrado. No se puede comenzar una nueva partida');
            return $this->redirectToRoute('questionary.show', ['id' => $questionary->getId()]);
        }

        //$em->persist($game);
        //$em->flush();
        return $this->redirectToRoute('gamesession.gamestarting', ['id' => $game->getId()]);
    }

    /**
     * Screen to start a game
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

        $answer = $this->getDoctrine()->getRepository(PlayerAnswer::class)->findall();

        return $this->render('game_session/gamedisponible.html.twig', [
            'users' => $users,
            'questionary' => $questionary,
            'game' => $game,
            'answered' => $answer,

        ]);
    }

    /**
     * Screen to start a game
     *
     * @Route("/gamestart/{id}", name="gamesession.gamestart", methods="GET")
     *
     * @return Response
     */
    public function gamestart(GameSession $game, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('GAME_DISPONIBLE', $game);
        //$users = $this->getDoctrine()->getRepository(User::class)->findAll();

        if($game->getUserCreator() == $this->getUser() and $game->getStarted() == 0) {
            $questionary = $game->getQuestionary();
            $users = $game->getUser();

            $answer = $this->getDoctrine()->getRepository(PlayerAnswer::class)->findall();

            $questionary=$game->getQuestionary();
            $questions=$questionary->getQuestion();

            $checkerQuestionAvailable = 0;

            $questionToActive = new Question();

            if( $questions->isEmpty()==false) {
                for ($i = 0; $i <= \count($questions) - 1; ++$i) {
                    if ($questions[$i]->getActivated() == 1) {
                        $checkerQuestionAvailable = 1;
                        $questionToActive = $questions[$i];
                    }
                }
                if ($questionToActive != null){
                    $game->setActivatedQuestion($questionToActive);

                }
            }

            if ($checkerQuestionAvailable == 1) {
                $game->setStarted(1);
                $game->setGameMode("manual");
                $em->persist($game);
                $em->flush();

            }else{
                $this->addFlash('notice3', 'No hay ninguna pregunta disponible');

            }
            return $this->render('game_session/gamedisponible.html.twig', [
                'users' => $users,
                'questionary' => $questionary,
                'game' => $game,
                'answered' => $answer,

            ]);
        }else{
            $this->addFlash('notice3', 'Solo el usuario creador de la sesión puede iniciar partida');
            return $this->redirectToRoute('gamesession.gamestarting', ['id' => $game->getId()]);


        }
    }



    /**
     * Lists all questionary entities.
     *
     * @Route("/lastgames/{id}", name="questionary.lastgames", methods="GET", requirements={"id":"\d+"})
     *
     * @return Response
     */
    public function correctAnswer(Request $request, PaginatorInterface $paginator, $id): Response
    {

        $allSessions= $this->getDoctrine()->getRepository(PlayerAnswer::class)->findAll();

        //to eliminate repeat
        $cont=0;

        for ($i = 0; $i <= \count($allSessions) - 1; ++$i) {

            if ($allSessions[$i]->getUser() == $this->getUser()) {
                $cont = $cont + 1;
                $sessionId[$cont] = $allSessions[$i]->getGamesession()->getId();
            }
        }

        if($cont == 0){
            $sessionId[0]=0;
        }

        $sessionsWithoutRepetition= array_unique($sessionId);


        return $this->render('bundles/FOSUserBundle/Profile/showlastgames.html.twig', [
            'sessions' => $sessionsWithoutRepetition


        ]);
    }

    /**
     * Screen to start a game
     *
     * @Route("/gamestartauto/{id}", name="gamesession.gamestartauto", methods="GET")
     *
     * @return Response
     */
    public function gamestartauto(GameSession $game, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('GAME_DISPONIBLE', $game);
        //$users = $this->getDoctrine()->getRepository(User::class)->findAll();

        if($game->getUserCreator() == $this->getUser() and $game->getStarted() == 0) {
        $questionary = $game->getQuestionary();
        $users = $game->getUser();

        $answer = $this->getDoctrine()->getRepository(PlayerAnswer::class)->findall();



        $questionary=$game->getQuestionary();
        $questions=$questionary->getQuestion();

        $checkerQuestionAvailable = 0;

        $questionToActive = new Question();

        if( $questions->isEmpty()==false) {
            for ($i = 0; $i <= \count($questions) - 1; ++$i) {
                if ($questions[$i]->getActivated() == 1) {
                    $checkerQuestionAvailable = 1;
                    $questionToActive = $questions[$i];
                }
            }
            if ($questionToActive != null){
                for ($i = 0; $i <= \count($users) - 1; ++$i) {

                    $users[$i]->setActiveQuestion($questionToActive);
                }
             }
        }

        if ($checkerQuestionAvailable == 1) {
            $game->setStarted(1);
            $game->setGameMode("auto");
            $em->persist($game);
            $em->flush();

        }else{
                $this->addFlash('notice3', 'No hay ninguna pregunta disponible');

        }
        return $this->render('game_session/gamedisponible.html.twig', [
            'users' => $users,
            'questionary' => $questionary,
            'game' => $game,
            'answered' => $answer,

        ]);


        }else{
            $this->addFlash('notice3', 'Solo el usuario creador de la sesión puede iniciar partida o el juego ya ha empezado');
            return $this->redirectToRoute('gamesession.gamestarting', ['id' => $game->getId()]);

        }
    }

    /**
     * Screen to start a game
     *
     * @Route("/gameclose/{id}", name="gamesession.gameclose", methods="GET")
     *
     * @return Response
     */
    public function gameclose(GameSession $game, EntityManagerInterface $em, $id): Response
    {

        if($game->getUserCreator() == $this->getUser()) {
            $users = $this->getDoctrine()->getRepository(User::class)->findBy(array( 'gameSession' => $id ));

            for ($i = 0; $i <= \count($users) - 1; ++$i) {

                $actualGamesession = $users[$i]->getGameSession();
                $users[$i]->setActiveQuestion(null);
                $users[$i]->setGameSession(null);
                $em->persist($users[$i]);

            }
            $game->setUserCreator(null);
            $game->setClosed(1);
            $em->persist($game);

            $em->flush();


            return $this->redirectToRoute('gamesession.stats', ['id' => $actualGamesession->getId()]);

        }else{
            $this->addFlash('notice3', 'Solo el usuario creador de la sesión puede cerrar partida');
            return $this->redirectToRoute('gamesession.gamestarting', ['id' => $game->getId()]);

        }
    }

    /**
     * Screen to start a game
     *
     * @Route("/gameexit/{id}", name="gamesession.gameexit", methods="GET")
     *
     * @return Response
     */
    public function gameexit(GameSession $game, EntityManagerInterface $em, $id): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findBy(array( 'gameSession' => $id ));
        if( $this->getUser() == $game->getUserCreator()){
            return $this->redirectToRoute('gamesession.gameclose', ['id' => $game->getId()]);
        }

        for ($i = 0; $i <= \count($users) - 1; ++$i) {
            if ($users[$i] == $this->getUser()){
                $users[$i]->setGameSession(null);
                $users[$i]->setActiveQuestion(null);
                $em->persist($users[$i]);
            }
        }
        $em->flush();

        return $this->redirectToRoute('questionary.list');
    }

    /**
     * Return to a game
     *
     * @Route("/returntogame/{id}", name="gamesession.returntogame", methods="GET")
     *
     * @return Response
     */

    public function returntogame (EntityManagerInterface $em): Response
    {
        $games= $this->getDoctrine()->getRepository(GameSession::class)->findAll();

        $user = $this->getUser()->getId();

        for ($i = 0; $i <= \count($games) - 1; ++$i) {
            $usersOfGame = $games[$i]->getUser();

            for ($a = 0; $a <= \count($usersOfGame) - 1; ++$a) {

                if ($usersOfGame[$a]->getId() == $user) {

                    $variable = $games[$i]->getId();
                    return $this->redirectToRoute('gamesession.gamestarting', ['id' => $games[$i]->getId()]);
                }
            }
        }

        return $this->redirectToRoute('questionary.list');

    }


    /**
     * Return to a game
     *
     * @Route("/answergame/{idsession}/{idquestion}", name="gamesession.answergame", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function answergame (Request $request, EntityManagerInterface $em, $idsession, $idquestion): Response
    {


        $answerPlayer = new PlayerAnswerDTO();
        $questionary = new Questionary();
        //question of logged user
        $questionUser = $this->getDoctrine()->getRepository(PlayerAnswer::class)->findBy(array( 'user' => $this->getUser()->getId() ));
        $actualGameSession = $this->getDoctrine()->getRepository(GameSession::class)->findOneBy(array( 'id' => $idsession) );


        $this->denyAccessUnlessGranted('GAME_DISPONIBLE', $actualGameSession);


        if( $actualGameSession->getActivatedQuestion() != null  ){
        if($actualGameSession->getActivatedQuestion()->getId() == $idquestion ){
            $answerQuestionFounded=0;
            for ($i = 0; $i <= \count($questionUser) - 1; ++$i) {
                if ($questionUser[$i]->getQuestion()->getId() == $idquestion and $questionUser[$i]->getAnsweredAt() != null and $questionUser[$i]->getGameSession()->getId() == $actualGameSession->getId() )
                    $answerQuestionFounded = 1 ;
            }

            if($answerQuestionFounded == 0) {
                $gamesession = $this->getDoctrine()->getRepository(GameSession::class)->findAll();
                $question = $this->getDoctrine()->getRepository(Question::class)->findOneBy(array('id' => $idquestion));
                $answerOfQuestion = $question->getAnswer();


                for ($i = 0; $i <= \count($gamesession) - 1; ++$i) {
                    if ($gamesession[$i]->getId() == $idsession) {
                        $questionary = $gamesession[$i]->getQuestionary();
                    }
                }

                $form = $this->createForm(PlayerAnswerType::class, $answerPlayer);
                $form->handleRequest($request);


                // FIND OR CREATE


                $answer = $this->getDoctrine()->getRepository(PlayerAnswer::class)->findOneBy(array('user' => $this->getUser()->getId(), 'question' => $question, 'gamesession' => $actualGameSession));

                if ($answer == false) {
                    $answer = new PlayerAnswer();
                    $answer->setPlayerAnswer('a');
                    $answer->setAnswered(0);

                    $questionWasStartedAt = new \DateTime("now");
                    $answer->setStartedAt($questionWasStartedAt);
                    $answer->setUser($this->getUser());
                    $answer->setGamesession($actualGameSession);
                    $answer->setQuestionary($questionary);
                    $answer->setQuestion($question);

                    $em->persist($answer);
                    $em->flush();
                }
                // save if found is false




                if ($form->isSubmitted() && $form->isValid()) {
                    if(($answerPlayer->getAnswer1() == 1 && $answerPlayer->getAnswer2()== 0 && $answerPlayer->getAnswer3() == 0 && $answerPlayer->getAnswer4() ==0)
                        || ($answerPlayer->getAnswer1() == 0 && $answerPlayer->getAnswer2()== 1 && $answerPlayer->getAnswer3() == 0 && $answerPlayer->getAnswer4() ==0)
                        || ($answerPlayer->getAnswer1() == 0 && $answerPlayer->getAnswer2()== 0 && $answerPlayer->getAnswer3() == 2 && $answerPlayer->getAnswer4() ==0)
                        || ($answerPlayer->getAnswer1() == 0 && $answerPlayer->getAnswer2()== 0 && $answerPlayer->getAnswer3() == 0 && $answerPlayer->getAnswer4() ==3)
                        || ($answerPlayer->getAnswer1() == 0 && $answerPlayer->getAnswer2()== 0 && $answerPlayer->getAnswer3() == 0 && $answerPlayer->getAnswer4() ==0)) {


                        $answer->setAnswered(true);

                        if ($answerPlayer->getAnswer1() == 1) {
                            $answer->setPlayerAnswer(1);
                        }
                        elseif ($answerPlayer->getAnswer2() == 1) {
                            $answer->setPlayerAnswer(2);
                        }

                        elseif ($answerPlayer->getAnswer3() == 1) {
                            $answer->setPlayerAnswer(3);
                        }
                        elseif ($answerPlayer->getAnswer4() == 1) {
                            $answer->setPlayerAnswer(4);
                        }else{
                            $answer->setPlayerAnswer(0);
                        }

                        //pass to second diference between $questionWasStartedAt and $questionWasAnsweredAt
                        $questionWasAnsweredAt = new \DateTime("now");
                        $answer->setAnsweredAt($questionWasAnsweredAt);

                        $seconds = $questionWasAnsweredAt->diff($answer->getStartedAt());

                        $seconds = $seconds->format('%s')+($seconds->format('%i')*60) ;

                        $puntuation = 100 / (0.25 * $seconds);


                        $answer->setDurationOfAnswer($seconds);

                        $checkerAnswer=0;
                        if ($seconds > $answer->getQuestion()->getDuration()) {
                            $checkerAnswer=1;
                            $answer->setPuntuation(0);
                            $answer->setCorrect(false);
                        } elseif ($answer->getPlayerAnswer() == 1 and $answerOfQuestion[0]->getCorrect() == 1) {
                            $checkerAnswer=2;
                            $answer->setPuntuation($puntuation);
                            $answer->setCorrect(true);
                        } elseif ($answer->getPlayerAnswer() == 2 and $answerOfQuestion[1]->getCorrect() == 1) {
                            $checkerAnswer=2;
                            $answer->setPuntuation($puntuation);
                            $answer->setCorrect(true);
                        } elseif ($answer->getPlayerAnswer() == 3 and $answerOfQuestion[2]->getCorrect() == 1) {
                            $checkerAnswer=2;
                            $answer->setPuntuation($puntuation);
                            $answer->setCorrect(true);
                        } elseif ($answer->getPlayerAnswer() == 4 and $answerOfQuestion[3]->getCorrect() == 1) {
                            $checkerAnswer=2;
                            $answer->setPuntuation($puntuation);
                            $answer->setCorrect(true);
                        } else {
                            $checkerAnswer=1;
                            $answer->setPuntuation(0);
                            $answer->setCorrect(false);

                        }

                        $answer->setAnswered(1);
                        $em->persist($answer);
                        $em->flush();



                        return $this->render('game_session/checkeranswer.html.twig', [

                            'question' => $question,
                            'game' => $actualGameSession,
                            'answer' =>$answer,
                            'checker' =>$checkerAnswer,
                        ]);
                    }
                    else{
                        $this->addFlash('notice', 'Solo puede seleccionar una respuesta correcta');

                        return $this->render('game_session/answerplayer.html.twig', [
                            'form' => $form->createView(),
                            'question' => $question,
                            'game' => $actualGameSession,


                        ]);

                    }
                }

            }else{
                $this->addFlash('notice', 'Ya has respondido la pregunta');
                return $this->redirectToRoute('gamesession.gamestarting', ['id' => $idsession]);
            }

        }else{
            $this->addFlash('notice3', 'Esta pregunta no está activada en la sesión');
            return $this->redirectToRoute('gamesession.gamestarting', ['id' => $idsession]);
        }
        }

        return $this->render('game_session/answerplayer.html.twig', [
            'form' => $form->createView(),
            'question' => $question,
            'game' => $actualGameSession,


        ]);


    }

    /**
     * Return to a game
     *
     * @Route("/answergameauto/{idsession}/{idquestion}", name="gamesession.answergameauto", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function answergameauto (Request $request, EntityManagerInterface $em, $idsession, $idquestion): Response
    {

        $checkerAnswer=0;
        $answerPlayer = new PlayerAnswerDTO();
        $questionary = new Questionary();
        //question of logged user
        $questionUser = $this->getDoctrine()->getRepository(PlayerAnswer::class)->findBy(array( 'user' => $this->getUser()->getId() ));
        $actualGameSession = $this->getDoctrine()->getRepository(GameSession::class)->findOneBy(array( 'id' => $idsession) );
        $questionGameSession = $actualGameSession->getQuestionary()->getQuestion();

        //$this->denyAccessUnlessGranted('GAME_DISPONIBLE', $actualGameSession);

        $answerQuestionFounded=0;
        for ($i = 0; $i <= \count($questionUser) - 1; ++$i) {
            if ($questionUser[$i]->getQuestion()->getId() == $idquestion and $questionUser[$i]->getAnsweredAt() != null and $questionUser[$i]->getGameSession()->getId() == $actualGameSession->getId() )
                $answerQuestionFounded = 1 ;
        }

        if($answerQuestionFounded == 0) {
            $gamesession = $this->getDoctrine()->getRepository(GameSession::class)->findAll();
            $question = $this->getDoctrine()->getRepository(Question::class)->findOneBy(array('id' => $idquestion));
            $answerOfQuestion = $question->getAnswer();


            for ($i = 0; $i <= \count($gamesession) - 1; ++$i) {
                if ($gamesession[$i]->getId() == $idsession) {
                    $questionary = $gamesession[$i]->getQuestionary();
                }
            }

            $form = $this->createForm(PlayerAnswerType::class, $answerPlayer);
            $form->handleRequest($request);


            // FIND OR CREATE

            $answer = $this->getDoctrine()->getRepository(PlayerAnswer::class)->findOneBy(array('user' => $this->getUser()->getId(), 'question' => $question, 'gamesession' => $actualGameSession));

            if ($answer == false) {
                $answer = new PlayerAnswer();
                $answer->setPlayerAnswer('a');
                $answer->setAnswered(0);

                $questionWasStartedAt = new \DateTime("now");
                $answer->setStartedAt($questionWasStartedAt);
                $answer->setUser($this->getUser());
                $answer->setGamesession($actualGameSession);
                $answer->setQuestionary($questionary);
                $answer->setQuestion($question);

                $em->persist($answer);
                $em->flush();
            }
            // save falseif found is false




            if ($form->isSubmitted() && $form->isValid()) {
                if(($answerPlayer->getAnswer1() == 1 && $answerPlayer->getAnswer2()== 0 && $answerPlayer->getAnswer3() == 0 && $answerPlayer->getAnswer4() ==0)
                    || ($answerPlayer->getAnswer1() == 0 && $answerPlayer->getAnswer2()== 1 && $answerPlayer->getAnswer3() == 0 && $answerPlayer->getAnswer4() ==0)
                    || ($answerPlayer->getAnswer1() == 0 && $answerPlayer->getAnswer2()== 0 && $answerPlayer->getAnswer3() == 2 && $answerPlayer->getAnswer4() ==0)
                    || ($answerPlayer->getAnswer1() == 0 && $answerPlayer->getAnswer2()== 0 && $answerPlayer->getAnswer3() == 0 && $answerPlayer->getAnswer4() ==3)
                    || ($answerPlayer->getAnswer1() == 0 && $answerPlayer->getAnswer2()== 0 && $answerPlayer->getAnswer3() == 0 && $answerPlayer->getAnswer4() ==0)) {


                    $answer->setAnswered(true);

                    if ($answerPlayer->getAnswer1() == 1) {
                        $answer->setPlayerAnswer(1);
                    }
                    elseif ($answerPlayer->getAnswer2() == 1) {
                        $answer->setPlayerAnswer(2);
                    }

                    elseif ($answerPlayer->getAnswer3() == 1) {
                        $answer->setPlayerAnswer(3);
                    }
                    elseif ($answerPlayer->getAnswer4() == 1) {
                        $answer->setPlayerAnswer(4);
                    }else{
                        $answer->setPlayerAnswer(0);
                    }

                    //pass to second diference between $questionWasStartedAt and $questionWasAnsweredAt
                    $questionWasAnsweredAt = new \DateTime("now");
                    $answer->setAnsweredAt($questionWasAnsweredAt);

                    $seconds = $questionWasAnsweredAt->diff($answer->getStartedAt());

                    $seconds = $seconds->format('%s')+($seconds->format('%i')*60) ;

                    $puntuation = 100 / (0.25 * $seconds);


                    $answer->setDurationOfAnswer($seconds);


                    if ($seconds > $answer->getQuestion()->getDuration()) {
                        $checkerAnswer=1;
                        $answer->setPuntuation(0);
                        $answer->setCorrect(false);
                    } elseif ($answer->getPlayerAnswer() == 1 and $answerOfQuestion[0]->getCorrect() == 1) {
                        $checkerAnswer=2;
                        $answer->setPuntuation($puntuation);
                        $answer->setCorrect(true);
                    } elseif ($answer->getPlayerAnswer() == 2 and $answerOfQuestion[1]->getCorrect() == 1) {
                        $checkerAnswer=2;
                        $answer->setPuntuation($puntuation);
                        $answer->setCorrect(true);
                    } elseif ($answer->getPlayerAnswer() == 3 and $answerOfQuestion[2]->getCorrect() == 1) {
                        $checkerAnswer=2;
                        $answer->setPuntuation($puntuation);
                        $answer->setCorrect(true);
                    } elseif ($answer->getPlayerAnswer() == 4 and $answerOfQuestion[3]->getCorrect() == 1) {
                        $checkerAnswer=2;
                        $answer->setPuntuation($puntuation);
                        $answer->setCorrect(true);
                    } else {
                        $checkerAnswer=1;
                        $answer->setPuntuation(0);
                        $answer->setCorrect(false);

                    }


                    $answer->setAnswered(1);
                    $em->persist($answer);
                    $em->flush();

                    $user=$this->getUser();


                    $question = $this->getDoctrine()->getRepository(Question::class)->findOneBy(array('id' => $idquestion));
                    for ($i = 0; $i <= \count($questionGameSession) - 1; ++$i) {
                        $checker = $this->getDoctrine()->getRepository(PlayerAnswer::class)->findOneBy(array('gamesession'=>$actualGameSession, 'question' => $questionGameSession[$i], 'user'=>$user));

                            if($checker == false and $questionGameSession[$i]->getActivated() == 1  ){
                                $user->setActiveQuestion($questionGameSession[$i]);

                                $em->persist($user);
                                $em->flush();

                                return $this->render('game_session/checkeranswer.html.twig', [

                                    'question' => $question,
                                    'game' => $actualGameSession,
                                    'checker' =>$checkerAnswer,
                                    'answer' =>$answer,
                                ]);
                            }
                     }

                    return $this->render('game_session/checkeranswer.html.twig', [

                        'question' => $question,
                        'game' => $actualGameSession,
                        'answer' =>$answer,
                        'checker' =>$checkerAnswer,
                    ]);

                }
                else{
                    $this->addFlash('notice', 'Solo puede seleccionar una respuesta correcta');

                    return $this->render('game_session/answerplayer.html.twig', [
                        'form' => $form->createView(),
                        'question' => $question,
                        'game' => $actualGameSession,

                    ]);

                }
            }

        }else{
            $this->addFlash('notice4', 'Ya has respondido la pregunta');
            return $this->redirectToRoute('gamesession.gamestarting', ['id' => $idsession]);
        }



        return $this->render('game_session/answerplayer.html.twig', [
            'form' => $form->createView(),
            'question' => $question,
            'game' => $actualGameSession,


        ]);


    }

    /**
     * Return to a game
     *
     * @Route("/activate/{idsession}/{idquestion}", name="gamesession.activateQuestion", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function activateQuestion (Request $request, EntityManagerInterface $em, $idsession, $idquestion): Response
    {
        $question = $this->getDoctrine()->getRepository(Question::class)->findOneBy(array('id' => $idquestion));
        $gamesesion = $this->getDoctrine()->getRepository(GameSession::class)->findOneBy(array('id' => $idsession));

        if ($question->getActivated() == 1 and $gamesesion->getUserCreator()== $this->getUser()){
            $gamesesion->setActivatedQuestion($question);

            $em->persist($gamesesion);
            $em->flush();

        }else{

            $this->addFlash('notice7', 'Esta pregunta está eliminada del cuestionario o no eres el administrador de la partida.');
        }
                return $this->redirectToRoute('gamesession.gamestarting', ['id' => $idsession]);

    }


    /**
     * @Route("/stats/{id}" , name="gamesession.stats", methods={"GET", "POST"})
     */
    public function stats($id)
    {
        $actualGameSession = $this->getDoctrine()->getRepository(GameSession::class)->findOneBy(array( 'id' => $id) );
        //$this->denyAccessUnlessGranted('GAME_DISPONIBLE', $actualGameSession);

        $clasification = $this->getDoctrine()->getRepository(PlayerAnswer::class)->findByMorePutuation($id);
        $questionOfUserData = $this->getDoctrine()->getRepository(PlayerAnswer::class)->findByQuestionData($id,$this->getUser()->getId());

        return $this->render("game_session/statistics.html.twig", [
            'api_url' => $this->generateUrl('app_gamesession_data1', ['id' => $id]),
            'api_url2' => $this->generateUrl('app_gamesession_data2', ['id' => $id]),
            'api_url3' => $this->generateUrl('app_gamesession_data3', ['id' => $id]),
            'clasification' => $clasification,
            'idGameSession' => $id,
            'questionOfUserData' =>$questionOfUserData
        ]);
    }

    /**
     * @Route("/data1/{id},")
     */
    public function data1(QuestionRepository $questionRepository, SerializerInterface $serializer, $id)
    {
        /*$data = [
            ['Javier', 4],
            ['Carlos', 2],
            ['Ana', 4],
            ['Carla', 3],
        ];*/

        $data = $this->getDoctrine()->getRepository(PlayerAnswer::class)->findByUser($id);



        return new JsonResponse(
            $data,
            200,
            [],
            false
       );
    }

    /**
     * @Route("/data2/{id}")
     */
    public function data2(QuestionRepository $questionRepository, SerializerInterface $serializer, $id)
    {
        /*$data = [
            ['Javier', 4],
            ['Carlos', 2],
            ['Ana', 4],
            ['Carla', 3],
        ];*/

        $data2 = $this->getDoctrine()->getRepository(PlayerAnswer::class)->findByQuestion($id);

        return new JsonResponse(
            $data2,
            200,
            [],
            false
        );
    }

    /**
     * @Route("/data3/{id}")
     */
    public function data3(QuestionRepository $questionRepository, SerializerInterface $serializer, $id)
    {
        /*$data = [
            ['Javier', 4],
            ['Carlos', 2],
            ['Ana', 4],
            ['Carla', 3],
        ];*/

        $data3 = $this->getDoctrine()->getRepository(PlayerAnswer::class)->findByAverageDurationOfAnswer($id);


        return new JsonResponse(
            $data3,
            200,
            [],
            false
        );
    }





}