<?php


namespace App\Message;


use App\Entity\GameSession;
use App\Entity\Questionary;

class AddGameSessionMessage
{
    /**
     * @var GameSession
     */
    private $gamesession;

    public function __construct(GameSession $gamesession)
    {
        $this->gamesession = $gamesession;
    }

    /**
     * @return GameSession
     */
    public function getGameSession(): GameSession
    {
        return $this->gamesession;
    }
}