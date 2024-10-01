<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	db_prefix().'cs_ticket_categories.id as id',
	'category_name',
	'type',
	'priority',
	'work_flow_id',
	'department_id',
	db_prefix().'cs_ticket_categories.status as status',
	'category_default',
	db_prefix().'cs_ticket_categories.datecreated as datecreated',
	'1',
];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'cs_ticket_categories';

$where = [];
$join = [
	'LEFT JOIN ' . db_prefix() . 'cs_work_flows ON '.db_prefix().'cs_work_flows.id = ' . db_prefix() . 'cs_ticket_categories.work_flow_id'
];


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['category_name',db_prefix().'cs_ticket_categories.status','type','priority','work_flow_id','department_id','custom_form_id','thank_you_page_id','auto_response',db_prefix().'cs_ticket_categories.datecreated', 'workflow_name']);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];

	$row[] = $aRow['id'];
	$row[] = $aRow['category_name'];
	$row[] = _l($aRow['type']);
	$row[] = _l('cs_'.$aRow['priority']);
	$row[] = $aRow['workflow_name'];
	$row[] = cs_get_department_name($aRow['department_id']);

	$status = '';
	$checked = '';
	if ($aRow['status'] == 'enabled') {
		$checked = 'checked';
	}

	$status .= '<div class="onoffswitch">
	<input type="checkbox" ' . (((is_admin() || !has_permission('customer_service', '', 'edit')) && !is_admin()) ? 'disabled' : '') . ' data-switch-url="' . admin_url() . 'customer_service/change_category_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" data-status="' . $aRow['status'] . '" ' . $checked . '>
	<label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
	</div>';

	$row[] = $status;

	$category_default = '';
	$checked = '';
	if ($aRow['category_default'] == 1) {
		$checked = 'checked';
	}

	if($aRow['category_default'] == 1){
		$category_default .= '<div class="onoffswitch">
		<input type="checkbox" disabled data-switch-url="' . admin_url() . 'customer_service/change_category_default" name="category_default_onoffswitch" class="onoffswitch-checkbox" id="category_default_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" data-category_default="' . $aRow['category_default'] . '" ' . $checked . '>
		<label class="onoffswitch-label" for="category_default_' . $aRow['id'] . '"></label>
		</div>';
	}else{

		$category_default .= '<div class="onoffswitch">
		<input type="checkbox" ' . (((is_admin() || !has_permission('customer_service', '', 'edit') ) && !is_admin() ) ? 'disabled' : '') . ' data-switch-url="' . admin_url() . 'customer_service/change_category_default" name="category_default_onoffswitch" class="onoffswitch-checkbox" id="category_default_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" data-category_default="' . $aRow['category_default'] . '" ' . $checked . '>
		<label class="onoffswitch-label" for="category_default_' . $aRow['id'] . '"></label>
		</div>';
	}

	$row[] = $category_default;

	$row[] = _dt($aRow['datecreated']);

	$options = '';
	
	if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
		$options .= icon_btn('#', 'fa-regular fa-pen-to-square', 'btn-default', [
			'onclick'    => 'category_modal('.$aRow['id'].',' . $aRow['id'] . ', \'updated\'); return false;',
		]);
	}

	if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
		$options .= icon_btn('customer_service/delete_category/' . $aRow['id'], 'fa fa-remove', 'btn-danger _delete', ['data-original-title' => _l('delete'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']);
	}

	$row[] = $options;

	$output['aaData'][] = $row;
}

