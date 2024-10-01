<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Version_114 extends App_module_migration
{

    public function up()
    {
        $CI = &get_instance();


        if( !$CI->db->field_exists('related_type', db_prefix() .'email_template_manage_templates') )
        {

            $CI->db->query("ALTER TABLE `".db_prefix()."email_template_manage_templates`
                                ADD COLUMN `related_type` varchar(30) NULL DEFAULT 'all' AFTER `template_subject`;");

        }

    }
}
