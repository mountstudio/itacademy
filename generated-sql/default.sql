
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- group
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `group`;

CREATE TABLE `group`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `alt` VARCHAR(15) NOT NULL,
    `name` VARCHAR(32) NOT NULL,
    `allow_choose_group` TINYINT(1) DEFAULT 0 NOT NULL,
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- privilege
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `privilege`;

CREATE TABLE `privilege`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `alt` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- group_privilege
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `group_privilege`;

CREATE TABLE `group_privilege`
(
    `group_id` INTEGER NOT NULL,
    `privilege_id` INTEGER NOT NULL,
    `sortable_rank` INTEGER,
    PRIMARY KEY (`group_id`,`privilege_id`),
    INDEX `group_privilege_fi_da39c7` (`privilege_id`),
    CONSTRAINT `group_privilege_fk_0278b4`
        FOREIGN KEY (`group_id`)
        REFERENCES `group` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `group_privilege_fk_da39c7`
        FOREIGN KEY (`privilege_id`)
        REFERENCES `privilege` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- admin_style
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `admin_style`;

CREATE TABLE `admin_style`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `b_layout` TINYINT(1) DEFAULT 0 NOT NULL,
    `c_menu` TINYINT(1) DEFAULT 0 NOT NULL,
    `f_header` TINYINT(1) DEFAULT 1 NOT NULL,
    `f_sidebar` TINYINT(1) DEFAULT 0 NOT NULL,
    `h_bar` TINYINT(1) DEFAULT 0 NOT NULL,
    `h_menu` TINYINT(1) DEFAULT 0 NOT NULL,
    `t_sidebar` TINYINT(1) DEFAULT 0 NOT NULL,
    `custom_style` VARCHAR(15) DEFAULT 'green' NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- verification_token
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `verification_token`;

CREATE TABLE `verification_token`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(254),
    `token` VARCHAR(100) NOT NULL,
    `type` TINYINT(1) DEFAULT 1 NOT NULL,
    `expires_at` DATETIME,
    `user_id` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `verification_token_fi_29554a` (`user_id`),
    CONSTRAINT `verification_token_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(32) NOT NULL,
    `user_name` VARCHAR(32),
    `email` VARCHAR(128),
    `about` TEXT,
    `birth_date` DATE,
    `password` VARCHAR(100),
    `phone` VARCHAR(12),
    `logo_name` VARCHAR(32),
    `cover_name` VARCHAR(32),
    `address` VARCHAR(100),
    `address_coordinates` TEXT,
    `is_activated` TINYINT(1) DEFAULT 0 NOT NULL,
    `social_id` VARCHAR(255),
    `social_token` VARCHAR(255),
    `group_id` INTEGER NOT NULL,
    `currency_id` INTEGER,
    `admin_style_id` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `user_fi_0278b4` (`group_id`),
    INDEX `user_fi_16a5a4` (`currency_id`),
    INDEX `user_fi_4b365c` (`admin_style_id`),
    CONSTRAINT `user_fk_0278b4`
        FOREIGN KEY (`group_id`)
        REFERENCES `group` (`id`),
    CONSTRAINT `user_fk_16a5a4`
        FOREIGN KEY (`currency_id`)
        REFERENCES `currency` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `user_fk_4b365c`
        FOREIGN KEY (`admin_style_id`)
        REFERENCES `admin_style` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- application_status
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `application_status`;

CREATE TABLE `application_status`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(20),
    `description` VARCHAR(300),
    `background_color` VARCHAR(30),
    `font_color` VARCHAR(30),
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- application
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `application`;

CREATE TABLE `application`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(60),
    `phone` VARCHAR(20),
    `application_status_id` INTEGER NOT NULL,
    `course_id` INTEGER,
    `course_stream_id` INTEGER,
    `description` VARCHAR(300),
    `notes` VARCHAR(300) COMMENT 'for customers',
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `application_fi_0ebdd7` (`application_status_id`),
    INDEX `application_fi_ebed28` (`course_id`),
    INDEX `application_fi_f39771` (`course_stream_id`),
    CONSTRAINT `application_fk_0ebdd7`
        FOREIGN KEY (`application_status_id`)
        REFERENCES `application_status` (`id`),
    CONSTRAINT `application_fk_ebed28`
        FOREIGN KEY (`course_id`)
        REFERENCES `course` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `application_fk_f39771`
        FOREIGN KEY (`course_stream_id`)
        REFERENCES `course_stream` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- faq
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `faq`;

CREATE TABLE `faq`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `question` VARCHAR(255),
    `answer` TEXT,
    `sortable_rank` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- course_stream_status
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `course_stream_status`;

CREATE TABLE `course_stream_status`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(20),
    `description` VARCHAR(300),
    `background_color` VARCHAR(30),
    `font_color` VARCHAR(30),
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- course
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `course`;

CREATE TABLE `course`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(20),
    `description` TEXT,
    `alt_url` VARCHAR(255) NOT NULL,
    `logo_name` VARCHAR(255),
    `cover_name` VARCHAR(255),
    `title` VARCHAR(255) NOT NULL,
    `context` TEXT,
    `notes` VARCHAR(300),
    `use_notes` VARCHAR(700) NOT NULL,
    `uses` MEDIUMBLOB,
    `meta_description` TEXT,
    `meta_keywords` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- course_stream
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `course_stream`;

CREATE TABLE `course_stream`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(20),
    `description` TEXT,
    `number_of_places` INTEGER NOT NULL,
    `notes` VARCHAR(300),
    `starts_at` DATE NOT NULL,
    `ends_at` DATE NOT NULL,
    `show_on_website` TINYINT(1) DEFAULT 0 NOT NULL,
    `cost` FLOAT NOT NULL,
    `branch_id` INTEGER NOT NULL,
    `currency_id` INTEGER NOT NULL,
    `course_id` INTEGER NOT NULL,
    `course_stream_status_id` INTEGER NOT NULL,
    `instructor_id` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `course_stream_fi_6cc548` (`branch_id`),
    INDEX `course_stream_fi_16a5a4` (`currency_id`),
    INDEX `course_stream_fi_ebed28` (`course_id`),
    INDEX `course_stream_fi_f865a5` (`course_stream_status_id`),
    INDEX `course_stream_fi_e53930` (`instructor_id`),
    CONSTRAINT `course_stream_fk_6cc548`
        FOREIGN KEY (`branch_id`)
        REFERENCES `branch` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `course_stream_fk_16a5a4`
        FOREIGN KEY (`currency_id`)
        REFERENCES `currency` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `course_stream_fk_ebed28`
        FOREIGN KEY (`course_id`)
        REFERENCES `course` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `course_stream_fk_f865a5`
        FOREIGN KEY (`course_stream_status_id`)
        REFERENCES `course_stream_status` (`id`),
    CONSTRAINT `course_stream_fk_e53930`
        FOREIGN KEY (`instructor_id`)
        REFERENCES `user` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- course_skill
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `course_skill`;

CREATE TABLE `course_skill`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50),
    `description` TEXT,
    `logo_name` VARCHAR(255),
    `course_id` INTEGER NOT NULL,
    `sortable_rank` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `course_skill_fi_ebed28` (`course_id`),
    CONSTRAINT `course_skill_fk_ebed28`
        FOREIGN KEY (`course_id`)
        REFERENCES `course` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- branch
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `branch`;

CREATE TABLE `branch`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `show_on_website` TINYINT(1) DEFAULT 1 NOT NULL,
    `name` VARCHAR(255),
    `address` VARCHAR(255),
    `geographic_coordinates` MEDIUMBLOB,
    `tel` VARCHAR(25),
    `email` VARCHAR(128),
    `instagram_link` VARCHAR(255),
    `facebook_link` VARCHAR(255),
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- project
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `project`;

CREATE TABLE `project`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `alt_url` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `logo_name` VARCHAR(255),
    `cover_name` VARCHAR(255),
    `context` TEXT,
    `meta_description` TEXT,
    `meta_keywords` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- vacancy_salary
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `vacancy_salary`;

CREATE TABLE `vacancy_salary`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(20) NOT NULL,
    `description` VARCHAR(300),
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- notification
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `notification`;

CREATE TABLE `notification`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `type` INTEGER,
    `to_user_id` INTEGER NOT NULL,
    `from_user_id` INTEGER,
    `is_seen` TINYINT(1) DEFAULT 0 NOT NULL,
    `is_over` TINYINT(1) DEFAULT 0 NOT NULL,
    `quantity` INTEGER DEFAULT 1 NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `notification_fi_3703f2` (`to_user_id`),
    INDEX `notification_fi_122792` (`from_user_id`),
    CONSTRAINT `notification_fk_3703f2`
        FOREIGN KEY (`to_user_id`)
        REFERENCES `user` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `notification_fk_122792`
        FOREIGN KEY (`from_user_id`)
        REFERENCES `user` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- currency
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `currency`;

CREATE TABLE `currency`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `iso_code` VARCHAR(255) NOT NULL,
    `symbol` VARCHAR(255) NOT NULL,
    `is_symbol_before` TINYINT(1) DEFAULT 0 NOT NULL,
    `notes` VARCHAR(255),
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- currency_rate
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `currency_rate`;

CREATE TABLE `currency_rate`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `rate` FLOAT DEFAULT 1.0 NOT NULL,
    `default_currency_id` INTEGER NOT NULL,
    `to_currency_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `currency_rate_fi_8596aa` (`default_currency_id`),
    INDEX `currency_rate_fi_78514f` (`to_currency_id`),
    CONSTRAINT `currency_rate_fk_8596aa`
        FOREIGN KEY (`default_currency_id`)
        REFERENCES `currency` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `currency_rate_fk_78514f`
        FOREIGN KEY (`to_currency_id`)
        REFERENCES `currency` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- vacancy
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `vacancy`;

CREATE TABLE `vacancy`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `description` TEXT,
    `context` TEXT,
    `alt_url` VARCHAR(255) NOT NULL,
    `logo_name` VARCHAR(255),
    `vacancy_salary_id` INTEGER NOT NULL,
    `meta_description` TEXT,
    `meta_keywords` TEXT,
    `sortable_rank` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `vacancy_fi_c4cfb6` (`vacancy_salary_id`),
    CONSTRAINT `vacancy_fk_c4cfb6`
        FOREIGN KEY (`vacancy_salary_id`)
        REFERENCES `vacancy_salary` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- static_page
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `static_page`;

CREATE TABLE `static_page`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(90),
    `logo_name` VARCHAR(255),
    `cover_name` VARCHAR(32),
    `alt_url` VARCHAR(255) NOT NULL,
    `is_available` TINYINT(1) DEFAULT 0 NOT NULL,
    `content` TEXT,
    `context` TEXT,
    `notes` VARCHAR(500),
    `meta_description` TEXT,
    `meta_keywords` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- feedback
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `feedback`;

CREATE TABLE `feedback`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `work_place` VARCHAR(255),
    `salary` FLOAT,
    `currency_id` INTEGER,
    `user_id` INTEGER NOT NULL,
    `is_available` TINYINT(1) DEFAULT 0 NOT NULL,
    `content` TEXT,
    `notes` VARCHAR(500),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `feedback_fi_16a5a4` (`currency_id`),
    INDEX `feedback_fi_29554a` (`user_id`),
    CONSTRAINT `feedback_fk_16a5a4`
        FOREIGN KEY (`currency_id`)
        REFERENCES `currency` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `feedback_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- config
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `config`;

CREATE TABLE `config`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(60),
    `value` INTEGER,
    `data` VARCHAR(255),
    `seo` MEDIUMBLOB,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
