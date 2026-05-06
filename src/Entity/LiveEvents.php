<?php

namespace App\Entity;

use App\Repository\LiveEventsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'live_events')]
#[ORM\Entity(repositoryClass: LiveEventsRepository::class)]
class LiveEvents
{
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(name: 'event_id', type: 'string', length: 5, nullable: false)]
    private string $eventId;

    #[ORM\Column(name: 'data', type: 'datetime')]
    private \DateTimeInterface $data;

    #[ORM\Column(name: 'app_log', type: 'text', nullable: true, columnDefinition: 'LONGTEXT')]
    private string $appLog;

    #[ORM\Column(name: 'riders_list', type: 'text', nullable: true, columnDefinition: 'LONGTEXT')]
    private string $ridersList;

    #[ORM\Column(name: 'title', type: 'string', length: 500,nullable: true )]
    private string $title;

    #[ORM\Column(name: 'description', type: 'text', nullable: true, columnDefinition: 'LONGTEXT')]
    private string $description;

    #[ORM\Column(name: 'event_img', type: 'string', length: 500)]
    private ?string $eventImg;

    #[ORM\Column(name: 'result', type: 'text', nullable: true, columnDefinition: 'LONGTEXT')]
    private string $result;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setEventId(string $eventId): self
    {
        $this->eventId = $eventId;
        return $this;
    }

    public function getEventId(): string
    {
        return $this->eventId;
    }

    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): \DateTimeInterface
    {
        return $this->data;
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

    public function setRidersList(string $ridersList): self
    {
        $this->ridersList = $ridersList;
        return $this;
    }

    public function getRidersList(): string
    {
        return $this->ridersList;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
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

    public function setResult(string $result): self
    {
        $this->result = $result;
        return $this;
    }

    public function getResult(): ?array
    {
        return json_decode($this->result,true);
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
