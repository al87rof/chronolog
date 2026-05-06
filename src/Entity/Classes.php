<?php

namespace App\Entity;

use App\Repository\ClassesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassesRepository::class)]
class Classes
{

    public const CLASS_ALIASES = [
        'xc-1' => 'xc-1',
        'cx-1' => 'xc-1',
        'xc1' => 'xc-1',

        'Amateur' => 'Аматор',
        'amatuer' => 'Аматор',
        'аматор' => 'Аматор',

        'xc-2' => 'xc-2',
        'cx-2' => 'xc-2',
        'xc2' => 'xc-2',

        'Veteran'=>'Ветеран',
        'Ветеран'=>'Ветеран',

        'NoLicense'=>'Free',
        'Free'=>'Free',
    ];


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $type_id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeId(): ?int
    {
        return $this->type_id;
    }

    public function setTypeId(int $type_id): static
    {
        $this->type_id = $type_id;

        return $this;
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
}
