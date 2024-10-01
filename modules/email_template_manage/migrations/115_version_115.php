<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Version_115 extends App_module_migration
{

    public function up()
    {
        $CI = &get_instance();

        if ( !$CI->db->table_exists( db_prefix() . 'email_template_smtp_settings' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_smtp_settings` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `company_name` varchar(200) DEFAULT NULL,
                      `mail_engine` varchar(30) DEFAULT NULL,
                      `email_protocol` varchar(10) DEFAULT NULL,
                      `smtp_encryption` varchar(10) DEFAULT NULL,
                      `smtp_host` varchar(150) DEFAULT NULL,
                      `smtp_port` varchar(20) DEFAULT NULL,
                      `smtp_email` varchar(150) DEFAULT NULL,
                      `smtp_username` varchar(150) DEFAULT NULL,
                      `smtp_password` varchar(100) DEFAULT NULL,
                      `smtp_email_charset` varchar(20) DEFAULT NULL,
                      `is_public` tinyint(4) DEFAULT 1,
                      `status` tinyint(4) DEFAULT 1,
                      `active_staff` varchar(255) DEFAULT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }



        if( !$CI->db->field_exists('smtp_setting_id', db_prefix() .'email_template_manage_mail_logs') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_mail_logs`
                                ADD COLUMN `smtp_setting_id` int NULL DEFAULT 0 AFTER `send_rel_id`;');

        }


    }

}
