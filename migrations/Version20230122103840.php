<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230122103840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, producer_id INT NOT NULL, product_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_D79572D989B658FE (producer_id), INDEX IDX_D79572D914959723 (product_type_id), UNIQUE INDEX name_producer_productType (name, producer_id, product_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE producer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_976449DC5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, model_id INT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, INDEX IDX_D34A04AD7975B7E7 (model_id), UNIQUE INDEX name_model (name, model_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_13675885E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D989B658FE FOREIGN KEY (producer_id) REFERENCES producer (id)');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D914959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD7975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D989B658FE');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D914959723');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD7975B7E7');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE producer');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_type');
    }
}
