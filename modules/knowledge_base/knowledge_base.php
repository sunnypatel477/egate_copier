<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
  Module Name: Knowledge Base
  Description: Default module for Knowledge Base
  Version: 2.3.0
  Requires at least: 2.3.*
*/

/*
 * Knowledge Base Module
*/
define('KNOWLEDGE_BASE_MODULE_NAME', 'knowledge_base');

define('RE_REVISION', 116);

// Register language files, must be registered if the module is using languages
register_language_files(KNOWLEDGE_BASE_MODULE_NAME, [KNOWLEDGE_BASE_MODULE_NAME]);

// hooks()->add_filter('download_file_path', 'add_knowledge_base_in_download_file_path', 10, 2);
hooks()->add_action('app_admin_footer', 'knowledge_base_add_footer_components');

/**
 * Load the module helper.
 */
$CI = &get_instance();
$CI->load->helper(KNOWLEDGE_BASE_MODULE_NAME . '/knowledge_base');

// function add_knowledge_base_in_download_file_path($path, $data)
// {
//     $CI = &get_instance();

//     if ($data['folder_indicator'] == 'knowledgebaseattachment') {
//         if (!is_staff_logged_in() && strpos($_SERVER['HTTP_REFERER'], 'forms/l/') === false) {
//             show_404();
//         }

//         // admin area
//         if ($data['folder_indicator'] == 'estimate_request_attachment') {
//             $CI->db->where('id', $data['attachmentid']);
//         } else {
//             // Lead public form
//             $CI->db->where('attachment_key', $data['attachmentid']);
//         }

//         $attachment = $CI->db->get(db_prefix() . 'files')->row();

//         if (!$attachment) {
//             show_404();
//         }

//         $path = FCPATH . 'uploads/' . KNOWLEDGE_BASE_MODULE_NAME . '/' . $attachment->rel_id . '/' . $attachment->file_name;

//         return $path;
//     }
// }

function knowledge_base_add_footer_components()
{
    echo '<script src="' . module_dir_url(KNOWLEDGE_BASE_MODULE_NAME, 'js/knowledge_base.js') . '?v=' . RE_REVISION . '"></script>';
}
