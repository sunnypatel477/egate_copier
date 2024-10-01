<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Knowledge Base attachments upload array
 * Multiple knowledge_base attachments can be upload if input type is array or dropzone plugin is used
 * @param  mixed $knowledge_base_id     knowledge_base id
 * @param  string $index_name attachments index, in different forms different index name is used
 * @return mixed
 */
function handle_knowledge_base_attachments_array($knowledge_base_id, $index_name = 'attachments')
{
    $uploaded_files = [];
    $path           = FCPATH . 'uploads/' . KNOWLEDGE_BASE_MODULE_NAME . '/' . $knowledge_base_id . '/';
    $CI             = &get_instance();

    if (isset($_FILES[$index_name]['name'])
        && ($_FILES[$index_name]['name'] != '' || is_array($_FILES[$index_name]['name']) && count($_FILES[$index_name]['name']) > 0)) {
        if (!is_array($_FILES[$index_name]['name'])) {
            $_FILES[$index_name]['name']     = [$_FILES[$index_name]['name']];
            $_FILES[$index_name]['type']     = [$_FILES[$index_name]['type']];
            $_FILES[$index_name]['tmp_name'] = [$_FILES[$index_name]['tmp_name']];
            $_FILES[$index_name]['error']    = [$_FILES[$index_name]['error']];
            $_FILES[$index_name]['size']     = [$_FILES[$index_name]['size']];
        }

        _file_attachments_index_fix($index_name);
        for ($i = 0; $i < count($_FILES[$index_name]['name']); $i++) {
            // Get the temp file path
            $tmpFilePath = $_FILES[$index_name]['tmp_name'][$i];

            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                if (_perfex_upload_error($_FILES[$index_name]['error'][$i])
                    || !_upload_extension_allowed($_FILES[$index_name]['name'][$i])) {
                    continue;
                }

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $filename    = unique_filename($path, $_FILES[$index_name]['name'][$i]);
                $newFilePath = $path . $filename;

                // Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    array_push($uploaded_files, [
                        'file_name' => $filename,
                        'filetype'  => $_FILES[$index_name]['type'][$i],
                    ]);

                    if (is_image($newFilePath)) {
                        create_img_thumb($path, $filename);
                    }
                }
            }
        }
    }

    if (count($uploaded_files) > 0) {
        return $uploaded_files;
    }

    return false;
}

function get_knowledge_base_attachments_by_id($id)
{
    $CI             = &get_instance();
    $CI->load->model(KNOWLEDGE_BASE_MODULE_NAME . '/knowledge_model');
    return $CI->knowledge_model->get_knowledge_base_attachments($id);
}
