<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Version_111 extends App_module_migration
{

    public function up()
    {

        $CI = &get_instance();

        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_triggers' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_triggers` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `trigger_name` varchar(255) DEFAULT NULL,
                    `template_id` int(11) DEFAULT NULL,
                    `rel_type` varchar(20) DEFAULT NULL,
                    `options` varchar(255) DEFAULT NULL,
                    `staff_active` tinyint(4) DEFAULT 0,
                    `client_active` tinyint(4) DEFAULT 0,
                    `status` tinyint(4) DEFAULT 1,
                    `sending_hour` tinyint(4) DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `template_id` (`template_id`),
                    KEY `rel_type` (`rel_type`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }

        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_trigger_logs' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_trigger_logs` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `trigger_id` int(11) DEFAULT NULL,
                      `trigger_rel_id` int(11) DEFAULT NULL,
                      `send_rel_type` varchar(25) DEFAULT NULL,
                      `send_rel_id` int(11) DEFAULT 0,
                      `mail_id` int(11) DEFAULT NULL,
                      `date` datetime DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `trigger_id` (`trigger_id`),
                      KEY `send_rel_type` (`send_rel_type`),
                      KEY `send_rel_id` (`send_rel_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }


        if( !$CI->db->field_exists('trigger_id', db_prefix() .'email_template_manage_mail_logs') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_mail_logs`
                               ADD COLUMN `trigger_id` int NULL DEFAULT 0 AFTER `system_template_slug`, ADD INDEX(`trigger_id`);');

        }



    }

}
