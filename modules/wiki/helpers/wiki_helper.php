<?php

defined('BASEPATH') or exit('No direct script access allowed');

function wiki_generate_code($s){
  return  md5(uniqid($s, true));
}

function wiki_get_mindmap_thumb($filename = ''){
  if($filename != ''){
    return base_url(WIKI_UPLOAD_PATH.'/storage/mindmap') . '/' . $filename;
  }else{
    return base_url(WIKI_ASSETS_PATH.'/builder/ui/default_thumb.png');
  }
}

function wiki_get_mindmap_content(){
  return '{"data":{"text":"My New Mind Map"},"template":"default","theme":"fresh-blue","version":"1.3.5"}';
}
function handle_wiki_book_attachments_array($knowledge_base_id, $index_name = 'attachments')
{
    $uploaded_files = [];
    $path           = FCPATH . 'uploads/' . WIKI_MODULE_NAME . '/' . $knowledge_base_id . '/';
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

function get_wiki_book_attachments_by_id($id)
{
    $CI             = &get_instance();
    $CI->load->model(WIKI_MODULE_NAME . '/wikibooks_model');
    return $CI->wikibooks_model->get_wiki_book_attachments($id);
}


function handle_wiki_article_attachments_array($knowledge_base_id, $index_name = 'attachments')
{
    $uploaded_files = [];
    $path           = FCPATH . 'uploads/' . WIKI_MODULE_NAME_ARTICAL . '/' . $knowledge_base_id . '/';
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

function get_wiki_article_attachments_by_id($id)
{
    $CI             = &get_instance();
    $CI->load->model(WIKI_MODULE_NAME . '/wikiarticles_model');
    return $CI->wikiarticles_model->get_wiki_article_attachments($id);
}