<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220904141405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sylius_newsletter_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, subject VARCHAR(255) NOT NULL, content TINYTEXT NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_2AB9280F2C2AC5D3 (translatable_id), UNIQUE INDEX sylius_newsletter_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_newsletter_translation ADD CONSTRAINT FK_2AB9280F2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_newsletter (id) ON DELETE CASCADE');
        $this->addSql('INSERT INTO sylius_newsletter_translation(translatable_id, subject, content, locale) SELECT id, subject, content, \'en_US\' AS locale FROM sylius_newsletter');
        $this->addSql('ALTER TABLE sylius_newsletter DROP subject, DROP content');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sylius_newsletter_translation');
        $this->addSql('ALTER TABLE sylius_newsletter ADD subject VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD content TINYTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
