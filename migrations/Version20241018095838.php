<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241018095838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, question_id INT NOT NULL, commentaire_parent_id INT DEFAULT NULL, commentaire_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_67F068BCA76ED395 (user_id), INDEX IDX_67F068BC1E27F6BF (question_id), INDEX IDX_67F068BCFDED4547 (commentaire_parent_id), INDEX IDX_67F068BCBA9CD190 (commentaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE element (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, nom_scientifique VARCHAR(150) NOT NULL, famille VARCHAR(150) NOT NULL, hauteur VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, sol VARCHAR(150) NOT NULL, resume LONGTEXT NOT NULL, entretien LONGTEXT NOT NULL, rotation_des_cultures LONGTEXT NOT NULL, conservation LONGTEXT NOT NULL, contre_indication LONGTEXT NOT NULL, informations_nutritionnelles LONGTEXT NOT NULL, benefices LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE element_ami (element_source INT NOT NULL, element_target INT NOT NULL, INDEX IDX_5A1277BD69D76E7 (element_source), INDEX IDX_5A1277BCF782668 (element_target), PRIMARY KEY(element_source, element_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE element_ennemi (element_source INT NOT NULL, element_target INT NOT NULL, INDEX IDX_3736BA1FD69D76E7 (element_source), INDEX IDX_3736BA1FCF782668 (element_target), PRIMARY KEY(element_source, element_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE element_type_element (element_id INT NOT NULL, type_element_id INT NOT NULL, INDEX IDX_C1AC48C11F1F2A24 (element_id), INDEX IDX_C1AC48C121CFC01 (type_element_id), PRIMARY KEY(element_id, type_element_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etape (id INT AUTO_INCREMENT NOT NULL, element_id INT NOT NULL, type_etape_id INT NOT NULL, mois LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', periode VARCHAR(255) NOT NULL, instructions LONGTEXT NOT NULL, INDEX IDX_285F75DD1F1F2A24 (element_id), INDEX IDX_285F75DD87738551 (type_etape_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, date_publication DATETIME NOT NULL, INDEX IDX_B6F7494EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, conseil LONGTEXT DEFAULT NULL, nombre_de_personne INT NOT NULL, ingredients LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', temps_de_preparation INT NOT NULL, temps_de_cuisson INT NOT NULL, etapes LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', slug VARCHAR(255) NOT NULL, INDEX IDX_49BB6390A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_element (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_etape (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, pseudo VARCHAR(50) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCFDED4547 FOREIGN KEY (commentaire_parent_id) REFERENCES commentaire (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCBA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id)');
        $this->addSql('ALTER TABLE element_ami ADD CONSTRAINT FK_5A1277BD69D76E7 FOREIGN KEY (element_source) REFERENCES element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE element_ami ADD CONSTRAINT FK_5A1277BCF782668 FOREIGN KEY (element_target) REFERENCES element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE element_ennemi ADD CONSTRAINT FK_3736BA1FD69D76E7 FOREIGN KEY (element_source) REFERENCES element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE element_ennemi ADD CONSTRAINT FK_3736BA1FCF782668 FOREIGN KEY (element_target) REFERENCES element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE element_type_element ADD CONSTRAINT FK_C1AC48C11F1F2A24 FOREIGN KEY (element_id) REFERENCES element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE element_type_element ADD CONSTRAINT FK_C1AC48C121CFC01 FOREIGN KEY (type_element_id) REFERENCES type_element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT FK_285F75DD1F1F2A24 FOREIGN KEY (element_id) REFERENCES element (id)');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT FK_285F75DD87738551 FOREIGN KEY (type_etape_id) REFERENCES type_etape (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC1E27F6BF');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCFDED4547');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCBA9CD190');
        $this->addSql('ALTER TABLE element_ami DROP FOREIGN KEY FK_5A1277BD69D76E7');
        $this->addSql('ALTER TABLE element_ami DROP FOREIGN KEY FK_5A1277BCF782668');
        $this->addSql('ALTER TABLE element_ennemi DROP FOREIGN KEY FK_3736BA1FD69D76E7');
        $this->addSql('ALTER TABLE element_ennemi DROP FOREIGN KEY FK_3736BA1FCF782668');
        $this->addSql('ALTER TABLE element_type_element DROP FOREIGN KEY FK_C1AC48C11F1F2A24');
        $this->addSql('ALTER TABLE element_type_element DROP FOREIGN KEY FK_C1AC48C121CFC01');
        $this->addSql('ALTER TABLE etape DROP FOREIGN KEY FK_285F75DD1F1F2A24');
        $this->addSql('ALTER TABLE etape DROP FOREIGN KEY FK_285F75DD87738551');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EA76ED395');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390A76ED395');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE element');
        $this->addSql('DROP TABLE element_ami');
        $this->addSql('DROP TABLE element_ennemi');
        $this->addSql('DROP TABLE element_type_element');
        $this->addSql('DROP TABLE etape');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE recette');
        $this->addSql('DROP TABLE type_element');
        $this->addSql('DROP TABLE type_etape');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
