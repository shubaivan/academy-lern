<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * add filed name to entity user
 */
class Version20161204164828 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->skipIf(
            !$schema->hasTable('users'),
            'Table users doesn\'t exist'
        );

        $schemaTable = $schema->getTable('users');

        if (!$schemaTable->hasColumn('name')) {
            $this->addSql('ALTER TABLE `users` ADD `name` VARCHAR(255) DEFAULT NULL');
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->skipIf(
            !$schema->hasTable('users'),
            'Table users doesn\'t exist'
        );

        $schemaTable = $schema->getTable('users');

        if ($schemaTable->hasColumn('name')) {
            $this->addSql('ALTER TABLE `users` DROP `name`');
        }
    }
}
