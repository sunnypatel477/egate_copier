<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	'id',
	'code',
	'first_response_time',
	'average_resolution_time',
	'average_handle_time',
	'number_of_tickets',
	'number_of_resolved_tickets',
	'number_of_tickets_by_medium',
	'escalation_rate',
	'customer_satisfaction_score',
	'status',
	'datecreated',
	'staffid',
	'1',
];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'cs_kpis';

$where = [];
$join= [];


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['first_response_time','first_response_time_measure','average_resolution_time','average_resolution_time_measure','average_handle_time','average_handle_time_measure','number_of_tickets','number_of_resolved_tickets','number_of_tickets_by_medium', 'escalation_rate', 'customer_satisfaction_score', 'datecreated', 'code', 'name']);

$output = $result['output'];
$rResult = $result['rResult'];


foreach ($rResult as $aRow) {
	$row = [];
	$row[] = $aRow['id'];
	$row[] = $aRow['code'].' '.$aRow['name'];
	$row[] = $aRow['first_response_time'].' '._l('cs_'.$aRow['first_response_time_measure']);
	$row[] = $aRow['average_resolution_time'].' '._l('cs_'.$aRow['average_resolution_time_measure']);
	$row[] = $aRow['average_handle_time'].' '._l('cs_'.$aRow['average_handle_time_measure']);
	$row[] = $aRow['number_of_tickets'];
	$row[] = $aRow['number_of_resolved_tickets'];
	$row[] = $aRow['number_of_tickets_by_medium'];
	$row[] = $aRow['escalation_rate'].'%';
	$row[] = $aRow['customer_satisfaction_score'].'%';

	$status = '';
	$checked = '';
	if ($aRow['status'] == 'enabled') {
		$checked = 'checked';
	}

	$status .= '<div class="onoffswitch">
	<input type="checkbox" ' . (((is_admin() || !has_permission('customer_service', '', 'edit')) && !is_admin()) ? 'disabled' : '') . ' data-switch-url="' . admin_url() . 'customer_service/change_kpi_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" data-status="' . $aRow['status'] . '" ' . $checked . '>
	<label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
	</div>';

	$row[] = $status;
	$row[] = _dt($aRow['datecreated']);
	$row[] = $aRow['staffid'];

	$options = '';

	if(has_permission('customer_service', '', 'view')){
		$options .= icon_btn('customer_service/kpi_detail/'.$aRow['id'], 'fa-solid fa-eye', 'btn-default', ['data-original-title' => _l('cs_view_details'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']);
	}


	if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
		$options .= icon_btn('#', 'fa-regular fa-pen-to-square', 'btn-default', [
			'onclick'    => 'edit_kpi(this,' . $aRow['id'] . '); return false;',
			'data-code' => $aRow['code'],
			'data-name'  => $aRow['name'],
			'data-first_response_time'  => $aRow['first_response_time'],
			'data-first_response_time_measure'  => $aRow['first_response_time_measure'],
			'data-average_resolution_time'  => $aRow['average_resolution_time'],
			'data-average_resolution_time_measure'  => $aRow['average_resolution_time_measure'],
			'data-average_handle_time'  => $aRow['average_handle_time'],
			'data-average_handle_time_measure'  => $aRow['average_handle_time_measure'],
			'data-number_of_tickets'  => $aRow['number_of_tickets'],
			'data-number_of_resolved_tickets'  => $aRow['number_of_resolved_tickets'],
			'data-number_of_tickets_by_medium'  => $aRow['number_of_tickets_by_medium'],
			'data-escalation_rate'  => $aRow['escalation_rate'],
			'data-customer_satisfaction_score'  => $aRow['customer_satisfaction_score'],
		]);
	}

	if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
		$options .= icon_btn('customer_service/delete_kpi/' . $aRow['id'], 'fa fa-remove', 'btn-danger _delete', ['data-original-title' => _l('delete'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']);
	}

	$row[] = $options;

	$output['aaData'][] = $row;
}

