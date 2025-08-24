<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250824104524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE colors (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filters (id INT AUTO_INCREMENT NOT NULL, productId INT NOT NULL, categoryId INT NOT NULL, type INT NOT NULL, INDEX product_id_search_idx (productId), INDEX category_id_search_idx (categoryId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filter_color (filter_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_4FE7BB16D395B25E (filter_id), INDEX IDX_4FE7BB167ADA1FB5 (color_id), PRIMARY KEY(filter_id, color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE filter_color ADD CONSTRAINT FK_4FE7BB16D395B25E FOREIGN KEY (filter_id) REFERENCES filters (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE filter_color ADD CONSTRAINT FK_4FE7BB167ADA1FB5 FOREIGN KEY (color_id) REFERENCES colors (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE filter_color DROP FOREIGN KEY FK_4FE7BB16D395B25E');
        $this->addSql('ALTER TABLE filter_color DROP FOREIGN KEY FK_4FE7BB167ADA1FB5');
        $this->addSql('DROP TABLE colors');
        $this->addSql('DROP TABLE filters');
        $this->addSql('DROP TABLE filter_color');
    }
}
