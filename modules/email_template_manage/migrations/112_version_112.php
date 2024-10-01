<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Version_112 extends App_module_migration
{

    public function up()
    {

        $CI = &get_instance();


        if( !$CI->db->field_exists('send_rel_type', db_prefix() .'email_template_manage_mail_logs') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_mail_logs`
                                ADD COLUMN `send_rel_type` varchar(50) NULL AFTER `trigger_id`,
                                ADD COLUMN `send_rel_id` int NULL AFTER `send_rel_type`;');

        }


        if( !$CI->db->field_exists('lead_statuses', db_prefix() .'email_template_manage_timer') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_timer`
                                MODIFY COLUMN `client_groups` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `template_id`,
                                ADD COLUMN `lead_statuses` varchar(150) NULL AFTER `client_groups`,
                                ADD COLUMN `lead_sources` varchar(150) NULL AFTER `lead_statuses`;');

        }



    }

}
