<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821112006 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apprenant CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE groupe ADD etat_brief_groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C217777C7A0 FOREIGN KEY (etat_brief_groupe_id) REFERENCES etat_brief_groupe (id)');
        $this->addSql('CREATE INDEX IDX_4B98C217777C7A0 ON groupe (etat_brief_groupe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apprenant CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C217777C7A0');
        $this->addSql('DROP INDEX IDX_4B98C217777C7A0 ON groupe');
        $this->addSql('ALTER TABLE groupe DROP etat_brief_groupe_id');
    }
}
