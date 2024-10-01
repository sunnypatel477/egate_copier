<?php

defined('BASEPATH') or exit('No direct script access allowed');

//template_for which template should be replaced (based on slug) - single or all
//template_for_language which language of template should be replaced - single or all
if (!$CI->db->table_exists(db_prefix() . 'emailcanvas_templates')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "emailcanvas_templates` (
  `id` int(11) NOT NULL,
  `template_name` text,
  `template_description` text,
  `template_content` text,
  `template_html_css` text,
  `template_for` text,
  `template_for_language` text,
  `is_enabled` int default 1, 
  `created_at` datetime
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'emailcanvas_templates`
  ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'emailcanvas_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}