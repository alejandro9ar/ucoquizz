<?php


namespace App\MessageHandler;


use App\Message\AddGameSessionMessage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddGameSessionMessageHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManager
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(AddGameSessionMessage $message)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';

        $gamesession = $message->getGameSession();
        $gamesession->setPassword(substr(str_shuffle($permitted_chars), 0, 7));

        $this->manager->persist($gamesession);
        $this->manager->flush();

    }
}