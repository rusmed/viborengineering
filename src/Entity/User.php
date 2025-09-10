<?php

namespace App\Entity;

use App\Entity\Traits\TimeStampableTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[ORM\HasLifecycleCallbacks]
class User
{
    use TimeStampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    #[ORM\Column(length: 255)]
    private ?string $patronymic = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $phone = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Project::class)]
    private Collection $projects;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: WorkRequest::class)]
    private Collection $workRequests;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->workRequests = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getStringifyId(): string
    {
        return $this->id->toRfc4122();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(string $patronymic): static
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setUser($this);
        }
        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            if ($project->getUser() === $this) {
                $project->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, WorkRequest>
     */
    public function getWorkRequests(): Collection
    {
        return $this->workRequests;
    }

    public function addWorkRequest(WorkRequest $wr): static
    {
        if (!$this->workRequests->contains($wr)) {
            $this->workRequests->add($wr);
            $wr->setUser($this);
        }
        return $this;
    }

    public function removeWorkRequest(WorkRequest $wr): static
    {
        if ($this->workRequests->removeElement($wr)) {
            if ($wr->getUser() === $this) {
                $wr->setUser(null);
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        $parts = array_filter([
            $this->surname ?? null,
            $this->name ?? null,
            $this->patronymic ?? null,
        ]);
        $label = trim(implode(' ', $parts));
        if ($this->phone !== null) {
            $label = $label !== '' ? $label . ' (' . $this->phone . ')' : (string)$this->phone;
        }
        return $label !== '' ? $label : 'User #' . ($this->getStringifyId() ?? '');
    }
}
