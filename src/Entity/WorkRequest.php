<?php

namespace App\Entity;

use App\Entity\Traits\TimeStampableTrait;
use App\Repository\WorkRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: WorkRequestRepository::class)]
#[ORM\Table(name: '`work_requests`')]
#[ORM\HasLifecycleCallbacks]
class WorkRequest
{
    use TimeStampableTrait;

    public const STATUS_NEW = 0;
    public const STATUS_ACCEPTED = 1;
    public const STATUS_IN_PROGRESS = 2;

    public const AVAILABLE_STATUSES = [
        self::STATUS_NEW => 'Новый',
        self::STATUS_ACCEPTED => 'Принят',
        self::STATUS_IN_PROGRESS => 'В работе',
    ];

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(length: 255)]
    private string $contactName;

    #[ORM\Column(length: 255)]
    private string $objectAddress;

    #[ORM\Column(length: 30)]
    private string $phone;

    #[ORM\Column(type: 'smallint')]
    private int $status = self::STATUS_NEW;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getStringifyId(): string
    {
        return $this->id->toRfc4122();
    }

    public function getContactName(): string
    {
        return $this->contactName;
    }

    public function setContactName(string $contactName): static
    {
        $this->contactName = $contactName;
        return $this;
    }

    public function getObjectAddress(): string
    {
        return $this->objectAddress;
    }

    public function setObjectAddress(string $objectAddress): static
    {
        $this->objectAddress = $objectAddress;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getStatusLabel(): string
    {
        return self::AVAILABLE_STATUSES[$this->status] ?? (string) $this->status;
    }

    public function __toString(): string
    {
        return sprintf(
            '#%s %s (статус: %s)',
            substr($this->getStringifyId(), 0, 8),
            $this->objectAddress,
            $this->getStatusLabel(),
        );
    }
}
