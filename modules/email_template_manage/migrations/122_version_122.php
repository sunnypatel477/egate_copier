<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Version_122 extends App_module_migration
{

    public function up()
    {

        $CI = &get_instance();


        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_special' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_special` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                              `special_name` varchar(255) DEFAULT NULL,
                              `template_id` int(11) DEFAULT NULL,
                              `rel_type` varchar(20) DEFAULT NULL,
                              `date_field_name` varchar(100) DEFAULT NULL,
                              `is_custom_field` tinyint(4) DEFAULT 0,
                              `status` tinyint(4) DEFAULT 1,
                              `sending_hour` tinyint(4) DEFAULT NULL,
                              PRIMARY KEY (`id`),
                              KEY `template_id` (`template_id`),
                              KEY `rel_type` (`rel_type`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }



        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_special_logs' ) )
        {

            $CI->db->query("CREATE TABLE `".db_prefix()."email_template_manage_special_logs` ( 
                                  `id` int(11) NOT NULL AUTO_INCREMENT,
                                  `special_id` int(11) DEFAULT NULL,
                                  `send_rel_type` varchar(25) DEFAULT NULL,
                                  `send_rel_id` int(11) DEFAULT 0,
                                  `date` date DEFAULT NULL,
                                  PRIMARY KEY (`id`),
                                  KEY `special_id` (`special_id`),
                                  KEY `send_rel_type` (`send_rel_type`),
                                  KEY `send_rel_id` (`send_rel_id`)
                                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        }


    }

}
