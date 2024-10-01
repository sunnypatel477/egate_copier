<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Version_110 extends App_module_migration
{

    public function up()
    {

        $CI = &get_instance();

        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_system_templates' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_system_templates` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `template_slug` varchar(100) DEFAULT NULL,
                      `status` tinyint(1) DEFAULT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }



        if( !$CI->db->field_exists('system_template_id', db_prefix() .'email_template_manage_mail_logs') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_mail_logs`
                                ADD COLUMN `system_template_id` varchar(100) NULL AFTER `date_opened`,
                                ADD COLUMN `system_template_slug` varchar(100) NULL AFTER `system_template_id`;');

        }


    }

}
