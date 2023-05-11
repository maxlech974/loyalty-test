<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230511215637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense_note ADD company_id INT NOT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE expense_note ADD CONSTRAINT FK_7B1AB476979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE expense_note ADD CONSTRAINT FK_7B1AB476A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_7B1AB476979B1AD6 ON expense_note (company_id)');
        $this->addSql('CREATE INDEX IDX_7B1AB476A76ED395 ON expense_note (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense_note DROP FOREIGN KEY FK_7B1AB476979B1AD6');
        $this->addSql('ALTER TABLE expense_note DROP FOREIGN KEY FK_7B1AB476A76ED395');
        $this->addSql('DROP INDEX IDX_7B1AB476979B1AD6 ON expense_note');
        $this->addSql('DROP INDEX IDX_7B1AB476A76ED395 ON expense_note');
        $this->addSql('ALTER TABLE expense_note DROP company_id, DROP user_id');
    }
}
