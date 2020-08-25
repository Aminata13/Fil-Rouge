<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200824144711 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brief CHANGE etat_brief_groupe_id etat_brief_groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_partiel ADD deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE livrable_rendu CHANGE date_rendu date_rendu DATETIME DEFAULT NULL, CHANGE delai delai DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brief CHANGE etat_brief_groupe_id etat_brief_groupe_id INT NOT NULL');
        $this->addSql('ALTER TABLE livrable_partiel DROP deleted');
        $this->addSql('ALTER TABLE livrable_rendu CHANGE date_rendu date_rendu DATETIME NOT NULL, CHANGE delai delai DATETIME DEFAULT NULL');
    }
}
