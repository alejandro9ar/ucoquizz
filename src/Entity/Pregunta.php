<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Pregunta1Repository")
 */
class Pregunta
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cuestionario", inversedBy="pregunta")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cuestionario;

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
    private $respuesta1;

    /**
     * @ORM\Column(type="boolean")
     */
    private $check1;

    /**
     * @ORM\Column(type="text")
     */
    private $respuesta2;

    /**
     * @ORM\Column(type="boolean")
     */
    private $check2;

    /**
     * @ORM\Column(type="text")
     */
    private $respuesta3;

    /**
     * @ORM\Column(type="boolean")
     */
    private $check3;

    /**
     * @ORM\Column(type="text")
     */
    private $respuesta4;

    /**
     * @ORM\Column(type="boolean")
     */
    private $check4;

    /**
     * @ORM\Column(type="integer")
     */
    private $time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCuestionario(): ?Cuestionario
    {
        return $this->cuestionario;
    }

    public function setCuestionario(?Cuestionario $cuestionario): self
    {
        $this->cuestionario = $cuestionario;

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

    public function getRespuesta1(): ?string
    {
        return $this->respuesta1;
    }

    public function setRespuesta1(string $respuesta1): self
    {
        $this->respuesta1 = $respuesta1;

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

    public function getRespuesta2(): ?string
    {
        return $this->respuesta2;
    }

    public function setRespuesta2(string $respuesta2): self
    {
        $this->respuesta2 = $respuesta2;

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

    public function getRespuesta3(): ?string
    {
        return $this->respuesta3;
    }

    public function setRespuesta3(string $respuesta3): self
    {
        $this->respuesta3 = $respuesta3;

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

    public function getRespuesta4(): ?string
    {
        return $this->respuesta4;
    }

    public function setRespuesta4(string $respuesta4): self
    {
        $this->respuesta4 = $respuesta4;

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

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }
}