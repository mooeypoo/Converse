SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `Converse` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `Converse` ;

-- -----------------------------------------------------
-- Table `Converse`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Converse`.`users` (
  `userid` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(45) NULL,
  `displayname` VARCHAR(45) NULL,
  `level` INT NULL,
  PRIMARY KEY (`userid`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Converse`.`posts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Converse`.`posts` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `latest_revision` INT NULL,
  `moderation_status` VARCHAR(45) NULL,
  `moderation_author` INT NULL,
  `moderation_timestamp` INT NULL,
  `moderation_reason` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_posts_latest_revision_idx` (`latest_revision` ASC),
  INDEX `fk_posts_moderation_author_idx` (`moderation_author` ASC),
  CONSTRAINT `fk_posts_latest_revision`
    FOREIGN KEY (`latest_revision`)
    REFERENCES `Converse`.`revisions` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_posts_moderation_author`
    FOREIGN KEY (`moderation_author`)
    REFERENCES `Converse`.`users` (`userid`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Converse`.`revisions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Converse`.`revisions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `timestamp` INT NULL,
  `author` INT NULL,
  `previous_revision` INT NULL,
  `parent_post` INT NULL,
  `content` TEXT NULL,
  `content_format` VARCHAR(45) NULL,
  `edit_comment` TEXT NULL,
  `moderation_status` VARCHAR(45) NULL,
  `moderation_author` INT NULL,
  `moderation_timestamp` INT NULL,
  `moderation_reason` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_revisions_author_idx` (`author` ASC),
  INDEX `fk_revisions_previous_revision_idx` (`previous_revision` ASC),
  INDEX `fk_revisions_moderation_author_idx` (`moderation_author` ASC),
  INDEX `fk_revisions_parent_post_idx` (`parent_post` ASC),
  CONSTRAINT `fk_revisions_author`
    FOREIGN KEY (`author`)
    REFERENCES `Converse`.`users` (`userid`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_revisions_previous_revision`
    FOREIGN KEY (`previous_revision`)
    REFERENCES `Converse`.`revisions` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_revisions_moderation_author`
    FOREIGN KEY (`moderation_author`)
    REFERENCES `Converse`.`users` (`userid`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_revisions_parent_post`
    FOREIGN KEY (`parent_post`)
    REFERENCES `Converse`.`posts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Converse`.`collections`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Converse`.`collections` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `author` INT NULL,
  `timestamp` INT NULL,
  `title_post` INT NULL,
  `primary_post` INT NULL,
  `summary_post` INT NULL,
  `context_collection` INT NULL,
  `moderation_status` VARCHAR(45) NULL,
  `moderation_author` INT NULL,
  `moderation_timestamp` INT NULL,
  `moderation_reason` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_collections_author_idx` (`author` ASC),
  INDEX `fk_collections_title_post_idx` (`title_post` ASC),
  INDEX `fk_collections_primary_post_idx` (`primary_post` ASC),
  INDEX `fk_collections_description_idx` (`summary_post` ASC),
  INDEX `fk_collections_moderation_author_idx` (`moderation_author` ASC),
  INDEX `fk_collections_context_collection_idx` (`context_collection` ASC),
  CONSTRAINT `fk_collections_author`
    FOREIGN KEY (`author`)
    REFERENCES `Converse`.`users` (`userid`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_collections_title_post`
    FOREIGN KEY (`title_post`)
    REFERENCES `Converse`.`posts` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_collections_primary_post`
    FOREIGN KEY (`primary_post`)
    REFERENCES `Converse`.`posts` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_collections_description_post`
    FOREIGN KEY (`summary_post`)
    REFERENCES `Converse`.`posts` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_collections_moderation_author`
    FOREIGN KEY (`moderation_author`)
    REFERENCES `Converse`.`posts` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_collections_context_collection`
    FOREIGN KEY (`context_collection`)
    REFERENCES `Converse`.`collections` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Converse`.`collection_children`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Converse`.`collection_children` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `collection_id` INT NULL,
  `child_collection_id` INT NULL,
  `is_sticky` TINYINT(1) NULL DEFAULT FALSE,
  `order` INT NULL DEFAULT -1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_collection_children_collection_id_idx` (`collection_id` ASC),
  INDEX `fk_collection_children_child_collection_id_idx` (`child_collection_id` ASC),
  CONSTRAINT `fk_collection_children_collection_id`
    FOREIGN KEY (`collection_id`)
    REFERENCES `Converse`.`collections` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_collection_children_child_collection_id`
    FOREIGN KEY (`child_collection_id`)
    REFERENCES `Converse`.`collections` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
