<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RankingRepository")
 */
class Ranking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="rankings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userRank;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Meeting", inversedBy="rankings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $meeting;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getUserRank(): ?User
    {
        return $this->userRank;
    }

    public function setUserRank(?User $userRank): self
    {
        $this->userRank = $userRank;

        return $this;
    }

    public function getMeeting(): ?Meeting
    {
        return $this->meeting;
    }

    public function setMeeting(?Meeting $meeting): self
    {
        $this->meeting = $meeting;

        return $this;
    }
}
