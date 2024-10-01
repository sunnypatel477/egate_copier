<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	'id',
	'code',
	'sla_id',
	'kpi_id',
	'status',
	'datecreated',
	'1',
];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'cs_work_flows';

$where = [];
$join= [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id','code','workflow_name','status','workflow','datecreated','dateupdated','staffid',]);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];

	$row[] = $aRow['id'];
	$row[] = $aRow['code']. ' '. $aRow['workflow_name'];

	
	$row[] = cs_get_sla_name($aRow['sla_id']);
	$row[] = cs_get_kpi_name($aRow['kpi_id']);

	$status = '';
	$checked = '';
	if ($aRow['status'] == 'enabled') {
		$checked = 'checked';
	}

	$status .= '<div class="onoffswitch">
	<input type="checkbox" ' . (((is_admin() || !has_permission('customer_service', '', 'edit')) && !is_admin()) ? 'disabled' : '') . ' data-switch-url="' . admin_url() . 'customer_service/change_workflow_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" data-status="' . $aRow['status'] . '" ' . $checked . '>
	<label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
	</div>';

	$row[] = $status;

	$row[] = _dt($aRow['datecreated']);

	$options = '';
	if(1==2){
		$options .= icon_btn('customer_service/work_flow_detail/'.$aRow['id'], 'fa-solid fa-eye', 'btn-default', ['data-original-title' => _l('cs_view_details'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']);
	}

	if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
		$options .= icon_btn('customer_service/add_edit_work_flow/'.$aRow['id'], 'fa-solid fa-bullseye', 'btn-default', ['data-original-title' => _l('view'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']);
	}

	if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){

		$options .= icon_btn('#', 'fa-regular fa-pen-to-square', 'btn-primary', ['data-original-title' => _l('edit'), 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'onclick'    => 'add_workflow_modal('.$aRow['id'].', \'update\'); return false;',
	]);
	}

	if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
		$options .= icon_btn('customer_service/delete_workflow/' . $aRow['id'], 'fa fa-remove', 'btn-danger _delete', ['data-original-title' => _l('delete'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']);
	}

	$row[] = $options;

	$output['aaData'][] = $row;
}

