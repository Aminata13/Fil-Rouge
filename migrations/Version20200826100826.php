<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826100826 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brief DROP FOREIGN KEY FK_1FBB10077777C7A0');
        $this->addSql('DROP INDEX IDX_1FBB10077777C7A0 ON brief');
        $this->addSql('ALTER TABLE brief DROP etat_brief_groupe_id');
        $this->addSql('ALTER TABLE etat_brief_groupe ADD brief_id INT NOT NULL, ADD groupe_id INT NOT NULL');
        $this->addSql('ALTER TABLE etat_brief_groupe ADD CONSTRAINT FK_4C4C1AA4757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id)');
        $this->addSql('ALTER TABLE etat_brief_groupe ADD CONSTRAINT FK_4C4C1AA47A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_4C4C1AA4757FABFF ON etat_brief_groupe (brief_id)');
        $this->addSql('CREATE INDEX IDX_4C4C1AA47A45358C ON etat_brief_groupe (groupe_id)');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C217777C7A0');
        $this->addSql('DROP INDEX IDX_4B98C217777C7A0 ON groupe');
        $this->addSql('ALTER TABLE groupe DROP etat_brief_groupe_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brief ADD etat_brief_groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE brief ADD CONSTRAINT FK_1FBB10077777C7A0 FOREIGN KEY (etat_brief_groupe_id) REFERENCES etat_brief_groupe (id)');
        $this->addSql('CREATE INDEX IDX_1FBB10077777C7A0 ON brief (etat_brief_groupe_id)');
        $this->addSql('ALTER TABLE etat_brief_groupe DROP FOREIGN KEY FK_4C4C1AA4757FABFF');
        $this->addSql('ALTER TABLE etat_brief_groupe DROP FOREIGN KEY FK_4C4C1AA47A45358C');
        $this->addSql('DROP INDEX IDX_4C4C1AA4757FABFF ON etat_brief_groupe');
        $this->addSql('DROP INDEX IDX_4C4C1AA47A45358C ON etat_brief_groupe');
        $this->addSql('ALTER TABLE etat_brief_groupe DROP brief_id, DROP groupe_id');
        $this->addSql('ALTER TABLE groupe ADD etat_brief_groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C217777C7A0 FOREIGN KEY (etat_brief_groupe_id) REFERENCES etat_brief_groupe (id)');
        $this->addSql('CREATE INDEX IDX_4B98C217777C7A0 ON groupe (etat_brief_groupe_id)');
    }
}
