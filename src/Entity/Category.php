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
 * @ORM\Entity()
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var Questionary[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Questionary", mappedBy="category")
     */
    private $questionary;

    public function __construct()
    {
        $this->questionary = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Questionary[]
     */
    public function getQuestionary(): Collection
    {
        return $this->questionary;
    }

    public function addCuestionario(Questionary $cuestionario): self
    {
        if (!$this->questionary->contains($cuestionario)) {
            $this->questionary[] = $cuestionario;
            $cuestionario->setCategory($this);
        }

        return $this;
    }

    public function removeCuestionario(Questionary $cuestionario): self
    {
        if ($this->questionary->contains($cuestionario)) {
            $this->questionary->removeElement($cuestionario);
            // set the owning side to null (unless already changed)
            if ($cuestionario->getCategory() === $this) {
                $cuestionario->setCategory(null);
            }
        }

        return $this;
    }

    public function addQuestionary(Questionary $questionary): self
    {
        if (!$this->questionary->contains($questionary)) {
            $this->questionary[] = $questionary;
            $questionary->setCategory($this);
        }

        return $this;
    }

    public function removeQuestionary(Questionary $questionary): self
    {
        if ($this->questionary->contains($questionary)) {
            $this->questionary->removeElement($questionary);
            // set the owning side to null (unless already changed)
            if ($questionary->getCategory() === $this) {
                $questionary->setCategory(null);
            }
        }

        return $this;
    }
}
