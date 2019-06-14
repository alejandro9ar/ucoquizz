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

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameSessionRepository")
 */
class GameSession
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="gameSession")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Questionary", inversedBy="gameSessions")
     */
    private $questionary;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(unique=true)
     */
    private $UserCreator;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $started;


    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setGameSession($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getGameSession() === $this) {
                $user->setGameSession(null);
            }
        }

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

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
    public function __toString()
    {
        return $this->getPassword();
    }

    public function getUserCreator(): ?User
    {
        return $this->UserCreator;
    }

    public function setUserCreator(?User $UserCreator): self
    {
        $this->UserCreator = $UserCreator;

        return $this;
    }

    public function getStarted(): ?bool
    {
        return $this->started;
    }

    public function setStarted(?bool $started): self
    {
        $this->started = $started;

        return $this;
    }






}

