<?php

namespace App\Entity;

use App\Repository\EventsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'events')]
#[ORM\Entity(repositoryClass: EventsRepository::class)]
class Events
{
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(name: 'type_id', type: 'integer', nullable: false)]
    private int $typeId;

    #[ORM\Column(name: 'date', type: 'datetime', nullable: false)]
    private \DateTime $date;

    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(name: 'event_img', type: 'string', length: 500, nullable: false)]
    private ?string $eventImg;

    #[ORM\Column(name: 'rider_list', type: 'text', nullable: false, columnDefinition: 'LONGTEXT')]
    private string $riderList;

    #[ORM\Column(name: 'riders_list_dsq', type: 'text', nullable: false, columnDefinition: 'LONGTEXT')]
    private string $ridersListDsq;

    #[ORM\Column(name: 'app_log', type: 'text', nullable: false, columnDefinition: 'LONGTEXT')]
    private string $appLog;

    #[ORM\Column(name: 'hash', type: 'text', nullable: false, columnDefinition: 'LONGTEXT')]
    private string $hash;

    #[ORM\Column(name: 'results', type: 'text', nullable: false, columnDefinition: 'LONGTEXT')]
    private string $results;

    #[ORM\Column(name: 'count', type: 'integer', nullable: false)]
    private int $count;

    #[ORM\Column(name: 'description', type: 'text', nullable: false)]
    private string $description;

    #[ORM\Column(name: 'search_tags', type: 'text', nullable: false)]
    private string $searchTags;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setEventImg(string $eventImg): self
    {
        $this->eventImg = $eventImg;
        return $this;
    }

    public function getEventImg(): string
    {
        return $this->eventImg;
    }

    public function setRiderList(string $riderList): self
    {
        $this->riderList = $riderList;
        return $this;
    }

    public function getRiderList(): string
    {
        return $this->riderList;
    }

    public function setAppLog(string $appLog): self
    {
        $this->appLog = $appLog;
        return $this;
    }

    public function getAppLog(): string
    {
        return $this->appLog;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setResults(string $results): void
    {
        $this->results = $results;
    }

    public function getResults(): string
    {
        return $this->results;
    }

    public function getResultsJson(): array
    {
        return  json_decode($this->results,true);
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getSearchTags(): string
    {
        return $this->searchTags;
    }

    public function getKeywords(): string
    {
        return $this->searchTags;
    }

    public function setSearchTags(string $searchTags): void
    {
        $this->searchTags = $searchTags;
    }

    public function setTypeId(int $typeId): void
    {
        $this->typeId = $typeId;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }

    public function setRidersListDsq(string $ridersListDsq): void
    {
        $this->ridersListDsq = $ridersListDsq;
    }

    public function getRidersListDsq(): string
    {
        return $this->ridersListDsq;
    }

    public function getRidersListDsqArray(): array
    {
        return explode("\n", $this->ridersListDsq);
    }
}
