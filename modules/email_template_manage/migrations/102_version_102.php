<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Version_102 extends App_module_migration
{

    public function up()
    {

        $CI = &get_instance();

        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_mail_logs' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_mail_logs` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `rel_type` varchar(50) DEFAULT NULL,
                      `rel_id` int(11) DEFAULT NULL,
                      `template_id` int(11) DEFAULT NULL,
                      `company_name` varchar(150) DEFAULT NULL,
                      `company_email` varchar(250) DEFAULT NULL,
                      `company_cc` varchar(250) DEFAULT NULL,
                      `error_message` text DEFAULT NULL,
                      `mail_subject` varchar(255) DEFAULT NULL,
                      `content` text DEFAULT NULL,
                      `date` datetime DEFAULT NULL,
                      `status` tinyint(4) DEFAULT 1,
                      PRIMARY KEY (`id`),
                      KEY `rel_type` (`rel_type`),
                      KEY `rel_id` (`rel_id`),
                      KEY `template_id` (`template_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }


    }

}
