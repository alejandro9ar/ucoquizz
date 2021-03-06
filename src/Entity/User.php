<?php

/*
 * This file is part of the ucoquizz project.
 *
 * (c) Alejandro Arroyo Ruiz <i42arrua@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Questionary", mappedBy="user", orphanRemoval=true)
     */
    private $questionary;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GameSession", inversedBy="user")
     */
    private $gameSession;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GameSession", mappedBy="UserCreator")
     */
    private $gameSessions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="users")
     */
    private $activeQuestion;


    public function __construct()
    {
        parent::__construct();
        $this->questionary = new ArrayCollection();
        $this->gameSessions = new ArrayCollection();
    }

    /**
     * @return Collection|Questionary[]
     */
    public function getQuestionary(): Collection
    {
        return $this->questionary;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function addQuestionary(Questionary $questionary): self
    {
        if (!$this->questionary->contains($questionary)) {
            $this->questionary[] = $questionary;
            $questionary->setUser($this);
        }

        return $this;
    }

    public function removeQuestionary(Questionary $questionary): self
    {
        if ($this->questionary->contains($questionary)) {
            $this->questionary->removeElement($questionary);
            // set the owning side to null (unless already changed)
            if ($questionary->getUser() === $this) {
                $questionary->setUser(null);
            }
        }

        return $this;
    }

    public function getGameSession(): ?GameSession
    {
        return $this->gameSession;
    }

    public function setGameSession(?GameSession $gameSession): self
    {
        $this->gameSession = $gameSession;

        return $this;
    }

    /**
     * @return Collection|GameSession[]
     */
    public function getGameSessions(): Collection
    {
        return $this->gameSessions;
    }

    public function addGameSession(GameSession $gameSession): self
    {
        if (!$this->gameSessions->contains($gameSession)) {
            $this->gameSessions[] = $gameSession;
            $gameSession->setUserCreator($this);
        }

        return $this;
    }

    public function removeGameSession(GameSession $gameSession): self
    {
        if ($this->gameSessions->contains($gameSession)) {
            $this->gameSessions->removeElement($gameSession);
            // set the owning side to null (unless already changed)
            if ($gameSession->getUserCreator() === $this) {
                $gameSession->setUserCreator(null);
            }
        }

        return $this;
    }

    public function getActiveQuestion(): ?Question
    {
        return $this->activeQuestion;
    }

    public function setActiveQuestion(?Question $activeQuestion): self
    {
        $this->activeQuestion = $activeQuestion;

        return $this;
    }

}
