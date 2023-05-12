<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["expenseNote:read", "expenseNote:write"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["expenseNote:read", "expenseNote:write"])]
    private ?string $firstName = null;

    #[Assert\Email(
        message: 'The email "{{ value }}" is not a valid email.'
    )]
    #[Groups(["expenseNote:read", "expenseNote:write"])]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ExpenseNote::class)]
    private Collection $expenseNotes;

    public function __construct()
    {
        $this->expenseNotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return Collection<int, ExpenseNote>
     */
    public function getExpenseNotes(): Collection
    {
        return $this->expenseNotes;
    }

    public function addExpenseNote(ExpenseNote $expenseNote): self
    {
        if (!$this->expenseNotes->contains($expenseNote)) {
            $this->expenseNotes->add($expenseNote);
            $expenseNote->setUser($this);
        }

        return $this;
    }

    public function removeExpenseNote(ExpenseNote $expenseNote): self
    {
        if ($this->expenseNotes->removeElement($expenseNote)) {
            // set the owning side to null (unless already changed)
            if ($expenseNote->getUser() === $this) {
                $expenseNote->setUser(null);
            }
        }

        return $this;
    }
}
