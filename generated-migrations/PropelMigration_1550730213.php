<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1550730213.
 * Generated on 2019-02-21 09:23:33 
 */
class PropelMigration_1550730213
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postDown(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `application`

  ADD `course_id` INTEGER AFTER `application_status_id`,

  ADD `course_stream_id` INTEGER AFTER `course_id`;

CREATE INDEX `application_fi_ebed28` ON `application` (`course_id`);

CREATE INDEX `application_fi_f39771` ON `application` (`course_stream_id`);

ALTER TABLE `application` ADD CONSTRAINT `application_fk_ebed28`
    FOREIGN KEY (`course_id`)
    REFERENCES `course` (`id`)
    ON DELETE SET NULL;

ALTER TABLE `application` ADD CONSTRAINT `application_fk_f39771`
    FOREIGN KEY (`course_stream_id`)
    REFERENCES `course_stream` (`id`)
    ON DELETE SET NULL;

ALTER TABLE `static_page`

  DROP `context`;

ALTER TABLE `user`

  CHANGE `first_name` `name` VARCHAR(32) NOT NULL,

  CHANGE `fb_social_id` `social_id` VARCHAR(255),

  CHANGE `g_social_id` `social_token` VARCHAR(255),

  ADD `about` TEXT AFTER `email`,

  ADD `cover_name` VARCHAR(32) AFTER `logo_name`,

  ADD `address` VARCHAR(100) AFTER `cover_name`,

  ADD `address_coordinates` TEXT AFTER `address`,

  ADD `currency_id` INTEGER AFTER `group_id`,

  DROP `last_name`,

  DROP `gender`;

CREATE INDEX `user_fi_16a5a4` ON `user` (`currency_id`);

ALTER TABLE `user` ADD CONSTRAINT `user_fk_16a5a4`
    FOREIGN KEY (`currency_id`)
    REFERENCES `currency` (`id`)
    ON DELETE SET NULL;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `application` DROP FOREIGN KEY `application_fk_ebed28`;

ALTER TABLE `application` DROP FOREIGN KEY `application_fk_f39771`;

DROP INDEX `application_fi_ebed28` ON `application`;

DROP INDEX `application_fi_f39771` ON `application`;

ALTER TABLE `application`

  DROP `course_id`,

  DROP `course_stream_id`;

ALTER TABLE `static_page`

  ADD `context` TEXT AFTER `is_available`;

ALTER TABLE `user` DROP FOREIGN KEY `user_fk_16a5a4`;

DROP INDEX `user_fi_16a5a4` ON `user`;

ALTER TABLE `user`

  CHANGE `name` `first_name` VARCHAR(32) NOT NULL,

  CHANGE `social_id` `fb_social_id` VARCHAR(255),

  CHANGE `social_token` `g_social_id` VARCHAR(255),

  ADD `last_name` VARCHAR(32) NOT NULL AFTER `first_name`,

  ADD `gender` TINYINT AFTER `last_name`,

  DROP `about`,

  DROP `cover_name`,

  DROP `address`,

  DROP `address_coordinates`,

  DROP `currency_id`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}