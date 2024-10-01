<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Version_116 extends App_module_migration
{

    public function up()
    {
        $CI = &get_instance();


        $CI->db->query("
                ALTER TABLE `".db_prefix()."email_template_manage_templates` 
                        MODIFY COLUMN `related_type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'all' AFTER `template_subject`;
            ");



    }

}
