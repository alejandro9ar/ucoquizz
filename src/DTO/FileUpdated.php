<?php

/*
 * This file is part of the ucoquizz project.
 *
 * (c) Alejandro Arroyo Ruiz <i42arrua@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DTO;

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
