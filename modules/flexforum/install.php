<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'flexforumcategories')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'flexforumcategories` (
    `id` int(11) NOT NULL,
    `name` mediumtext NOT NULL,
    `slug` mediumtext NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumcategories`
    ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumcategories`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');

  
}

if (!$CI->db->table_exists(db_prefix() . 'flexforummodellist')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . 'flexforummodellist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model_name` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}


if (!$CI->db->table_exists(db_prefix() . 'flexforumtopics')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'flexforumtopics` (
    `id` int(11) NOT NULL,
    `category` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `user_type` varchar(10) NOT NULL,
    `title` mediumtext NOT NULL,
    `slug` mediumtext NOT NULL,
    `description` LONGTEXT NOT NULL,
    `date_added` datetime NOT NULL,
    `date_updated` datetime NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumtopics`
    ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumtopics`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
}

if (!$CI->db->table_exists(db_prefix() . 'flexforumlikes')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'flexforumlikes` (
    `id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `user_type` varchar(10) NOT NULL,
    `type_id` int(11) NOT NULL,
    `type` varchar(10) NOT NULL,
    `date_added` datetime NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumlikes`
    ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumlikes`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
}

if (!$CI->db->table_exists(db_prefix() . 'flexforumreplies')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'flexforumreplies` (
    `id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `user_type` varchar(10) NOT NULL,
    `type_id` int(11) NOT NULL,
    `type` varchar(10) NOT NULL,
    `reply` LONGTEXT NOT NULL,
    `date_added` datetime NOT NULL,
    `date_updated` datetime NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumreplies`
    ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumreplies`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
}

if (!$CI->db->table_exists(db_prefix() . 'flexforumbans')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'flexforumbans` (
    `id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `user_type` varchar(10) NOT NULL,
    `date_added` datetime NOT NULL,
    `date_updated` datetime NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumbans`
    ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumbans`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumlikes` ADD COLUMN `banned` tinyint(1) NOT NULL DEFAULT 0');
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumreplies` ADD COLUMN `banned` tinyint(1) NOT NULL DEFAULT 0');
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumtopics` ADD COLUMN `banned` tinyint(1) NOT NULL DEFAULT 0');
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumtopics` ADD COLUMN `closed` tinyint(1) NOT NULL DEFAULT 0');
}

if (!$CI->db->table_exists(db_prefix() . 'flexforumfollowers')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . 'flexforumfollowers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` varchar(10) NOT NULL,
  `type_id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `date_added` datetime NOT NULL,
  `banned` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

  $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumfollowers`
  ADD PRIMARY KEY (`id`);');

  $CI->db->query('ALTER TABLE `' . db_prefix() . 'flexforumfollowers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
}

if (!$CI->db->field_exists('topic_file', db_prefix() . 'flexforumtopics')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . "flexforumtopics`
      ADD COLUMN `topic_file` VARCHAR(200) NULL
      ;");
}

if (!$CI->db->field_exists('error_code', db_prefix() . 'flexforumtopics')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . "flexforumtopics`
      ADD COLUMN `error_code` VARCHAR(200) NULL
      ;");
}

if (!$CI->db->field_exists('replay_file', db_prefix() . 'flexforumreplies')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . "flexforumreplies`
      ADD COLUMN `replay_file` VARCHAR(200) NULL;");
}

if (!$CI->db->field_exists('parent_id', db_prefix() . 'flexforumcategories')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . "flexforumcategories`
      ADD COLUMN `parent_id` int(11) NOT NULL;");
}

if (!$CI->db->field_exists('childcategory', db_prefix() . 'flexforumtopics')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . "flexforumtopics`
      ADD COLUMN `childcategory` int(11) NOT NULL;");
}

if (!$CI->db->field_exists('model_id', db_prefix() . 'flexforumtopics')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . "flexforumtopics`
      ADD COLUMN `model_id` int(25) NOT NULL;");
}


//create email templates
$CI->load->library('flexforum/notifications_module');
$CI->notifications_module->create_email_template();
