<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * add gield score to entity users
 */
class Version20161204132615 extends AbstractMigration
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
        
        if (!$schemaTable->hasColumn('score')) {
            $this->addSql('
                ALTER TABLE `users` 
                ADD `score` INT DEFAULT NULL
            ');
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

        if ($schemaTable->hasColumn('score')) {
            $this->addSql('
                ALTER TABLE `users` DROP `score`');
        }
    }
}
