<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200820214155 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brief (id INT AUTO_INCREMENT NOT NULL, langue_id INT NOT NULL, referentiel_id INT NOT NULL, formateur_id INT NOT NULL, etat_brief_groupe_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, contexte LONGTEXT NOT NULL, modalite_pedagogique LONGTEXT NOT NULL, critere_performance LONGTEXT DEFAULT NULL, modalite_evaluation LONGTEXT DEFAULT NULL, image LONGBLOB DEFAULT NULL, date_creation DATE NOT NULL, INDEX IDX_1FBB10072AADBACD (langue_id), INDEX IDX_1FBB1007805DB139 (referentiel_id), INDEX IDX_1FBB1007155D8F51 (formateur_id), INDEX IDX_1FBB10077777C7A0 (etat_brief_groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_niveau_evaluation (brief_id INT NOT NULL, niveau_evaluation_id INT NOT NULL, INDEX IDX_9AA1D939757FABFF (brief_id), INDEX IDX_9AA1D93955CCA3C7 (niveau_evaluation_id), PRIMARY KEY(brief_id, niveau_evaluation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_tag (brief_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_452A4F36757FABFF (brief_id), INDEX IDX_452A4F36BAD26311 (tag_id), PRIMARY KEY(brief_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_apprenant (id INT AUTO_INCREMENT NOT NULL, statut_id INT DEFAULT NULL, brief_promotion_id INT NOT NULL, apprenant_id INT NOT NULL, INDEX IDX_DD6198EDF6203804 (statut_id), INDEX IDX_DD6198ED92A25B3B (brief_promotion_id), INDEX IDX_DD6198EDC5697D6D (apprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_promotion (id INT AUTO_INCREMENT NOT NULL, promotion_id INT DEFAULT NULL, brief_id INT DEFAULT NULL, statut_id INT NOT NULL, INDEX IDX_D897A312139DF194 (promotion_id), INDEX IDX_D897A312757FABFF (brief_id), INDEX IDX_D897A312F6203804 (statut_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, livrable_rendu_id INT NOT NULL, formateur_id INT DEFAULT NULL, libelle LONGTEXT NOT NULL, date DATETIME NOT NULL, piece_jointe LONGBLOB DEFAULT NULL, INDEX IDX_67F068BC9F3E86A9 (livrable_rendu_id), INDEX IDX_67F068BC155D8F51 (formateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competence_valide (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT NOT NULL, referentiel_id INT NOT NULL, competence_id INT NOT NULL, promotion_id INT DEFAULT NULL, niveau1 TINYINT(1) NOT NULL, niveau2 TINYINT(1) NOT NULL, niveau3 TINYINT(1) NOT NULL, INDEX IDX_8BB7F7FEC5697D6D (apprenant_id), INDEX IDX_8BB7F7FE805DB139 (referentiel_id), INDEX IDX_8BB7F7FE15761DAB (competence_id), INDEX IDX_8BB7F7FE139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat_brief_groupe (id INT AUTO_INCREMENT NOT NULL, statut_id INT NOT NULL, INDEX IDX_4C4C1AA4F6203804 (statut_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fil_discussion (id INT AUTO_INCREMENT NOT NULL, promotion_id INT NOT NULL, titre VARCHAR(255) NOT NULL, date DATETIME NOT NULL, UNIQUE INDEX UNIQ_C9EFF4FC139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_apprenant (id INT AUTO_INCREMENT NOT NULL, livrable_attendu_id INT NOT NULL, apprenant_id INT NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_B50C89F875180ACC (livrable_attendu_id), INDEX IDX_B50C89F8C5697D6D (apprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_attendu (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_attendu_brief (livrable_attendu_id INT NOT NULL, brief_id INT NOT NULL, INDEX IDX_778854ED75180ACC (livrable_attendu_id), INDEX IDX_778854ED757FABFF (brief_id), PRIMARY KEY(livrable_attendu_id, brief_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_partiel (id INT AUTO_INCREMENT NOT NULL, brief_promotion_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, date_affectation DATETIME NOT NULL, date_soumission DATETIME NOT NULL, INDEX IDX_37F072C592A25B3B (brief_promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_partiel_niveau_evaluation (livrable_partiel_id INT NOT NULL, niveau_evaluation_id INT NOT NULL, INDEX IDX_1FA3CCCC519178C4 (livrable_partiel_id), INDEX IDX_1FA3CCCC55CCA3C7 (niveau_evaluation_id), PRIMARY KEY(livrable_partiel_id, niveau_evaluation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_rendu (id INT AUTO_INCREMENT NOT NULL, statut_id INT NOT NULL, livrable_partiel_id INT DEFAULT NULL, apprenant_id INT NOT NULL, date_rendu DATETIME NOT NULL, delai DATETIME DEFAULT NULL, INDEX IDX_9033AB0FF6203804 (statut_id), INDEX IDX_9033AB0F519178C4 (livrable_partiel_id), INDEX IDX_9033AB0FC5697D6D (apprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_chat (id INT AUTO_INCREMENT NOT NULL, fil_discussion_id INT NOT NULL, user_id INT NOT NULL, libelle LONGTEXT NOT NULL, date DATETIME NOT NULL, piece_jointe LONGBLOB NOT NULL, INDEX IDX_CC0869739AFA941D (fil_discussion_id), INDEX IDX_CC086973A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource (id INT AUTO_INCREMENT NOT NULL, brief_id INT NOT NULL, url VARCHAR(255) DEFAULT NULL, piece_jointe LONGBLOB DEFAULT NULL, INDEX IDX_939F4544757FABFF (brief_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut_brief (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut_livrable (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brief ADD CONSTRAINT FK_1FBB10072AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE brief ADD CONSTRAINT FK_1FBB1007805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('ALTER TABLE brief ADD CONSTRAINT FK_1FBB1007155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id)');
        $this->addSql('ALTER TABLE brief ADD CONSTRAINT FK_1FBB10077777C7A0 FOREIGN KEY (etat_brief_groupe_id) REFERENCES etat_brief_groupe (id)');
        $this->addSql('ALTER TABLE brief_niveau_evaluation ADD CONSTRAINT FK_9AA1D939757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_niveau_evaluation ADD CONSTRAINT FK_9AA1D93955CCA3C7 FOREIGN KEY (niveau_evaluation_id) REFERENCES niveau_evaluation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_tag ADD CONSTRAINT FK_452A4F36757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_tag ADD CONSTRAINT FK_452A4F36BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_apprenant ADD CONSTRAINT FK_DD6198EDF6203804 FOREIGN KEY (statut_id) REFERENCES statut_brief (id)');
        $this->addSql('ALTER TABLE brief_apprenant ADD CONSTRAINT FK_DD6198ED92A25B3B FOREIGN KEY (brief_promotion_id) REFERENCES brief_promotion (id)');
        $this->addSql('ALTER TABLE brief_apprenant ADD CONSTRAINT FK_DD6198EDC5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE brief_promotion ADD CONSTRAINT FK_D897A312139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE brief_promotion ADD CONSTRAINT FK_D897A312757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id)');
        $this->addSql('ALTER TABLE brief_promotion ADD CONSTRAINT FK_D897A312F6203804 FOREIGN KEY (statut_id) REFERENCES statut_brief (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC9F3E86A9 FOREIGN KEY (livrable_rendu_id) REFERENCES livrable_rendu (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id)');
        $this->addSql('ALTER TABLE competence_valide ADD CONSTRAINT FK_8BB7F7FEC5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE competence_valide ADD CONSTRAINT FK_8BB7F7FE805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('ALTER TABLE competence_valide ADD CONSTRAINT FK_8BB7F7FE15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id)');
        $this->addSql('ALTER TABLE competence_valide ADD CONSTRAINT FK_8BB7F7FE139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE etat_brief_groupe ADD CONSTRAINT FK_4C4C1AA4F6203804 FOREIGN KEY (statut_id) REFERENCES statut_brief (id)');
        $this->addSql('ALTER TABLE fil_discussion ADD CONSTRAINT FK_C9EFF4FC139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE livrable_apprenant ADD CONSTRAINT FK_B50C89F875180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendu (id)');
        $this->addSql('ALTER TABLE livrable_apprenant ADD CONSTRAINT FK_B50C89F8C5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE livrable_attendu_brief ADD CONSTRAINT FK_778854ED75180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_attendu_brief ADD CONSTRAINT FK_778854ED757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_partiel ADD CONSTRAINT FK_37F072C592A25B3B FOREIGN KEY (brief_promotion_id) REFERENCES brief_promotion (id)');
        $this->addSql('ALTER TABLE livrable_partiel_niveau_evaluation ADD CONSTRAINT FK_1FA3CCCC519178C4 FOREIGN KEY (livrable_partiel_id) REFERENCES livrable_partiel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_partiel_niveau_evaluation ADD CONSTRAINT FK_1FA3CCCC55CCA3C7 FOREIGN KEY (niveau_evaluation_id) REFERENCES niveau_evaluation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_rendu ADD CONSTRAINT FK_9033AB0FF6203804 FOREIGN KEY (statut_id) REFERENCES statut_livrable (id)');
        $this->addSql('ALTER TABLE livrable_rendu ADD CONSTRAINT FK_9033AB0F519178C4 FOREIGN KEY (livrable_partiel_id) REFERENCES livrable_partiel (id)');
        $this->addSql('ALTER TABLE livrable_rendu ADD CONSTRAINT FK_9033AB0FC5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE message_chat ADD CONSTRAINT FK_CC0869739AFA941D FOREIGN KEY (fil_discussion_id) REFERENCES fil_discussion (id)');
        $this->addSql('ALTER TABLE message_chat ADD CONSTRAINT FK_CC086973A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id)');
        $this->addSql('ALTER TABLE apprenant DROP email, DROP password, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE groupe ADD etat_brief_groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C217777C7A0 FOREIGN KEY (etat_brief_groupe_id) REFERENCES etat_brief_groupe (id)');
        $this->addSql('CREATE INDEX IDX_4B98C217777C7A0 ON groupe (etat_brief_groupe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brief_niveau_evaluation DROP FOREIGN KEY FK_9AA1D939757FABFF');
        $this->addSql('ALTER TABLE brief_tag DROP FOREIGN KEY FK_452A4F36757FABFF');
        $this->addSql('ALTER TABLE brief_promotion DROP FOREIGN KEY FK_D897A312757FABFF');
        $this->addSql('ALTER TABLE livrable_attendu_brief DROP FOREIGN KEY FK_778854ED757FABFF');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F4544757FABFF');
        $this->addSql('ALTER TABLE brief_apprenant DROP FOREIGN KEY FK_DD6198ED92A25B3B');
        $this->addSql('ALTER TABLE livrable_partiel DROP FOREIGN KEY FK_37F072C592A25B3B');
        $this->addSql('ALTER TABLE brief DROP FOREIGN KEY FK_1FBB10077777C7A0');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C217777C7A0');
        $this->addSql('ALTER TABLE message_chat DROP FOREIGN KEY FK_CC0869739AFA941D');
        $this->addSql('ALTER TABLE livrable_apprenant DROP FOREIGN KEY FK_B50C89F875180ACC');
        $this->addSql('ALTER TABLE livrable_attendu_brief DROP FOREIGN KEY FK_778854ED75180ACC');
        $this->addSql('ALTER TABLE livrable_partiel_niveau_evaluation DROP FOREIGN KEY FK_1FA3CCCC519178C4');
        $this->addSql('ALTER TABLE livrable_rendu DROP FOREIGN KEY FK_9033AB0F519178C4');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC9F3E86A9');
        $this->addSql('ALTER TABLE brief_apprenant DROP FOREIGN KEY FK_DD6198EDF6203804');
        $this->addSql('ALTER TABLE brief_promotion DROP FOREIGN KEY FK_D897A312F6203804');
        $this->addSql('ALTER TABLE etat_brief_groupe DROP FOREIGN KEY FK_4C4C1AA4F6203804');
        $this->addSql('ALTER TABLE livrable_rendu DROP FOREIGN KEY FK_9033AB0FF6203804');
        $this->addSql('DROP TABLE brief');
        $this->addSql('DROP TABLE brief_niveau_evaluation');
        $this->addSql('DROP TABLE brief_tag');
        $this->addSql('DROP TABLE brief_apprenant');
        $this->addSql('DROP TABLE brief_promotion');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE competence_valide');
        $this->addSql('DROP TABLE etat_brief_groupe');
        $this->addSql('DROP TABLE fil_discussion');
        $this->addSql('DROP TABLE livrable_apprenant');
        $this->addSql('DROP TABLE livrable_attendu');
        $this->addSql('DROP TABLE livrable_attendu_brief');
        $this->addSql('DROP TABLE livrable_partiel');
        $this->addSql('DROP TABLE livrable_partiel_niveau_evaluation');
        $this->addSql('DROP TABLE livrable_rendu');
        $this->addSql('DROP TABLE message_chat');
        $this->addSql('DROP TABLE ressource');
        $this->addSql('DROP TABLE statut_brief');
        $this->addSql('DROP TABLE statut_livrable');
        $this->addSql('ALTER TABLE apprenant ADD email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_4B98C217777C7A0 ON groupe');
        $this->addSql('ALTER TABLE groupe DROP etat_brief_groupe_id');
    }
}
