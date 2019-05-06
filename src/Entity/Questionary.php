<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="questionary")
 * @ORM\Entity(repositoryClass="App\Repository\QuestionaryRepository")
 */

class Questionary
{

    public const PUBLICO = 'público';
    public const PRIVADO = 'privado';

    public const TYPES = [
        self::PUBLICO,
        self::PRIVADO,
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="questionary")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="questionary", orphanRemoval=true)
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="questionary")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    public function __construct()
    {
        $this->question = new ArrayCollection();
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

    /**
     * @return string
     */
    public function getToken() : ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return self
     */
    public function setToken(string $token) : self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestion(): Collection
    {
        return $this->question;
    }

    public function addPreguntum(Question $preguntum): self
    {
        if (!$this->question->contains($preguntum)) {
            $this->question[] = $preguntum;
            $preguntum->setQuestionary($this);
        }

        return $this;
    }

    public function removePreguntum(Question $preguntum): self
    {
        if ($this->question->contains($preguntum)) {
            $this->question->removeElement($preguntum);
            // set the owning side to null (unless already changed)
            if ($preguntum->getQuestionary() === $this) {
                $preguntum->setQuestionary(null);
            }
        }

        return $this;
    }

    public function addPregunta(Question $pregunta): self
    {
        if (!$this->question->contains($pregunta)) {
            $this->question[] = $pregunta;
            $pregunta->setQuestionary($this);
        }

        return $this;
    }

    public function removePregunta(Question $pregunta): self
    {
        if ($this->question->contains($pregunta)) {
            $this->question->removeElement($pregunta);
            // set the owning side to null (unless already changed)
            if ($pregunta->getQuestionary() === $this) {
                $pregunta->setQuestionary(null);
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

    public function addQuestion(Question $question): self
    {
        if (!$this->question->contains($question)) {
            $this->question[] = $question;
            $question->setQuestionary($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->question->contains($question)) {
            $this->question->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getQuestionary() === $this) {
                $question->setQuestionary(null);
            }
        }

        return $this;
    }
}