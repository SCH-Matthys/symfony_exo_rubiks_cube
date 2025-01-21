<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115153852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE colors (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, cubes_id INT DEFAULT NULL, content LONGTEXT NOT NULL, INDEX IDX_9474526C7172C281 (cubes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cubes (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, format VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price INT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_DE840A0412469DE2 (category_id), INDEX IDX_DE840A04A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cubes_colors (cubes_id INT NOT NULL, colors_id INT NOT NULL, INDEX IDX_ECB4FB917172C281 (cubes_id), INDEX IDX_ECB4FB915C002039 (colors_id), PRIMARY KEY(cubes_id, colors_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7172C281 FOREIGN KEY (cubes_id) REFERENCES cubes (id)');
        $this->addSql('ALTER TABLE cubes ADD CONSTRAINT FK_DE840A0412469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE cubes ADD CONSTRAINT FK_DE840A04A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cubes_colors ADD CONSTRAINT FK_ECB4FB917172C281 FOREIGN KEY (cubes_id) REFERENCES cubes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cubes_colors ADD CONSTRAINT FK_ECB4FB915C002039 FOREIGN KEY (colors_id) REFERENCES colors (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7172C281');
        $this->addSql('ALTER TABLE cubes DROP FOREIGN KEY FK_DE840A0412469DE2');
        $this->addSql('ALTER TABLE cubes DROP FOREIGN KEY FK_DE840A04A76ED395');
        $this->addSql('ALTER TABLE cubes_colors DROP FOREIGN KEY FK_ECB4FB917172C281');
        $this->addSql('ALTER TABLE cubes_colors DROP FOREIGN KEY FK_ECB4FB915C002039');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE colors');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE cubes');
        $this->addSql('DROP TABLE cubes_colors');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
