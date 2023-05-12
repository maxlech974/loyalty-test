<?php


namespace App\DTO;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class ExpenseNoteDto
{
    #[Assert\NotBlank]
    #[Groups('expenseNote:input')]
    public $noteDate;

    #[Assert\Positive]
    #[Assert\NotBlank]
    #[Groups('expenseNote:input')]
    public $amount;

    #[Assert\NotBlank]
    #[Groups('expenseNote:input')]
    public $type;

    #[Assert\NotBlank]
    #[Groups('expenseNote:input')]
    public $companyName;
}