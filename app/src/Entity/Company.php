<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["expenseNote:read", "expenseNote:write"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["expenseNote:read", "expenseNote:write"])]
    private ?string $companyName = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: ExpenseNote::class, orphanRemoval: true)]
    private Collection $expenseNotes;

    public function __construct()
    {
        $this->expenseNotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

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
            $expenseNote->setCompany($this);
        }

        return $this;
    }

    public function removeExpenseNote(ExpenseNote $expenseNote): self
    {
        if ($this->expenseNotes->removeElement($expenseNote)) {
            // set the owning side to null (unless already changed)
            if ($expenseNote->getCompany() === $this) {
                $expenseNote->setCompany(null);
            }
        }

        return $this;
    }
}
