<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * add field second sum to entity users
 */
class Version20161204142804 extends AbstractMigration
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
        
        if (!$schemaTable->hasColumn('second_sum')) {
            $this->addSql('
                ALTER TABLE `users` 
                ADD `second_sum` INT DEFAULT NULL
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

        if ($schemaTable->hasColumn('second_sum')) {
            $this->addSql('ALTER TABLE `users` DROP `second_sum`');
        }
    }
}
