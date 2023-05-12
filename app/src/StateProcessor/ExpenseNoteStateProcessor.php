<?php
namespace App\StateProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DTO\ExpenseNoteDto;
use App\Entity\ExpenseNote;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use App\Repository\ExpenseNoteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ExpenseNoteStateProcessor implements ProcessorInterface
{
    // Format de date attendu pour noteDate
    private const DATE_FORMAT = 'd/m/Y';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private CompanyRepository $companyRepository,
        private UserRepository $userRepository,
        private ExpenseNoteRepository $expenseNoteRepository
    ) {}

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // Vérifie si les données entrantes sont une instance de ExpenseNoteDto
        if ($data instanceof ExpenseNoteDto) {
            // Récupère le premier utilisateur
            $user = $this->userRepository->findOneBy([]);
            if (!$user) {
                throw new \RuntimeException("No users found", 400);
            }

            // Convertit la date de string à DateTime
            $date = \DateTime::createFromFormat(self::DATE_FORMAT, $data->noteDate);
            if ($date === false) {
                throw new \InvalidArgumentException("Invalid date format, expected '".self::DATE_FORMAT."'");
            } elseif( $date > new \DateTime() ) {
                throw new \InvalidArgumentException("Invalid date, must be in the past");
            }

            // Convertit le montant de string à float
            $amount = (float) str_replace(',', '.', $data->amount);
            if ($amount <= 0) {
                throw new \InvalidArgumentException("Invalid amount, must be a positive number");
            }

            // Récupère l'entreprise par son nom
            $company = $this->companyRepository->findOneBy(['companyName' => $data->companyName]);
            if (!$company) {
                $company = new Company();
                $company->setCompanyName($data->companyName);
                $this->entityManager->persist($company);
            }

            // Détermine le type de méthode (POST, PUT)
            $method = $operation->getMethod();
            $expenseNote = null;
            if( $method === 'PUT'){
                // On récupère la note de dépense existante
                $expenseNote = $this->expenseNoteRepository->find($uriVariables['id']);
                if (!$expenseNote) {
                    throw new \Exception("ExpenseNote not found", 404);
                }
            } elseif( $method === 'POST' ) {
                // Pour POST, crée une nouvelle note de dépense
                $expenseNote = new ExpenseNote();
                $expenseNote->setRegistrationDate(new \DateTime());
            }

            // Met à jour les propriétés de la note de dépense
            $expenseNote->setUser($user);
            $expenseNote->setNoteDate($date);
            $expenseNote->setAmount($amount);
            $expenseNote->setType($data->type);
            $expenseNote->setCompany($company);

            // Persiste et sauvegarde les modifications
            $this->entityManager->persist($expenseNote);
            $this->entityManager->flush();

            // Retourne la note de dépense traitée
            return $expenseNote;
        }

        throw new \InvalidArgumentException(sprintf('The object must be an instance of "%s".', ExpenseNoteDto::class));
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof ExpenseNoteDto;
    }
}