<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180807094038 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX uniq_fa49171755ab88b');
        $this->addSql('DROP INDEX uniq_fa49171777e8715e');
        $this->addSql('ALTER TABLE marketreminder_stock ALTER creation_date TYPE DATE');
        $this->addSql('ALTER TABLE marketreminder_stock ALTER creation_date DROP DEFAULT');
        $this->addSql('ALTER TABLE marketreminder_stock ALTER modification_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE marketreminder_stock ALTER modification_date DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE marketReminder_stock ALTER creation_date TYPE INT');
        $this->addSql('ALTER TABLE marketReminder_stock ALTER creation_date DROP DEFAULT');
        $this->addSql('ALTER TABLE marketReminder_stock ALTER modification_date TYPE INT');
        $this->addSql('ALTER TABLE marketReminder_stock ALTER modification_date DROP DEFAULT');
        $this->addSql('CREATE UNIQUE INDEX uniq_fa49171755ab88b ON marketReminder_stock (modification_date)');
        $this->addSql('CREATE UNIQUE INDEX uniq_fa49171777e8715e ON marketReminder_stock (creation_date)');
    }
}
