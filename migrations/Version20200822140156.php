<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200822140156 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etat_brief (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brief ADD etat_brief_id INT NOT NULL');
        $this->addSql('ALTER TABLE brief ADD CONSTRAINT FK_1FBB1007E8AA036F FOREIGN KEY (etat_brief_id) REFERENCES etat_brief (id)');
        $this->addSql('CREATE INDEX IDX_1FBB1007E8AA036F ON brief (etat_brief_id)');
        $this->addSql('ALTER TABLE message_chat CHANGE piece_jointe piece_jointe LONGBLOB DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brief DROP FOREIGN KEY FK_1FBB1007E8AA036F');
        $this->addSql('DROP TABLE etat_brief');
        $this->addSql('DROP INDEX IDX_1FBB1007E8AA036F ON brief');
        $this->addSql('ALTER TABLE brief DROP etat_brief_id');
        $this->addSql('ALTER TABLE message_chat CHANGE piece_jointe piece_jointe LONGBLOB NOT NULL');
    }
}
