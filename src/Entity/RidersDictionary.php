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

    #[ORM\Column]
    private ?int $riderId = null;

    #[ORM\Column(length: 100)]
    private ?string $originalName = null;

    #[ORM\Column(length: 100)]
    private ?string $normalizedName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRiderId(): ?int
    {
        return $this->riderId;
    }

    public function setRiderId(int $riderId): static
    {
        $this->riderId = $riderId;
        return $this;
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
}
