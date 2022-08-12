<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220812120927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fiche_produit (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, marque_id INT DEFAULT NULL, matiere_id INT DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, prix INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_31A4B7FDBCF5E72D (categorie_id), INDEX IDX_31A4B7FD4827B9B2 (marque_id), INDEX IDX_31A4B7FDF46CD258 (matiere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fiche_produit ADD CONSTRAINT FK_31A4B7FDBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE fiche_produit ADD CONSTRAINT FK_31A4B7FD4827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
        $this->addSql('ALTER TABLE fiche_produit ADD CONSTRAINT FK_31A4B7FDF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_produit DROP FOREIGN KEY FK_31A4B7FDBCF5E72D');
        $this->addSql('ALTER TABLE fiche_produit DROP FOREIGN KEY FK_31A4B7FD4827B9B2');
        $this->addSql('ALTER TABLE fiche_produit DROP FOREIGN KEY FK_31A4B7FDF46CD258');
        $this->addSql('DROP TABLE fiche_produit');
    }
}
