<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Controller;


/**
 * @ORM\Entity()
 * @ORM\Table(name="category")
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
     *
     * @var Cuestionario[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Cuestionario", mappedBy="category")
     */
    private $cuestionario;


    public function __construct()
    {
        $this->cuestionario = new ArrayCollection();
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
     * @return Collection|Cuestionario[]
     */
    public function getCuestionario(): Collection
    {
        return $this->cuestionario;
    }

    public function addCuestionario(Cuestionario $cuestionario): self
    {
        if (!$this->cuestionario->contains($cuestionario)) {
            $this->cuestionario[] = $cuestionario;
            $cuestionario->setCategory($this);
        }

        return $this;
    }

    public function removeCuestionario(Cuestionario $cuestionario): self
    {
        if ($this->cuestionario->contains($cuestionario)) {
            $this->cuestionario->removeElement($cuestionario);
            // set the owning side to null (unless already changed)
            if ($cuestionario->getCategory() === $this) {
                $cuestionario->setCategory(null);
            }
        }

        return $this;
    }

}