<?php

// src/Entity/FileUpdated.php.php
namespace App\DTO;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class FileUpdated
{

    private $fileupdate;

    public function getFileupdate()
    {
    return $this->fileupdate;
    }

    public function setFileupdate($fileupdate)
    {
    $this->fileupdate = $fileupdate;

    return $this;
    }
}