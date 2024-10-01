<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_105 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        if (!$CI->db->field_exists('note', db_prefix() . 'landing_page_form_data')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'landing_page_form_data` ADD `note` TEXT AFTER `device`');
        }
    }
}
