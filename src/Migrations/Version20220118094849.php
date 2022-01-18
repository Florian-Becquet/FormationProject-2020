<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220118094849 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sessions (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, nb_min INT NOT NULL, nb_max INT NOT NULL, type VARCHAR(255) NOT NULL, prix INT DEFAULT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, longitude NUMERIC(10, 9) NOT NULL, latitude NUMERIC(10, 8) NOT NULL, nb_participants INT NOT NULL, UNIQUE INDEX UNIQ_9A609D13A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, commentaire LONGTEXT NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_5F9E962A613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dons (id INT AUTO_INCREMENT NOT NULL, montant INT NOT NULL, date_donation DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, tel INT NOT NULL, date_inscription DATE NOT NULL, type VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, date_naiss DATE NOT NULL, nom_societe VARCHAR(255) DEFAULT NULL, nom_assoc VARCHAR(255) DEFAULT NULL, roles JSON DEFAULT NULL, activation_token VARCHAR(50) DEFAULT NULL, reset_token VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_sessions (users_id INT NOT NULL, sessions_id INT NOT NULL, INDEX IDX_E121B6C967B3B43D (users_id), INDEX IDX_E121B6C9F17C4D8C (sessions_id), PRIMARY KEY(users_id, sessions_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sessions ADD CONSTRAINT FK_9A609D13A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A613FECDF FOREIGN KEY (session_id) REFERENCES sessions (id)');
        $this->addSql('ALTER TABLE users_sessions ADD CONSTRAINT FK_E121B6C967B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_sessions ADD CONSTRAINT FK_E121B6C9F17C4D8C FOREIGN KEY (sessions_id) REFERENCES sessions (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A613FECDF');
        $this->addSql('ALTER TABLE users_sessions DROP FOREIGN KEY FK_E121B6C9F17C4D8C');
        $this->addSql('ALTER TABLE sessions DROP FOREIGN KEY FK_9A609D13A76ED395');
        $this->addSql('ALTER TABLE users_sessions DROP FOREIGN KEY FK_E121B6C967B3B43D');
        $this->addSql('DROP TABLE sessions');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE dons');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_sessions');
    }
}
