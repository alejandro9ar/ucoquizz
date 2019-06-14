<?php
namespace App\DTO;

class PlayerAnswerDTO
{

    private $answer1;

    private $answer2;

    private $answer3;

    private $answer4;

    public function getAnswer1(): ?bool
{
    return $this->answer1;
}

    public function setAnswer1(?bool $answer1): self
    {
        $this->answer1 = $answer1;

        return $this;
    }

    public function getAnswer2(): ?bool
    {
        return $this->answer2;
    }

    public function setAnswer2(?bool $answer2): self
    {
        $this->answer2 = $answer2;

        return $this;
    }

    public function getAnswer3(): ?bool
    {
        return $this->answer3;
    }

    public function setAnswer3(?bool $answer3): self
    {
        $this->answer3 = $answer3;

        return $this;
    }

    public function getAnswer4(): ?bool
    {
        return $this->answer4;
    }

    public function setAnswer4(?bool $answer4): self
    {
        $this->answer4 = $answer4;

        return $this;
    }
}
