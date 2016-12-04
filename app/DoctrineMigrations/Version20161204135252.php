<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161204135252 extends AbstractMigration
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

        $columns = [];
        if (!$schemaTable->hasColumn('start_learn')) {
            $columns[] = 'ADD `start_learn` DATETIME DEFAULT NULL';
        }

        if (!$schemaTable->hasColumn('end_learn')) {
            $columns[] = 'ADD `end_learn` DATETIME DEFAULT NULL';
        }

        if (count($columns)) {
            $query = sprintf('ALTER TABLE `users` %s', implode(', ', $columns));
            $this->addSql($query);
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

        $columns = [];
        if ($schemaTable->hasColumn('start_learn')) {
            $columns[] = 'DROP `start_learn`';
        }

        if ($schemaTable->hasColumn('end_learn')) {
            $columns[] = 'DROP `end_learn`';
        }

        if (count($columns)) {
            $query = sprintf('ALTER TABLE `users` %s', implode(', ', $columns));
            $this->addSql($query);
        }
    }
}
