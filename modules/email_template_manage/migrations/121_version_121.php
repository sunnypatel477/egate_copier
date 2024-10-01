<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Version_121 extends App_module_migration
{

    public function up()
    {

        $CI = &get_instance();


        $CI->db->query("
                ALTER TABLE `".db_prefix()."email_template_manage_templates` 
                        MODIFY COLUMN `related_type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'all' AFTER `template_subject`;
            ");

        if( !$CI->db->field_exists('webhook_id', db_prefix() .'email_template_manage_mail_logs') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_mail_logs`
                                ADD COLUMN `webhook_id` int NULL DEFAULT 0 AFTER `smtp_setting_id`,
                                ADD INDEX(`smtp_setting_id`),
                                ADD INDEX(`webhook_id`);');

        }



        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_webhooks' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_webhooks` (
                       `id` int(11) NOT NULL AUTO_INCREMENT,
                      `webhook_name` varchar(255) DEFAULT NULL,
                      `webhook_trigger` varchar(255) DEFAULT NULL,
                      `template_id` int(11) DEFAULT NULL,
                      `staff_active` tinyint(4) DEFAULT 0,
                      `client_active` tinyint(4) DEFAULT 0,
                      `options` varchar(255) DEFAULT NULL,
                      `status` tinyint(4) DEFAULT 1,
                      PRIMARY KEY (`id`),
                      KEY `template_id` (`template_id`),
                      KEY `webhook_trigger` (`webhook_trigger`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }



    }

}
