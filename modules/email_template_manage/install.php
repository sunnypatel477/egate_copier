<?php


defined('BASEPATH') or exit('No direct script access allowed');

$CI = & get_instance();

if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_templates' ) )
{

    $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_templates` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `template_name` varchar(150) DEFAULT NULL,
                      `template_content` text DEFAULT NULL,
                      `template_subject` varchar(255) DEFAULT NULL,
                      `status` tinyint(4) DEFAULT 1,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

}



if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_timer' ) )
{

    $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_timer` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `setting_name` varchar(255) DEFAULT NULL,
                      `template_id` int(11) DEFAULT NULL,
                      `client_groups` varchar(100) DEFAULT NULL,
                      `leads` varchar(255) DEFAULT NULL,
                      `clients` varchar(255) DEFAULT NULL,
                      `sending_date` date DEFAULT NULL,
                      `status` tinyint(4) DEFAULT 1,
                      `send_status` tinyint(4) DEFAULT 0,
                      `sending_hour` tinyint(4) DEFAULT 0,
                      `not_clients` varchar(255) DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `template_id` (`template_id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

}



if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_sending_logs' ) )
{

    $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_sending_logs` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `mail_id` int(11) DEFAULT NULL,
                      `client_id` int(11) DEFAULT NULL,
                      `lead_id` int(11) DEFAULT NULL,
                      `status` tinyint(4) DEFAULT 0,
                      `error_message` varchar(500) DEFAULT NULL,
                      `date` datetime DEFAULT NULL,
                      `mail_address` varchar(255) DEFAULT NULL,
                      `mail_company` varchar(255) DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `mail_id` (`mail_id`),
                      KEY `client_id` (`client_id`),
                      KEY `lead_id` (`lead_id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

}

