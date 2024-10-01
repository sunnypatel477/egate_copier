<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	'id',
	'code',
	'created_id',
	'created_type',
	'client_id',
	'ticket_source',
	'category_id',
	'department_id',
	'assigned_id',
	'sla_id',
	'time_spent',
	'due_date',
	'issue_summary',
	'priority_level',
	'ticket_type',
	'last_message_time',
	'last_response_time',
	'first_reply_time',
	'last_update_time',
	'status',
	'datecreated',
];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'cs_tickets';

$where = [];
$join= [];

$client_filter = $this->ci->input->post('client_filter');
$category_filter = $this->ci->input->post('category_filter');
$priority_filter = $this->ci->input->post('priority_filter');
$ticket_status_filter = $this->ci->input->post('ticket_status_filter');

if (isset($client_filter)) {
	$where_client_ft = '';
	foreach ($client_filter as $client_id) {
		if ($client_id != '') {
			if ($where_client_ft == '') {
				$where_client_ft .= 'AND ('.db_prefix().'cs_tickets.client_id = "' . $client_id . '"';
			} else {
				$where_client_ft .= ' or '.db_prefix().'cs_tickets.client_id = "' . $client_id . '"';
			}
		}
	}
	if ($where_client_ft != '') {
		$where_client_ft .= ')';
		array_push($where, $where_client_ft);
	}
}

if (isset($category_filter)) {
	$where_category_ft = '';
	foreach ($category_filter as $category_id) {
		if ($category_id != '') {
			if ($where_category_ft == '') {
				$where_category_ft .= 'AND ('.db_prefix().'cs_tickets.category_id = "' . $category_id . '"';
			} else {
				$where_category_ft .= ' or '.db_prefix().'cs_tickets.category_id = "' . $category_id . '"';
			}
		}
	}
	if ($where_category_ft != '') {
		$where_category_ft .= ')';
		array_push($where, $where_category_ft);
	}
}

if (isset($priority_filter)) {
	$where_priority_ft = '';
	foreach ($priority_filter as $priority_level) {
		if ($priority_level != '') {
			if ($where_priority_ft == '') {
				$where_priority_ft .= 'AND ('.db_prefix().'cs_tickets.priority_level = "' . $priority_level . '"';
			} else {
				$where_priority_ft .= ' or '.db_prefix().'cs_tickets.priority_level = "' . $priority_level . '"';
			}
		}
	}
	if ($where_priority_ft != '') {
		$where_priority_ft .= ')';
		array_push($where, $where_priority_ft);
	}
}

if (isset($ticket_status_filter)) {
	$where_ticket_status_ft = '';
	foreach ($ticket_status_filter as $status) {
		if ($status != '') {
			if ($where_ticket_status_ft == '') {
				$where_ticket_status_ft .= 'AND ('.db_prefix().'cs_tickets.status = "' . $status . '"';
			} else {
				$where_ticket_status_ft .= ' or '.db_prefix().'cs_tickets.status = "' . $status . '"';
			}
		}
	}
	if ($where_ticket_status_ft != '') {
		$where_ticket_status_ft .= ')';
		array_push($where, $where_ticket_status_ft);
	}
}



$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id','created_id','created_type','client_id','ticket_source','category_id','department_id','assigned_id','sla_id','time_spent','due_date','issue_summary','priority_level','ticket_type','internal_note','last_message_time','last_response_time','first_reply_time','last_update_time','resolution','status','datecreated','dateupdated']);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];

	$row[] = $aRow['id'];

	$name = '<a href="' . admin_url('customer_service/ticket_detail/' . $aRow['id'] ).'" >' . $aRow['code'] . '</a>';
	$name .= '<div class="row-options">';
	$name .= '<a href="' . admin_url('customer_service/ticket_detail/' . $aRow['id'] ).'" >' . _l('view') . '</a>';

	if((has_permission('customer_service', '', 'edit') || is_admin())){
		if($aRow['status'] == 'open'){
			$name .= ' | <a href="' . admin_url('customer_service/add_edit_ticket/' . $aRow['id'] ).'" >' . _l('edit') . '</a>';
		}
	}

	if ((has_permission('customer_service', '', 'delete') || is_admin()) ) {
		$name .= ' | <a href="' . admin_url('customer_service/delete_ticket/' . $aRow['id'] ).'" class="text-danger _delete" >' . _l('delete') . '</a>';
	}

	$name .= '</div>';
	$row[] = $name;

	if($aRow['created_type'] == 'staff'){
		$row[] = get_staff_full_name($aRow['created_id']);
	}else{
		$row[] = get_contact_full_name($aRow['created_id']);
	}

	$row[] = $aRow['created_type'];

	$row[] = get_company_name($aRow['client_id']);
	$row[] = _l($aRow['ticket_source']);
	$row[] = cs_get_category_name($aRow['category_id']);
	$row[] = cs_get_department_name($aRow['department_id']);
	$row[] = get_staff_full_name($aRow['assigned_id']);
	$row[] = cs_get_sla_name($aRow['sla_id']);
	$row[] = $aRow['time_spent'];
	if(strtotime($aRow['due_date']) < strtotime(date('Y-m-d H:i:s'))){
		$row[] = '<span class="text-danger">'.$aRow['due_date'].'</span>';
	}else{
		$row[] = $aRow['due_date'];
	}

	$row[] = $aRow['issue_summary'];
	$row[] = render_customer_status_html($aRow['id'], 'priority', $aRow['priority_level']);
	$row[] = render_customer_status_html($aRow['id'], 'ticket_type', $aRow['ticket_type']);
	$row[] = $aRow['last_message_time'];
	$row[] = $aRow['last_response_time'];
	$row[] = $aRow['first_reply_time'];
	$row[] = $aRow['last_update_time'];
	$row[] = render_customer_status_html($aRow['id'], 'ticket_status', $aRow['status']);

	$row[] = $aRow['datecreated'];


	$output['aaData'][] = $row;
}

