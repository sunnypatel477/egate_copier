<?php

defined('BASEPATH') or exit('No direct script access allowed');

add_option('google_sheet_can_access', 'no');
add_option('google_sheet_can_manage', 'no');
add_option('google_sheet_client_id', '');
add_option('google_sheet_client_secret', '');

if (!$CI->db->table_exists(db_prefix() . 'google_sheets')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'google_sheets` (
        `id` int(11) NOT NULL,
        `staffid` int(11) NOT NULL,
        `sheetid` varchar(256) NOT NULL,
        `title` varchar(256) NOT NULL,
        `description` varchar(1024) NULL,
        `date` datetime NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'google_sheets` ADD PRIMARY KEY (`id`);');
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'google_sheets` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
}