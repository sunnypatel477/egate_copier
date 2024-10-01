<?php
defined('BASEPATH') or exit('No direct script access allowed');


function lead_count($clientid)
{
    $CI = &get_instance();
    $CI->db->where('existing_client_id', $clientid)->where('status !=', CLOSED_LOST)->where('status !=',CLOSED_WON);
    return  $CI->db->get(db_prefix() . 'leads')->num_rows();
}

function delivery_note_invoice_reference_number($id)
{
    $CI = &get_instance();
    $CI->db->select('reference_no');
    $CI->db->where('invoiceid', $id);
    $result = $CI->db->get(db_prefix() . 'delivery_notes')->row();
    return isset($result->reference_no) ? $result->reference_no : '' ;
}

function get_note_by_lead_id($id)
{
    $CI = &get_instance();
    $CI->db->select('dateadded,description');
    $CI->db->where('rel_id', $id);
    $CI->db->where('rel_type', 'lead');
    $CI->db->order_by('dateadded', 'desc');
    return $CI->db->get(db_prefix() . 'notes')->row();
}

function get_note_by_lead_reminder($id)
{
    $CI = &get_instance();
    $CI->db->select('COUNT(id) AS totalnote');
    $CI->db->where('rel_id', $id);
    $CI->db->where('rel_type', 'lead');
    return $CI->db->get(db_prefix() . 'reminders')->row();
}

function get_note_by_last_contarct_id($id)
{
    $CI = &get_instance();
    $CI->db->select('date,description');
    $CI->db->where('leadid', $id);
    $CI->db->order_by('id', 'desc');
    return $CI->db->get(db_prefix() . 'lead_activity_log')->row();
}

function get_company_name_proposal($id) 
    {
        $CI = &get_instance();
        $CI->db->select('company');
        $CI->db->where('userid', $id);
        $result =  $CI->db->get(db_prefix() . 'clients')->row();
        return isset($result->company) ? $result :'';
    }

function get_lead_name_proposal($id) 
    {
        $CI = &get_instance();
        $CI->db->select('name');
        $CI->db->where('id', $id);
        $result =  $CI->db->get(db_prefix() . 'leads')->row();
        return isset($result->name) ? $result :'';
    }



    // function pending_task_count($id) {
    //     $CI = &get_instance();
    //     $CI->db->select('Count(id) as pending_task_count');
    //     $CI->db->from(db_prefix() . 'tasks');
    //     $CI->db->where('rel_id', $id);
    //     $CI->db->where('rel_type', 'customer');
    //     $CI->db->where('status !=', TASK_PENDING);
    //     $result = $CI->db->get()->row_array();
    //     return $result['pending_task_count'];
    // }

    function pending_task_count($id)
{
    $CI = &get_instance();
    $where = '(';
    $where .= '(rel_id IN (SELECT id FROM ' . db_prefix() . 'invoices WHERE clientid=' . $id . ') AND rel_type="invoice")';
    $where .= ' OR (rel_id IN (SELECT id FROM ' . db_prefix() . 'estimates WHERE clientid=' . $id . ') AND rel_type="estimate")';
    $where .= ' OR (rel_id IN (SELECT id FROM ' . db_prefix() . 'contracts WHERE client=' . $id . ') AND rel_type="contract")';
    $where .= ' OR (rel_id IN (SELECT ticketid FROM ' . db_prefix() . 'tickets WHERE userid=' . $id . ') AND rel_type="ticket")';
    $where .= ' OR (rel_id IN (SELECT id FROM ' . db_prefix() . 'expenses WHERE clientid=' . $id . ') AND rel_type="expense")';
    $where .= ' OR (rel_id IN (SELECT id FROM ' . db_prefix() . 'proposals WHERE rel_id=' . $id . ' AND rel_type="proposal") AND rel_type="proposal")';
    $where .= ' OR (rel_id IN (SELECT userid FROM ' . db_prefix() . 'clients WHERE userid=' . $id . ') AND rel_type="customer")';
    $where .= ' OR (rel_id IN (SELECT id FROM ' . db_prefix() . 'projects WHERE clientid=' . $id . ') AND rel_type="project")';
    $where .= ')';

    $CI->db->where($where);
    $CI->db->where('datefinished is NULL');

    if (!staff_can('view', 'tasks')) {
        $CI->db->where(get_tasks_where_string(false));
    }

    return $CI->db->count_all_results('tasks');
}


?>