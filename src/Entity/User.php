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
 * @ORM\OneToMany(targetEntity="App\Entity\Questionary", mappedBy="user", orphanRemoval=true)
 */
private $questionary;

/**
 * @ORM\ManyToOne(targetEntity="App\Entity\GameSession", inversedBy="User")
 */
private $gameSession;


public function __construct()
{
    parent::__construct();
    $this->questionary = new ArrayCollection();
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
}
