<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161203154213 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        if (!$schema->hasTable('users')) {
            $this->addSql('
                CREATE TABLE users (
                    `id` INT AUTO_INCREMENT NOT NULL, 
                    `role` TINYTEXT NOT NULL COMMENT \'(DC2Type:array)\', 
                    `slug` VARCHAR(255) NOT NULL, 
                    `password` VARCHAR(80) DEFAULT NULL, 
                    `first_name` VARCHAR(255) DEFAULT NULL, 
                    `last_name` VARCHAR(255) DEFAULT NULL, 
                    `email` VARCHAR(255) DEFAULT NULL, 
                    `created_at` DATETIME NOT NULL, 
                    `updated_at` DATETIME NOT NULL, 
                    `deleted_at` DATETIME DEFAULT NULL, 
                    UNIQUE INDEX UNIQ_1483A5E9E7927C74 (`email`), 
                    PRIMARY KEY(`id`)
                ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
            ');   
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        if ($schema->hasTable('users')) {
            $this->addSql('DROP TABLE `users`');   
        }
    }
}
