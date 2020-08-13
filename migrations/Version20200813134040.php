<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200813134040 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apprenant ADD statut_id INT NOT NULL, ADD promotion_id INT NOT NULL, ADD attente TINYINT(1) NOT NULL, DROP email, DROP password');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462EF6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462E139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('CREATE INDEX IDX_C4EB462EF6203804 ON apprenant (statut_id)');
        $this->addSql('CREATE INDEX IDX_C4EB462E139DF194 ON apprenant (promotion_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462EF6203804');
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462E139DF194');
        $this->addSql('DROP INDEX IDX_C4EB462EF6203804 ON apprenant');
        $this->addSql('DROP INDEX IDX_C4EB462E139DF194 ON apprenant');
        $this->addSql('ALTER TABLE apprenant ADD email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP statut_id, DROP promotion_id, DROP attente');
    }
}
