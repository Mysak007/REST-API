<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220719004245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE follower (id INT AUTO_INCREMENT NOT NULL, follow_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', follower_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B9D609468711D3BC (follow_id), INDEX IDX_B9D60946AC24F853 (follower_id), UNIQUE INDEX unique_follower (follow_id, follower_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, nick VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649290B2F37 (nick), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE follower ADD CONSTRAINT FK_B9D609468711D3BC FOREIGN KEY (follow_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE follower ADD CONSTRAINT FK_B9D60946AC24F853 FOREIGN KEY (follower_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE follower DROP FOREIGN KEY FK_B9D609468711D3BC');
        $this->addSql('ALTER TABLE follower DROP FOREIGN KEY FK_B9D60946AC24F853');
        $this->addSql('DROP TABLE follower');
        $this->addSql('DROP TABLE `user`');
    }
}
