<?php

namespace App\Entity;

use App\Repository\RidersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RidersRepository::class)]
#[ORM\Table(name: 'riders')]
class Riders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $team = null;


    #[ORM\Column(length: 500)]
    private ?string $eventsIds = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getTeam(): ?string
    {
        return $this->team;
    }

    public function setTeam(string $team): static
    {
        $this->team = $team;
        return $this;
    }

    public function getEventsIds(): ?string
    {
        return $this->eventsIds;
    }

    public function setEventsIds(string $eventsIds): static
    {
        $this->eventsIds = $eventsIds;
        return $this;
    }
}
