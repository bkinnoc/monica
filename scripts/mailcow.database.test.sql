-- Valentina Studio --
-- MySQL dump --
-- ---------------------------------------------------------
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */
;

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */
;

-- ---------------------------------------------------------
-- DROP DATABASE "mailcow" ---------------------------------
-- DROP DATABASE IF EXISTS `mailcow`;
-- ---------------------------------------------------------
-- CREATE DATABASE "mailcow" -------------------------------
-- CREATE DATABASE `mailcow` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mailcow`;

-- ---------------------------------------------------------
-- CREATE TABLE "sender_acl" -----------------------------------
CREATE TABLE `sender_acl`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `logged_in_as` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `send_as` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `external` TinyInt(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "sogo_sessions_folder" -------------------------
CREATE TABLE `sogo_sessions_folder`(
    `c_id` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_value` VarChar(4096) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_creationdate` Int(11) NOT NULL,
    `c_lastseen` Int(11) NOT NULL,
    PRIMARY KEY (`c_id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "fido2" ----------------------------------------
CREATE TABLE `fido2`(
    `username` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `friendlyName` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `rpId` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `credentialPublicKey` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `certificateChain` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `certificate` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `certificateIssuer` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `certificateSubject` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `signatureCounter` Int(11) NULL DEFAULT NULL,
    `AAGUID` Blob NULL DEFAULT NULL,
    `credentialId` Blob NOT NULL,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    `active` TinyInt(1) NOT NULL DEFAULT 1
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "quarantine" -----------------------------------
CREATE TABLE `quarantine`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `qid` VarChar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `subject` VarChar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `score` float NULL DEFAULT NULL,
    `ip` VarChar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `action` Char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'unknown',
    `symbols` LongText CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
    `fuzzy_hashes` LongText CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
    `sender` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'unknown',
    `rcpt` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `msg` LongText CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `domain` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `notified` TinyInt(1) NOT NULL DEFAULT 0,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `user` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'unknown',
    PRIMARY KEY (`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "versions" -------------------------------------
CREATE TABLE `versions`(
    `application` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `version` VarChar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`application`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "oauth_clients" --------------------------------
CREATE TABLE `oauth_clients`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `client_id` VarChar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `client_secret` VarChar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `redirect_uri` VarChar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `grant_types` VarChar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `scope` VarChar(4000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `user_id` VarChar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    PRIMARY KEY (`client_id`),
    CONSTRAINT `id` UNIQUE(`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "transports" -----------------------------------
CREATE TABLE `transports`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `destination` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `nexthop` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `username` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `password` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `is_mx_based` TinyInt(1) NOT NULL DEFAULT 0,
    `active` TinyInt(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "sogo_store" -----------------------------------
CREATE TABLE `sogo_store`(
    `c_folder_id` Int(11) NOT NULL,
    `c_name` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_content` MediumText CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_creationdate` Int(11) NOT NULL,
    `c_lastmodified` Int(11) NOT NULL,
    `c_version` Int(11) NOT NULL,
    `c_deleted` Int(11) NULL DEFAULT NULL,
    PRIMARY KEY (`c_folder_id`, `c_name`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "filterconf" -----------------------------------
CREATE TABLE `filterconf`(
    `object` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `option` VarChar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `value` VarChar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    `prefid` Int(11) AUTO_INCREMENT NOT NULL,
    PRIMARY KEY (`prefid`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "domain" ---------------------------------------
CREATE TABLE `domain`(
    `domain` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `description` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `aliases` Int(10) NOT NULL DEFAULT 0,
    `mailboxes` Int(10) NOT NULL DEFAULT 0,
    `defquota` BigInt(20) NOT NULL DEFAULT 3072,
    `maxquota` BigInt(20) NOT NULL DEFAULT 102400,
    `quota` BigInt(20) NOT NULL DEFAULT 102400,
    `relayhost` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
    `backupmx` TinyInt(1) NOT NULL DEFAULT 0,
    `gal` TinyInt(1) NOT NULL DEFAULT 1,
    `relay_all_recipients` TinyInt(1) NOT NULL DEFAULT 0,
    `relay_unknown_only` TinyInt(1) NOT NULL DEFAULT 0,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    `active` TinyInt(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`domain`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "sogo_alarms_folder" ---------------------------
CREATE TABLE `sogo_alarms_folder`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `c_path` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_name` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_uid` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_recurrence_id` Int(11) NULL DEFAULT NULL,
    `c_alarm_number` Int(11) NOT NULL,
    `c_alarm_date` Int(11) NOT NULL,
    PRIMARY KEY (`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "api" ------------------------------------------
CREATE TABLE `api`(
    `api_key` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `allow_from` VarChar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `skip_ip_check` TinyInt(1) NOT NULL DEFAULT 0,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    `access` Enum('ro', 'rw') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'rw',
    `active` TinyInt(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`api_key`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "quota2replica" --------------------------------
CREATE TABLE `quota2replica`(
    `username` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `bytes` BigInt(20) NOT NULL DEFAULT 0,
    `messages` BigInt(20) NOT NULL DEFAULT 0,
    PRIMARY KEY (`username`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "sogo_quick_appointment" -----------------------
CREATE TABLE `sogo_quick_appointment`(
    `c_folder_id` Int(11) NOT NULL,
    `c_name` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_uid` VarChar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_startdate` Int(11) NULL DEFAULT NULL,
    `c_enddate` Int(11) NULL DEFAULT NULL,
    `c_cycleenddate` Int(11) NULL DEFAULT NULL,
    `c_title` VarChar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_participants` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_isallday` Int(11) NULL DEFAULT NULL,
    `c_iscycle` Int(11) NULL DEFAULT NULL,
    `c_cycleinfo` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_classification` Int(11) NOT NULL,
    `c_isopaque` Int(11) NOT NULL,
    `c_status` Int(11) NOT NULL,
    `c_priority` Int(11) NULL DEFAULT NULL,
    `c_location` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_orgmail` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_partmails` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_partstates` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_category` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_sequence` Int(11) NULL DEFAULT NULL,
    `c_component` VarChar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_nextalarm` Int(11) NULL DEFAULT NULL,
    `c_description` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    PRIMARY KEY (`c_folder_id`, `c_name`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "settingsmap" ----------------------------------
CREATE TABLE `settingsmap`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `desc` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `content` LongText CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    `active` TinyInt(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "relayhosts" -----------------------------------
CREATE TABLE `relayhosts`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `hostname` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `username` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `password` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `active` TinyInt(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "oauth_authorization_codes" --------------------
CREATE TABLE `oauth_authorization_codes`(
    `authorization_code` VarChar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `client_id` VarChar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `user_id` VarChar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `redirect_uri` VarChar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `expires` Timestamp NOT NULL DEFAULT current_timestamp(),
    `scope` VarChar(4000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `id_token` VarChar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    PRIMARY KEY (`authorization_code`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "recipient_maps" -------------------------------
CREATE TABLE `recipient_maps`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `old_dest` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `new_dest` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    `active` TinyInt(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "bcc_maps" -------------------------------------
CREATE TABLE `bcc_maps`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `local_dest` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `bcc_dest` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `domain` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `type` Enum('sender', 'rcpt') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    `active` TinyInt(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "sogo_user_profile" ----------------------------
CREATE TABLE `sogo_user_profile`(
    `c_uid` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_defaults` LongText CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_settings` LongText CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    PRIMARY KEY (`c_uid`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "tags_mailbox" ---------------------------------
CREATE TABLE `tags_mailbox`(
    `tag_name` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `username` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    CONSTRAINT `tag_name` UNIQUE(`tag_name`, `username`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "logs" -----------------------------------------
CREATE TABLE `logs`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `task` Char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '000000',
    `type` VarChar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
    `msg` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `call` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `user` VarChar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `role` VarChar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `remote` VarChar(39) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `time` Int(11) NOT NULL,
    PRIMARY KEY (`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 25;

-- -------------------------------------------------------------
-- CREATE TABLE "da_acl" ---------------------------------------
CREATE TABLE `da_acl`(
    `username` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `syncjobs` TinyInt(1) NOT NULL DEFAULT 1,
    `quarantine` TinyInt(1) NOT NULL DEFAULT 1,
    `login_as` TinyInt(1) NOT NULL DEFAULT 1,
    `sogo_access` TinyInt(1) NOT NULL DEFAULT 1,
    `app_passwds` TinyInt(1) NOT NULL DEFAULT 1,
    `bcc_maps` TinyInt(1) NOT NULL DEFAULT 1,
    `pushover` TinyInt(1) NOT NULL DEFAULT 0,
    `filters` TinyInt(1) NOT NULL DEFAULT 1,
    `ratelimit` TinyInt(1) NOT NULL DEFAULT 1,
    `spam_policy` TinyInt(1) NOT NULL DEFAULT 1,
    `extend_sender_acl` TinyInt(1) NOT NULL DEFAULT 0,
    `unlimited_quota` TinyInt(1) NOT NULL DEFAULT 0,
    `protocol_access` TinyInt(1) NOT NULL DEFAULT 1,
    `smtp_ip_access` TinyInt(1) NOT NULL DEFAULT 1,
    `alias_domains` TinyInt(1) NOT NULL DEFAULT 0,
    `mailbox_relayhost` TinyInt(1) NOT NULL DEFAULT 1,
    `domain_relayhost` TinyInt(1) NOT NULL DEFAULT 1,
    `domain_desc` TinyInt(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`username`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "sieve_filters" --------------------------------
CREATE TABLE `sieve_filters`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `username` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `script_desc` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `script_name` Enum('active', 'inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `script_data` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `filter_type` Enum('postfilter', 'prefilter') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "_sogo_static_view" ----------------------------
CREATE TABLE `_sogo_static_view`(
    `c_uid` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `domain` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_name` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_password` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `c_cn` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `mail` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `aliases` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `ad_aliases` VarChar(6144) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `ext_acl` VarChar(6144) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `kind` VarChar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `multiple_bookings` Int(11) NOT NULL DEFAULT -1,
    PRIMARY KEY (`c_uid`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "oauth_access_tokens" --------------------------
CREATE TABLE `oauth_access_tokens`(
    `access_token` VarChar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `client_id` VarChar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `user_id` VarChar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `expires` Timestamp NOT NULL DEFAULT current_timestamp(),
    `scope` VarChar(4000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    PRIMARY KEY (`access_token`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "alias_domain" ---------------------------------
CREATE TABLE `alias_domain`(
    `alias_domain` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `target_domain` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    `active` TinyInt(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`alias_domain`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "alias" ----------------------------------------
CREATE TABLE `alias`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `address` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `goto` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `domain` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    `private_comment` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `public_comment` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `sogo_visible` TinyInt(1) NOT NULL DEFAULT 1,
    `active` TinyInt(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    CONSTRAINT `address` UNIQUE(`address`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 12;

-- -------------------------------------------------------------
-- CREATE TABLE "imapsync" -------------------------------------
CREATE TABLE `imapsync`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `user2` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `host1` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `authmech1` Enum('PLAIN', 'LOGIN', 'CRAM-MD5') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'PLAIN',
    `regextrans2` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
    `authmd51` TinyInt(1) NOT NULL DEFAULT 0,
    `domain2` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `subfolder2` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `user1` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `password1` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `exclude` VarChar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `maxage` SmallInt(6) NOT NULL DEFAULT 0,
    `mins_interval` SmallInt(5) UNSIGNED NOT NULL DEFAULT 0,
    `maxbytespersecond` VarChar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
    `port1` SmallInt(5) UNSIGNED NOT NULL,
    `enc1` Enum('TLS', 'SSL', 'PLAIN') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'TLS',
    `delete2duplicates` TinyInt(1) NOT NULL DEFAULT 1,
    `delete1` TinyInt(1) NOT NULL DEFAULT 0,
    `delete2` TinyInt(1) NOT NULL DEFAULT 0,
    `automap` TinyInt(1) NOT NULL DEFAULT 0,
    `skipcrossduplicates` TinyInt(1) NOT NULL DEFAULT 0,
    `custom_params` VarChar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `timeout1` SmallInt(6) NOT NULL DEFAULT 600,
    `timeout2` SmallInt(6) NOT NULL DEFAULT 600,
    `subscribeall` TinyInt(1) NOT NULL DEFAULT 1,
    `is_running` TinyInt(1) NOT NULL DEFAULT 0,
    `returned_text` LongText CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `last_run` Timestamp NULL DEFAULT NULL,
    `success` TinyInt(1) UNSIGNED NULL DEFAULT NULL,
    `exit_status` VarChar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    `active` TinyInt(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "pushover" -------------------------------------
CREATE TABLE `pushover`(
    `username` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `key` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `token` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `attributes` LongText CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
    `title` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `text` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `senders` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `senders_regex` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `active` TinyInt(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`username`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "quota2" ---------------------------------------
CREATE TABLE `quota2`(
    `username` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `bytes` BigInt(20) NOT NULL DEFAULT 0,
    `messages` BigInt(20) NOT NULL DEFAULT 0,
    PRIMARY KEY (`username`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "mailbox" --------------------------------------
CREATE TABLE `mailbox`(
    `username` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `password` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `name` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `description` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `mailbox_path_prefix` VarChar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '/var/vmail/',
    `quota` BigInt(20) NOT NULL DEFAULT 102400,
    `local_part` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `domain` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `attributes` LongText CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
    `kind` VarChar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `multiple_bookings` Int(11) NOT NULL DEFAULT -1,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    `active` TinyInt(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`username`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "sogo_cache_folder" ----------------------------
CREATE TABLE `sogo_cache_folder`(
    `c_uid` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_path` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_parent_path` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_type` TinyInt(3) UNSIGNED NOT NULL,
    `c_creationdate` Int(11) NOT NULL,
    `c_lastmodified` Int(11) NOT NULL,
    `c_version` Int(11) NOT NULL DEFAULT 0,
    `c_deleted` TinyInt(4) NOT NULL DEFAULT 0,
    `c_content` LongText CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    PRIMARY KEY (`c_uid`, `c_path`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "app_passwd" -----------------------------------
CREATE TABLE `app_passwd`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `name` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `mailbox` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `domain` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `password` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    `imap_access` TinyInt(1) NOT NULL DEFAULT 1,
    `smtp_access` TinyInt(1) NOT NULL DEFAULT 1,
    `dav_access` TinyInt(1) NOT NULL DEFAULT 1,
    `eas_access` TinyInt(1) NOT NULL DEFAULT 1,
    `pop3_access` TinyInt(1) NOT NULL DEFAULT 1,
    `sieve_access` TinyInt(1) NOT NULL DEFAULT 1,
    `active` TinyInt(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "spamalias" ------------------------------------
CREATE TABLE `spamalias`(
    `address` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `goto` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    `validity` Int(11) NULL DEFAULT NULL,
    PRIMARY KEY (`address`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "domain_admins" --------------------------------
CREATE TABLE `domain_admins`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `username` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `domain` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `active` TinyInt(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 2;

-- -------------------------------------------------------------
-- CREATE TABLE "admin" ----------------------------------------
CREATE TABLE `admin`(
    `username` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `password` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `superadmin` TinyInt(1) NOT NULL DEFAULT 0,
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    `active` TinyInt(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`username`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "sogo_acl" -------------------------------------
CREATE TABLE `sogo_acl`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `c_folder_id` Int(11) NOT NULL,
    `c_object` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_uid` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_role` VarChar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    PRIMARY KEY (`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "tfa" ------------------------------------------
CREATE TABLE `tfa`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `key_id` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `username` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `authmech` Enum('yubi_otp', 'u2f', 'hotp', 'totp', 'webauthn') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `secret` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `keyHandle` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `publicKey` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `counter` Int(11) NOT NULL DEFAULT 0,
    `certificate` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `active` TinyInt(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "sasl_log" -------------------------------------
CREATE TABLE `sasl_log`(
    `service` VarChar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
    `app_password` Int(11) NULL DEFAULT NULL,
    `username` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `real_rip` VarChar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `datetime` DateTime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`service`, `real_rip`, `username`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "sogo_folder_info" -----------------------------
CREATE TABLE `sogo_folder_info`(
    `c_folder_id` BigInt(20) UNSIGNED AUTO_INCREMENT NOT NULL,
    `c_path` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_path1` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_path2` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_path3` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_path4` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_foldername` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_location` VarChar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_quick_location` VarChar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_acl_location` VarChar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_folder_type` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    PRIMARY KEY (`c_path`),
    CONSTRAINT `c_folder_id` UNIQUE(`c_folder_id`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 11;

-- -------------------------------------------------------------
-- CREATE TABLE "user_acl" -------------------------------------
CREATE TABLE `user_acl`(
    `username` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `spam_alias` TinyInt(1) NOT NULL DEFAULT 1,
    `tls_policy` TinyInt(1) NOT NULL DEFAULT 1,
    `spam_score` TinyInt(1) NOT NULL DEFAULT 1,
    `spam_policy` TinyInt(1) NOT NULL DEFAULT 1,
    `delimiter_action` TinyInt(1) NOT NULL DEFAULT 1,
    `syncjobs` TinyInt(1) NOT NULL DEFAULT 0,
    `eas_reset` TinyInt(1) NOT NULL DEFAULT 1,
    `sogo_profile_reset` TinyInt(1) NOT NULL DEFAULT 0,
    `pushover` TinyInt(1) NOT NULL DEFAULT 1,
    `quarantine` TinyInt(1) NOT NULL DEFAULT 1,
    `quarantine_attachments` TinyInt(1) NOT NULL DEFAULT 1,
    `quarantine_notification` TinyInt(1) NOT NULL DEFAULT 1,
    `quarantine_category` TinyInt(1) NOT NULL DEFAULT 1,
    `app_passwds` TinyInt(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`username`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "oauth_refresh_tokens" -------------------------
CREATE TABLE `oauth_refresh_tokens`(
    `refresh_token` VarChar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `client_id` VarChar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `user_id` VarChar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `expires` Timestamp NOT NULL DEFAULT current_timestamp(),
    `scope` VarChar(4000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    PRIMARY KEY (`refresh_token`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "tags_domain" ----------------------------------
CREATE TABLE `tags_domain`(
    `tag_name` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `domain` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    CONSTRAINT `tag_name` UNIQUE(`tag_name`, `domain`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "tls_policy_override" --------------------------
CREATE TABLE `tls_policy_override`(
    `id` Int(11) AUTO_INCREMENT NOT NULL,
    `dest` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `policy` Enum(
        'none',
        'may',
        'encrypt',
        'dane',
        'dane-only',
        'fingerprint',
        'verify',
        'secure'
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `parameters` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
    `created` DateTime NOT NULL DEFAULT current_timestamp(),
    `modified` DateTime NULL DEFAULT NULL,
    `active` TinyInt(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    CONSTRAINT `dest` UNIQUE(`dest`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB AUTO_INCREMENT = 1;

-- -------------------------------------------------------------
-- CREATE TABLE "sogo_quick_contact" ---------------------------
CREATE TABLE `sogo_quick_contact`(
    `c_folder_id` Int(11) NOT NULL,
    `c_name` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_givenname` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_cn` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_sn` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_screenname` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_l` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_mail` Text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_o` VarChar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_ou` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_telephonenumber` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_categories` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
    `c_component` VarChar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `c_hascertificate` Int(11) NULL DEFAULT 0,
    PRIMARY KEY (`c_folder_id`, `c_name`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE TABLE "forwarding_hosts" -----------------------------
CREATE TABLE `forwarding_hosts`(
    `host` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `source` VarChar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `filter_spam` TinyInt(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`host`)
) CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ENGINE = InnoDB;

-- -------------------------------------------------------------
-- CREATE INDEX "destination" ----------------------------------
CREATE INDEX `destination` USING BTREE ON `transports`(`destination`);

-- -------------------------------------------------------------
-- CREATE INDEX "nexthop" --------------------------------------
CREATE INDEX `nexthop` USING BTREE ON `transports`(`nexthop`);

-- -------------------------------------------------------------
-- CREATE INDEX "object" ---------------------------------------
CREATE INDEX `object` USING BTREE ON `filterconf`(`object`);

-- -------------------------------------------------------------
-- CREATE INDEX "hostname" -------------------------------------
CREATE INDEX `hostname` USING BTREE ON `relayhosts`(`hostname`);

-- -------------------------------------------------------------
-- CREATE INDEX "local_dest" -----------------------------------
CREATE INDEX `local_dest` USING BTREE ON `recipient_maps`(`old_dest`);

-- -------------------------------------------------------------
-- CREATE INDEX "local_dest" -----------------------------------
CREATE INDEX `local_dest` USING BTREE ON `bcc_maps`(`local_dest`);

-- -------------------------------------------------------------
-- CREATE INDEX "fk_tags_mailbox" ------------------------------
CREATE INDEX `fk_tags_mailbox` USING BTREE ON `tags_mailbox`(`username`);

-- -------------------------------------------------------------
-- CREATE INDEX "script_desc" ----------------------------------
CREATE INDEX `script_desc` USING BTREE ON `sieve_filters`(`script_desc`);

-- -------------------------------------------------------------
-- CREATE INDEX "username" -------------------------------------
CREATE INDEX `username` USING BTREE ON `sieve_filters`(`username`);

-- -------------------------------------------------------------
-- CREATE INDEX "domain" ---------------------------------------
CREATE INDEX `domain` USING BTREE ON `_sogo_static_view`(`domain`);

-- -------------------------------------------------------------
delimiter $ $ $ -- CREATE TRIGGER "sogo_update_password" -----------------------
CREATE DEFINER = `mailcow` @`%` TRIGGER sogo_update_password
AFTER
UPDATE
    ON _sogo_static_view FOR EACH ROW BEGIN
UPDATE
    mailbox
SET
    password = NEW.c_password
WHERE
    NEW.c_uid = username;

END;

-- -------------------------------------------------------------
$ $ $ delimiter;

-- CREATE INDEX "active" ---------------------------------------
CREATE INDEX `active` USING BTREE ON `alias_domain`(`active`);

-- -------------------------------------------------------------
-- CREATE INDEX "target_domain" --------------------------------
CREATE INDEX `target_domain` USING BTREE ON `alias_domain`(`target_domain`);

-- -------------------------------------------------------------
-- CREATE INDEX "domain" ---------------------------------------
CREATE INDEX `domain` USING BTREE ON `alias`(`domain`);

-- -------------------------------------------------------------
-- CREATE INDEX "domain" ---------------------------------------
CREATE INDEX `domain` USING BTREE ON `mailbox`(`domain`);

-- -------------------------------------------------------------
-- CREATE INDEX "kind" -----------------------------------------
CREATE INDEX `kind` USING BTREE ON `mailbox`(`kind`);

-- -------------------------------------------------------------
-- CREATE INDEX "domain" ---------------------------------------
CREATE INDEX `domain` USING BTREE ON `app_passwd`(`domain`);

-- -------------------------------------------------------------
-- CREATE INDEX "mailbox" --------------------------------------
CREATE INDEX `mailbox` USING BTREE ON `app_passwd`(`mailbox`);

-- -------------------------------------------------------------
-- CREATE INDEX "password" -------------------------------------
CREATE INDEX `password` USING BTREE ON `app_passwd`(`password`);

-- -------------------------------------------------------------
-- CREATE INDEX "username" -------------------------------------
CREATE INDEX `username` USING BTREE ON `domain_admins`(`username`);

-- -------------------------------------------------------------
-- CREATE INDEX "sogo_acl_c_folder_id_idx" ---------------------
CREATE INDEX `sogo_acl_c_folder_id_idx` USING BTREE ON `sogo_acl`(`c_folder_id`);

-- -------------------------------------------------------------
-- CREATE INDEX "sogo_acl_c_uid_idx" ---------------------------
CREATE INDEX `sogo_acl_c_uid_idx` USING BTREE ON `sogo_acl`(`c_uid`);

-- -------------------------------------------------------------
-- CREATE INDEX "datetime" -------------------------------------
CREATE INDEX `datetime` USING BTREE ON `sasl_log`(`datetime`);

-- -------------------------------------------------------------
-- CREATE INDEX "real_rip" -------------------------------------
CREATE INDEX `real_rip` USING BTREE ON `sasl_log`(`real_rip`);

-- -------------------------------------------------------------
-- CREATE INDEX "service" --------------------------------------
CREATE INDEX `service` USING BTREE ON `sasl_log`(`service`);

-- -------------------------------------------------------------
-- CREATE INDEX "username" -------------------------------------
CREATE INDEX `username` USING BTREE ON `sasl_log`(`username`);

-- -------------------------------------------------------------
-- CREATE INDEX "fk_tags_domain" -------------------------------
CREATE INDEX `fk_tags_domain` USING BTREE ON `tags_domain`(`domain`);

-- -------------------------------------------------------------
-- CREATE LINK "fk_tags_mailbox" -------------------------------
ALTER TABLE
    `tags_mailbox`
ADD
    CONSTRAINT `fk_tags_mailbox` FOREIGN KEY (`username`) REFERENCES `mailbox`(`username`) ON DELETE Cascade ON UPDATE No Action;

-- -------------------------------------------------------------
-- CREATE LINK "fk_username_sieve_global_before" ---------------
ALTER TABLE
    `sieve_filters`
ADD
    CONSTRAINT `fk_username_sieve_global_before` FOREIGN KEY (`username`) REFERENCES `mailbox`(`username`) ON DELETE Cascade ON UPDATE No Action;

-- -------------------------------------------------------------
-- CREATE LINK "fk_username_app_passwd" ------------------------
ALTER TABLE
    `app_passwd`
ADD
    CONSTRAINT `fk_username_app_passwd` FOREIGN KEY (`mailbox`) REFERENCES `mailbox`(`username`) ON DELETE Cascade ON UPDATE No Action;

-- -------------------------------------------------------------
-- CREATE LINK "fk_username" -----------------------------------
ALTER TABLE
    `user_acl`
ADD
    CONSTRAINT `fk_username` FOREIGN KEY (`username`) REFERENCES `mailbox`(`username`) ON DELETE Cascade ON UPDATE No Action;

-- -------------------------------------------------------------
-- CREATE LINK "fk_tags_domain" --------------------------------
ALTER TABLE
    `tags_domain`
ADD
    CONSTRAINT `fk_tags_domain` FOREIGN KEY (`domain`) REFERENCES `domain`(`domain`) ON DELETE Cascade ON UPDATE No Action;

-- -------------------------------------------------------------
delimiter $ $ $ -- CREATE EVENT "clean_spamalias" ------------------------------
CREATE DEFINER = mailcow @ % EVENT IF NOT EXISTS `clean_spamalias` ON SCHEDULE EVERY 1 DAY STARTS '2022-09-21 23:00:56' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
DELETE FROM
    spamalias
WHERE
    validity < UNIX_TIMESTAMP();

END;

-- -------------------------------------------------------------
$ $ $ delimiter;

delimiter $ $ $ -- CREATE EVENT "clean_oauth2" ---------------------------------
CREATE DEFINER = mailcow @ % EVENT IF NOT EXISTS `clean_oauth2` ON SCHEDULE EVERY 1 DAY STARTS '2022-09-21 23:00:56' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
DELETE FROM
    oauth_refresh_tokens
WHERE
    expires < NOW();

DELETE FROM
    oauth_access_tokens
WHERE
    expires < NOW();

DELETE FROM
    oauth_authorization_codes
WHERE
    expires < NOW();

END;

-- -------------------------------------------------------------
$ $ $ delimiter;

-- CREATE VIEW "sieve_after" -----------------------------------
CREATE ALGORITHM = UNDEFINED DEFINER = `mailcow` @`%` SQL SECURITY DEFINER VIEW `sieve_after` AS
select
    md5(`mailcow`.`sieve_filters`.`script_data`) AS `id`,
    `mailcow`.`sieve_filters`.`username` AS `username`,
    `mailcow`.`sieve_filters`.`script_name` AS `script_name`,
    `mailcow`.`sieve_filters`.`script_data` AS `script_data`
from
    `mailcow`.`sieve_filters`
where
    `mailcow`.`sieve_filters`.`filter_type` = 'postfilter';

-- -------------------------------------------------------------
-- CREATE VIEW "grouped_domain_alias_address" ------------------
CREATE ALGORITHM = UNDEFINED DEFINER = `mailcow` @`%` SQL SECURITY DEFINER VIEW `grouped_domain_alias_address` AS
select
    `mailcow`.`mailbox`.`username` AS `username`,
    ifnull(
        group_concat(
            `mailcow`.`mailbox`.`local_part`,
            '@',
            `mailcow`.`alias_domain`.`alias_domain` separator ' '
        ),
        ''
    ) AS `ad_alias`
from
    (
        `mailcow`.`mailbox`
        left join `mailcow`.`alias_domain` on(
            `mailcow`.`alias_domain`.`target_domain` = `mailcow`.`mailbox`.`domain`
        )
    )
group by
    `mailcow`.`mailbox`.`username`;

-- -------------------------------------------------------------
-- CREATE VIEW "grouped_sender_acl" ----------------------------
CREATE ALGORITHM = UNDEFINED DEFINER = `mailcow` @`%` SQL SECURITY DEFINER VIEW `grouped_sender_acl` AS
select
    `mailcow`.`sender_acl`.`logged_in_as` AS `username`,
    ifnull(
        group_concat(`mailcow`.`sender_acl`.`send_as` separator ' '),
        ''
    ) AS `send_as_acl`
from
    `mailcow`.`sender_acl`
where
    `mailcow`.`sender_acl`.`send_as` not like '@%'
group by
    `mailcow`.`sender_acl`.`logged_in_as`;

-- -------------------------------------------------------------
-- CREATE VIEW "sogo_view" -------------------------------------
CREATE ALGORITHM = UNDEFINED DEFINER = `mailcow` @`%` SQL SECURITY DEFINER VIEW `sogo_view` AS
select
    `mailcow`.`mailbox`.`username` AS `c_uid`,
    `mailcow`.`mailbox`.`domain` AS `domain`,
    `mailcow`.`mailbox`.`username` AS `c_name`,
    if(
        json_unquote(
            json_value(
                `mailcow`.`mailbox`.`attributes`,
                '$.force_pw_update'
            )
        ) = '0',
        if(
            json_unquote(
                json_value(
                    `mailcow`.`mailbox`.`attributes`,
                    '$.sogo_access'
                )
            ) = 1,
            `mailcow`.`mailbox`.`password`,
            '{SSHA256}A123A123A321A321A321B321B321B123B123B321B432F123E321123123321321'
        ),
        '{SSHA256}A123A123A321A321A321B321B321B123B123B321B432F123E321123123321321'
    ) AS `c_password`,
    `mailcow`.`mailbox`.`name` AS `c_cn`,
    `mailcow`.`mailbox`.`username` AS `mail`,
    ifnull(
        group_concat(
            `ga`.`aliases`
            order by
                `ga`.`aliases` ASC separator ' '
        ),
        ''
    ) AS `aliases`,
    ifnull(`gda`.`ad_alias`, '') AS `ad_aliases`,
    ifnull(`external_acl`.`send_as_acl`, '') AS `ext_acl`,
    `mailcow`.`mailbox`.`kind` AS `kind`,
    `mailcow`.`mailbox`.`multiple_bookings` AS `multiple_bookings`
from
    (
        (
            (
                `mailcow`.`mailbox`
                left join `mailcow`.`grouped_mail_aliases` `ga` on(
                    `ga`.`username` regexp concat('(^|,)', `mailcow`.`mailbox`.`username`, '($|,)')
                )
            )
            left join `mailcow`.`grouped_domain_alias_address` `gda` on(
                `gda`.`username` = `mailcow`.`mailbox`.`username`
            )
        )
        left join `mailcow`.`grouped_sender_acl_external` `external_acl` on(
            `external_acl`.`username` = `mailcow`.`mailbox`.`username`
        )
    )
where
    `mailcow`.`mailbox`.`active` = '1'
group by
    `mailcow`.`mailbox`.`username`;

-- -------------------------------------------------------------
-- CREATE VIEW "grouped_mail_aliases" --------------------------
CREATE ALGORITHM = UNDEFINED DEFINER = `mailcow` @`%` SQL SECURITY DEFINER VIEW `grouped_mail_aliases` AS
select
    `mailcow`.`alias`.`goto` AS `username`,
    ifnull(
        group_concat(
            `mailcow`.`alias`.`address`
            order by
                `mailcow`.`alias`.`address` ASC separator ' '
        ),
        ''
    ) AS `aliases`
from
    `mailcow`.`alias`
where
    `mailcow`.`alias`.`address` <> `mailcow`.`alias`.`goto`
    and `mailcow`.`alias`.`active` = '1'
    and `mailcow`.`alias`.`sogo_visible` = '1'
    and `mailcow`.`alias`.`address` not like '@%'
group by
    `mailcow`.`alias`.`goto`;

-- -------------------------------------------------------------
-- CREATE VIEW "sieve_before" ----------------------------------
CREATE ALGORITHM = UNDEFINED DEFINER = `mailcow` @`%` SQL SECURITY DEFINER VIEW `sieve_before` AS
select
    md5(`mailcow`.`sieve_filters`.`script_data`) AS `id`,
    `mailcow`.`sieve_filters`.`username` AS `username`,
    `mailcow`.`sieve_filters`.`script_name` AS `script_name`,
    `mailcow`.`sieve_filters`.`script_data` AS `script_data`
from
    `mailcow`.`sieve_filters`
where
    `mailcow`.`sieve_filters`.`filter_type` = 'prefilter';

-- -------------------------------------------------------------
-- CREATE VIEW "grouped_sender_acl_external" -------------------
CREATE ALGORITHM = UNDEFINED DEFINER = `mailcow` @`%` SQL SECURITY DEFINER VIEW `grouped_sender_acl_external` AS
select
    `mailcow`.`sender_acl`.`logged_in_as` AS `username`,
    ifnull(
        group_concat(`mailcow`.`sender_acl`.`send_as` separator ' '),
        ''
    ) AS `send_as_acl`
from
    `mailcow`.`sender_acl`
where
    `mailcow`.`sender_acl`.`send_as` not like '@%'
    and `mailcow`.`sender_acl`.`external` = '1'
group by
    `mailcow`.`sender_acl`.`logged_in_as`;

-- -------------------------------------------------------------
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */
;

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */
;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;

-- ---------------------------------------------------------
