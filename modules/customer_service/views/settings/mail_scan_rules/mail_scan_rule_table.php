<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	'id',
	'type',
	'rel_type',
	'value',
	'status',
	'datecreated',
	'staffid',
	'1',
];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'cs_spam_filters';

$where = [];
$join= [];

$blocked_sender_filter = $this->ci->input->post('blocked_sender_filter');
$blocked_subject_filter = $this->ci->input->post('blocked_subject_filter');
$blocked_phrase_filter = $this->ci->input->post('blocked_phrase_filter');
$allowed_sender_filter = $this->ci->input->post('allowed_sender_filter');
$allowed_subject_filter = $this->ci->input->post('allowed_subject_filter');
$allowed_phrase_filter = $this->ci->input->post('allowed_phrase_filter');

if($blocked_sender_filter && $blocked_sender_filter == 1){
	$where[] = 'AND '.db_prefix().'cs_spam_filters.type = "sender" AND '.db_prefix().'cs_spam_filters.rel_type = "blocked"';
}
if($blocked_subject_filter && $blocked_subject_filter == 1){
	$where[] = 'AND '.db_prefix().'cs_spam_filters.type = "subject" AND '.db_prefix().'cs_spam_filters.rel_type = "blocked"';
}
if($blocked_phrase_filter && $blocked_phrase_filter == 1){
	$where[] = 'AND '.db_prefix().'cs_spam_filters.type = "phrase" AND '.db_prefix().'cs_spam_filters.rel_type = "blocked"';
}
if($allowed_sender_filter && $allowed_sender_filter == 1){
	$where[] = 'AND '.db_prefix().'cs_spam_filters.type = "sender" AND '.db_prefix().'cs_spam_filters.rel_type = "allowed"';
}
if($allowed_subject_filter && $allowed_subject_filter == 1){
	$where[] = 'AND '.db_prefix().'cs_spam_filters.type = "subject" AND '.db_prefix().'cs_spam_filters.rel_type = "allowed"';
}
if($allowed_phrase_filter && $allowed_phrase_filter == 1){
	$where[] = 'AND '.db_prefix().'cs_spam_filters.type = "phrase" AND '.db_prefix().'cs_spam_filters.rel_type = "allowed"';
}


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['type', 'rel_type', 'value', 'status']);

$output = $result['output'];
$rResult = $result['rResult'];


foreach ($rResult as $aRow) {
	$row = [];
	$row[] = $aRow['id'];
	$row[] = $aRow['type'];
	$row[] = $aRow['rel_type'];
	$row[] = $aRow['value'];

	$status = '';
	$checked = '';
	if ($aRow['status'] == 'enabled') {
		$checked = 'checked';
	}

	$status .= '<div class="onoffswitch">
	<input type="checkbox" ' . (((is_admin() || !has_permission('customer_service', '', 'edit')) && !is_admin()) ? 'disabled' : '') . ' data-switch-url="' . admin_url() . 'customer_service/change_mail_scan_rule_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" data-status="' . $aRow['status'] . '" ' . $checked . '>
	<label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
	</div>';

	$row[] = $status;
	$row[] = _dt($aRow['datecreated']);
	$row[] = $aRow['staffid'];

	$options = '';

	if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
		$options .= icon_btn('#', 'fa-regular fa-pen-to-square', 'btn-default', [
			'onclick'    => 'edit_spam_filter(this,' . $aRow['id'] . '); return false;',
			'data-value' => $aRow['value'],
			'data-type'  => $aRow['type'],
			'data-rel_type'  => $aRow['rel_type'],
		]);
	}

	if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
		$options .= icon_btn('customer_service/delete_mail_scan_rule/' . $aRow['id'], 'fa fa-remove', 'btn-danger _delete', ['data-original-title' => _l('delete'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']);
	}

	$row[] = $options;

	$output['aaData'][] = $row;
}

