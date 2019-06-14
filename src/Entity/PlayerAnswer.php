<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerAnswerRepository")
 */
class PlayerAnswer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $gamesession;

    /**
     * @ORM\Column(type="integer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $questionary;

    /**
     * @ORM\Column(type="integer")
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $playerAnswer;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $puntuation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $answered;

    /**
     * @ORM\Column(type="integer")
     */
    private $user;

    public function getGamesession(): ?int
    {
        return $this->gamesession;
    }

    public function setGamesession(int $gamesession): self
    {
        $this->gamesession = $gamesession;

        return $this;
    }

    public function getQuestionary(): ?int
    {
        return $this->questionary;
    }

    public function setQuestionary(int $questionary): self
    {
        $this->questionary = $questionary;

        return $this;
    }

    public function getQuestion(): ?int
    {
        return $this->question;
    }

    public function setQuestion(int $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getPlayerAnswer(): ?string
    {
        return $this->playerAnswer;
    }

    public function setPlayerAnswer(string $playerAnswer): self
    {
        $this->playerAnswer = $playerAnswer;

        return $this;
    }

    public function getPuntuation(): ?int
    {
        return $this->puntuation;
    }

    public function setPuntuation(int $puntuation): self
    {
        $this->puntuation = $puntuation;

        return $this;
    }

    public function getAnswered(): ?bool
    {
        return $this->answered;
    }

    public function setAnswered(?bool $answered): self
    {
        $this->answered = $answered;

        return $this;
    }

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(int $user): self
    {
        $this->user = $user;

        return $this;
    }

    
}
