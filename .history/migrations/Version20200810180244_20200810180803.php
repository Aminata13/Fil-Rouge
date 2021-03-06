<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200810180244 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_C4EB462E139DF194 ON apprenant (promotion_id)');
        $this->addSql('ALTER TABLE competence ADD deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE groupe_competence ADD deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE groupe_tag ADD deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE tag ADD deleted TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462E139DF194');
        $this->addSql('DROP INDEX IDX_C4EB462E139DF194 ON apprenant');
        $this->addSql('ALTER TABLE competence DROP deleted');
        $this->addSql('ALTER TABLE groupe_competence DROP deleted');
        $this->addSql('ALTER TABLE groupe_tag DROP deleted');
        $this->addSql('ALTER TABLE tag DROP deleted');
    }
}
