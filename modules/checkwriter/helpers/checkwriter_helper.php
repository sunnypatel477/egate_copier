<?php
defined('BASEPATH') or exit('No direct script access allowed');

function get_bank_details($id = '')
{
    $CI = &get_instance();
    $CI->db->select('id,bank_name');
    if ($id) {
        $CI->db->where('id', $id);
        return $CI->db->get(db_prefix() . 'bank_details')->row();
    } else {
        return $CI->db->get(db_prefix() . 'bank_details')->result_array();
    }
}

function expense_note_pdf($expense)
{
    return app_pdf('expense_note_custom', module_libs_path(CHECKWRITER_MODULE) . 'pdf/expensive_report', $expense);
}


function acc_get_vendor_name_checkwriter($vendor_id, $prevent_empty_company = false)
{

    $CI = &get_instance();

    $select = 'company';

    $vendor = $CI->db->select($select)
        ->where('userid', $vendor_id)
        ->from(db_prefix() . 'pur_vendor')
        ->get()
        ->row();
    if ($vendor) {
        return $vendor->company;
    }

    return '';
}

function is_activated_module($name)
{
    $CI = &get_instance();
    $CI->db->select('active');
    $CI->db->where('module_name', $name);
    return $CI->db->get(db_prefix() . 'modules')->row();
}

function count_check_history($id) {
    $CI = &get_instance();
    $CI->db->select('COUNT(id) as count, id');
    $CI->db->where('expensive_id', $id);
    return $CI->db->get(db_prefix() . 'expensive_history')->row();
}

function get_check_history_data_by_expensive($id)
{
    $CI = &get_instance();
    $CI->db->where('expensive_id', $id);
    return  $CI->db->get(db_prefix() . 'expensive_history')->row();
}