<?php

defined('BASEPATH') or exit('No direct script access allowed');


function get_event_category_images_count($event_id,$category_id = '',$type = '')
{
    $CI = &get_instance();
    $CI->db->where('event_category', $event_id);
    if($category_id != ''){
        $CI->db->where('category', $category_id);
    }
    if($type != ''){
        $CI->db->where('type', $type);
    }
    $CI->db->from(db_prefix() . 'instax_printing_background_images');
    return $CI->db->count_all_results();
}


