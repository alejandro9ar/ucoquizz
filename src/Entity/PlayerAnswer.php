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
     * @ORM\ManyToOne(targetEntity="App\Entity\GameSession")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gamesession;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Questionary")
     * @ORM\JoinColumn(nullable=false)
     */
    private $questionary;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $startedAt;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $answeredAt;
    

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

    public function getGamesession(): ?GameSession
    {
        return $this->gamesession;
    }

    public function setGamesession(?GameSession $gamesession): self
    {
        $this->gamesession = $gamesession;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getQuestionary(): ?Questionary
    {
        return $this->questionary;
    }

    public function setQuestionary(?Questionary $questionary): self
    {
        $this->questionary = $questionary;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeInterface $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getAnsweredAt(): ?\DateTimeInterface
    {
        return $this->answeredAt;
    }

    public function setAnsweredAt(?\DateTimeInterface $answeredAt): self
    {
        $this->answeredAt = $answeredAt;

        return $this;
    }

    

    
}
