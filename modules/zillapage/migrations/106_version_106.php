<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_106 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        if (!$CI->db->field_exists('slug', db_prefix() . 'landing_pages')) {
            $CI->db->query('ALTER TABLE `' . db_prefix() . 'landing_pages` ADD `slug` VARCHAR(191) AFTER `name`');
        }
    }
}
