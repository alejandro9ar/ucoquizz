<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Questionary", inversedBy="question")
     * @ORM\JoinColumn(nullable=false)
     */
    private $questionary;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $answer1;

    /**
     * @ORM\Column(type="boolean")
     */
    private $check1;

    /**
     * @ORM\Column(type="text")
     */
    private $answer2;

    /**
     * @ORM\Column(type="boolean")
     */
    private $check2;

    /**
     * @ORM\Column(type="text")
     */
    private $answer3;

    /**
     * @ORM\Column(type="boolean")
     */
    private $check3;

    /**
     * @ORM\Column(type="text")
     */
    private $answer4;

    /**
     * @ORM\Column(type="boolean")
     */
    private $check4;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $toke;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function getAnswer1(): ?string
    {
        return $this->answer1;
    }

    public function setAnswer1(string $answer1): self
    {
        $this->answer1 = $answer1;

        return $this;
    }

    public function getCheck1(): ?bool
    {
        return $this->check1;
    }

    public function setCheck1(bool $check1): self
    {
        $this->check1 = $check1;

        return $this;
    }

    public function getAnswer2(): ?string
    {
        return $this->answer2;
    }

    public function setAnswer2(string $answer2): self
    {
        $this->answer2 = $answer2;

        return $this;
    }

    public function getCheck2(): ?bool
    {
        return $this->check2;
    }

    public function setCheck2(bool $check2): self
    {
        $this->check2 = $check2;

        return $this;
    }

    public function getAnswer3(): ?string
    {
        return $this->answer3;
    }

    public function setAnswer3(string $answer3): self
    {
        $this->answer3 = $answer3;

        return $this;
    }

    public function getCheck3(): ?bool
    {
        return $this->check3;
    }

    public function setCheck3(bool $check3): self
    {
        $this->check3 = $check3;

        return $this;
    }

    public function getAnswer4(): ?string
    {
        return $this->answer4;
    }

    public function setAnswer4(string $answer4): self
    {
        $this->answer4 = $answer4;

        return $this;
    }

    public function getCheck4(): ?bool
    {
        return $this->check4;
    }

    public function setCheck4(bool $check4): self
    {
        $this->check4 = $check4;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return string
     */
    public function getToke() : ?string
    {
        return $this->toke;
    }

    /**
     * @param string $toke
     *
     * @return self
     */
    public function setToke(string $toke) : self
    {
        $this->toke = $toke;
        return $this;
    }
}