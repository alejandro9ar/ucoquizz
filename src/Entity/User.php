<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

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
 * @ORM\OneToMany(targetEntity="App\Entity\Cuestionario", mappedBy="user")
 */
private $cuestionario;

public function __construct()
{
    parent::__construct();
    $this->cuestionario = new ArrayCollection();
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
        $cuestionario->setUser($this);
    }

    return $this;
}

public function removeCuestionario(Cuestionario $cuestionario): self
{
    if ($this->cuestionario->contains($cuestionario)) {
        $this->cuestionario->removeElement($cuestionario);
        // set the owning side to null (unless already changed)
        if ($cuestionario->getUser() === $this) {
            $cuestionario->setUser(null);
        }
    }

    return $this;
}
}
