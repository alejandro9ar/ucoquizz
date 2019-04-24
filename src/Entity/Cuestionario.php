<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cuestionario")
 */

class Cuestionario
{

    public const PUBLICO = 'publico';
    public const PRIVADO = 'privado';

    public const TYPES = [
        self::PUBLICO,
        self::PRIVADO,
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="cuestionario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $time;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pregunta", mappedBy="cuestionario", orphanRemoval=true)
     */
    private $pregunta;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="cuestionario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    public function __construct()
    {
        $this->pregunta = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        $this->category = $category;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return Collection|Pregunta[]
     */
    public function getPregunta(): Collection
    {
        return $this->pregunta;
    }

    public function addPreguntum(Pregunta $preguntum): self
    {
        if (!$this->pregunta->contains($preguntum)) {
            $this->pregunta[] = $preguntum;
            $preguntum->setCuestionario($this);
        }

        return $this;
    }

    public function removePreguntum(Pregunta $preguntum): self
    {
        if ($this->pregunta->contains($preguntum)) {
            $this->pregunta->removeElement($preguntum);
            // set the owning side to null (unless already changed)
            if ($preguntum->getCuestionario() === $this) {
                $preguntum->setCuestionario(null);
            }
        }

        return $this;
    }

    public function addPregunta(Pregunta $pregunta): self
    {
        if (!$this->pregunta->contains($pregunta)) {
            $this->pregunta[] = $pregunta;
            $pregunta->setCuestionario($this);
        }

        return $this;
    }

    public function removePregunta(Pregunta $pregunta): self
    {
        if ($this->pregunta->contains($pregunta)) {
            $this->pregunta->removeElement($pregunta);
            // set the owning side to null (unless already changed)
            if ($pregunta->getCuestionario() === $this) {
                $pregunta->setCuestionario(null);
            }
        }

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }
}