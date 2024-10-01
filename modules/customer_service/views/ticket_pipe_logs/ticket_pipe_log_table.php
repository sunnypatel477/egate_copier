<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	'name',
	'date',
	'email_to',
	'email',
	'subject',
	'message',
	'status',
	'ticket_id',
	'1',
];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'cs_tickets_pipe_logs';

$where = [];
$join= [];

if ($this->ci->input->post('activity_log_date')) {
	array_push($where, 'AND date LIKE "' . $this->ci->db->escape_like_str(to_sql_date($this->ci->input->post('activity_log_date'))) . '%" ESCAPE \'!\'');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id','name','date','email_to','email','subject','message','status']);

	$output = $result['output'];
	$rResult = $result['rResult'];

	foreach ($rResult as $aRow) {
		$row = [];

		$row[] = $aRow['name'];
		$row[] = _dt($aRow['date']);
		$row[] = $aRow['email_to'];
		$row[] = $aRow['email'];
		$row[] = $aRow['subject'];
		$row[] = mb_substr($aRow['message'], 0, 800);
		$row[] = $aRow['status'];
		if($aRow['ticket_id'] != 0){
			$row[] = '<a href="' . admin_url('customer_service/ticket_detail/' . $aRow['ticket_id'] ).'" >' . cs_get_ticket_code($aRow['ticket_id']) . '</a>';;
		}else{
			$row[] = '';
		}

		$options = '';
		if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
			if($aRow['ticket_id'] == 0){
			}
		}

		if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
			$options .= icon_btn('customer_service/delete_tickets_pipe_log/' . $aRow['id'], 'fa fa-remove', 'btn-danger _delete', ['data-original-title' => _l('delete'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']);
		}

		$row[] = $options;

		$output['aaData'][] = $row;
	}

