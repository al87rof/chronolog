<?php

namespace App\Entity;

use App\Repository\RidersDictionaryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RidersDictionaryRepository::class)]
#[ORM\Table(name: 'riders_dictionary')]
class RidersDictionary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $originalName = null;

    #[ORM\Column(length: 100)]
    private ?string $normalizedName = null;

    #[ORM\ManyToOne(targetEntity: Riders::class)]
    #[ORM\JoinColumn(name: 'rider_id', referencedColumnName: 'id')]
    private ?Riders $rider = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): static
    {
        $this->originalName = $originalName;
        return $this;
    }

    public function getNormalizedName(): ?string
    {
        return $this->normalizedName;
    }

    public function setNormalizedName(string $normalizedName): static
    {
        $this->normalizedName = $normalizedName;
        return $this;
    }

    public function getRider(): ?Riders
    {
        return $this->rider;
    }

    public function setRider(?Riders $rider): self
    {
        $this->rider = $rider;

        return $this;
    }
}
