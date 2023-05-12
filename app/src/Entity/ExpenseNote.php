<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\DTO\ExpenseNoteDto;
use App\Repository\ExpenseNoteRepository;
use App\StateProcessor\ExpenseNoteStateProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExpenseNoteRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['expenseNote:read']],
    denormalizationContext: ['groups' => ['expenseNote:write', 'expenseNote:input']], 
)]
#[Post(
    input: ExpenseNoteDto::class,
    processor: ExpenseNoteStateProcessor::class
)]
#[Put(
    input: ExpenseNoteDto::class,
    processor: ExpenseNoteStateProcessor::class
)]
#[GetCollection()]
#[Get()]
#[Delete()]
class ExpenseNote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["expenseNote:read", "expenseNote:write"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(["expenseNote:read", "expenseNote:write"])]
    private ?\DateTimeInterface $noteDate = null;

    #[Assert\GreaterThan(0, message: "The amount must be a positive number greater than zero.")]
    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2)]
    //#[Groups(["expenseNote:read", "expenseNote:write"])]
    private ?string $amount = null;

    #[ORM\Column(length: 255)]
    #[Groups(["expenseNote:read", "expenseNote:write"])]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(["expenseNote:read", "expenseNote:write"])]
    private ?\DateTimeInterface $registrationDate = null;

    #[ORM\ManyToOne(inversedBy: 'expenseNotes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["expenseNote:read", "expenseNote:write"])]
    private ?Company $company = null;

    #[ORM\ManyToOne(inversedBy: 'expenseNotes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["expenseNote:read", "expenseNote:write"])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoteDate(): ?\DateTimeInterface
    {
        return $this->noteDate;
    }

    public function setNoteDate(\DateTimeInterface $noteDate): self
    {
        $this->noteDate = $noteDate;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    #[ORM\PrePersist]
    public function setRegistrationDate(\DateTimeInterface $registrationDate): self
    {
        if ($this->registrationDate === null) {
            $this->registrationDate = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
