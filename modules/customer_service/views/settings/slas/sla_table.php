<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	'id',
	'name',
	'grace_period',
	'over_due_warning_alert',
	'event',
	'breach_action',
	'breach_action_value',
	'breach_action_agent_manager',
	'hours_of_operation',
	'status',
	'datecreated',
	'1',
];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'cs_service_level_agreements';

$where = [];
$join= [];


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id','name','status','grace_period','over_due_warning_alert','event','breach_action','breach_action_value','breach_action_agent_manager','hours_of_operation','admin_note','datecreated']);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];

	$row[] = $aRow['id'];
	$row[] = $aRow['name'];
	$row[] = $aRow['grace_period'];
	$row[] = _l($aRow['over_due_warning_alert']);

	if($aRow['event'] != null && new_strlen($aRow['event']) > 0){
		$row[] = _l('cs_'.$aRow['event']);
	}else{
		$row[] = $aRow['event'];
	}

	if($aRow['breach_action'] != null && new_strlen($aRow['breach_action']) > 0){
		$row[] = _l('cs_'.$aRow['breach_action']);
	}else{
		$row[] = $aRow['breach_action'];
	}

	if($aRow['breach_action_value'] != null && new_strlen($aRow['breach_action_value']) > 0){
		$row[] = _l('cs_'.$aRow['breach_action_value']);
	}else{
		$row[] = $aRow['breach_action_value'];
	}
	
	if($aRow['breach_action_agent_manager'] != null && new_strlen($aRow['breach_action_agent_manager']) != 0){
		$row[] = get_staff_full_name($aRow['breach_action_agent_manager']);
	}else{
		$row[] = $aRow['breach_action_agent_manager'];
	}

	if($aRow['hours_of_operation'] != null && new_strlen($aRow['hours_of_operation']) > 0){
		$row[] = _l('cs_'.$aRow['hours_of_operation']);
	}else{
		$row[] = $aRow['hours_of_operation'];
	}

	$status = '';
	$checked = '';
	if ($aRow['status'] == 'enabled') {
		$checked = 'checked';
	}

	$status .= '<div class="onoffswitch">
	<input type="checkbox" ' . (((is_admin() || !has_permission('customer_service', '', 'edit')) && !is_admin()) ? 'disabled' : '') . ' data-switch-url="' . admin_url() . 'customer_service/change_sla_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" data-status="' . $aRow['status'] . '" ' . $checked . '>
	<label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
	</div>';

	$row[] = $status;

	$row[] = _dt($aRow['datecreated']);

	$options = '';
	if((has_permission('customer_service', '', 'view') )){
		$options .= icon_btn('customer_service/sla_detail/'.$aRow['id'], 'fa-solid fa-eye', 'btn-default', []);
	}

	if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
		$options .= icon_btn('customer_service/sla_warning_manage/'.$aRow['id'], 'fa-regular fa-pen-to-square', 'btn-default', []);
	}

	if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
		$options .= icon_btn('customer_service/delete_sla/' . $aRow['id'], 'fa fa-remove', 'btn-danger _delete', ['data-original-title' => _l('delete'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']);
	}

	$row[] = $options;

	$output['aaData'][] = $row;
}

