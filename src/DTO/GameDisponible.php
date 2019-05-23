<?php

// src/Entity/FileUpdated.php.php
namespace App\DTO;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class GameDisponible
{

    private $passwordgame;

    public function getPasswordgame()
    {
        return $this->passwordgame;
    }

    public function setPasswordgame($passwordgame)
    {
        $this->passwordgame = $passwordgame;

        return $this;
    }
}