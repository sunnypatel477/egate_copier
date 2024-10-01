<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	'emailtemplateid',
	'type',
	'name',
	'subject',
	'active',
	'1',
];
$sIndexColumn = 'emailtemplateid';
$sTable = db_prefix() . 'emailtemplates';

$where = [];
$join= [];

	  //View own
$email_template_ids = $this->ci->customer_service_model->get_customer_service_email_template();
if (count($email_template_ids) > 0) {
	$where[] = 'AND '.db_prefix().'emailtemplates.emailtemplateid IN (' . implode(', ', $email_template_ids) . ') AND type = "customer_service_email_template"';

}else{
	$where[] = 'AND 1=2';
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['type', 'slug', 'language', 'name', 'subject', 'message', 'fromname', 'fromemail', 'plaintext', 'active']);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];
	$row[] = $aRow['emailtemplateid'];
	$row[] = $aRow['type'];
	$row[] = $aRow['name'];
	$row[] = $aRow['subject'];

	$status = '';
	$checked = '';
	if ($aRow['active'] == '1') {
		$checked = 'checked';
	}

	$status .= '<div class="onoffswitch">
	<input type="checkbox" ' . (((is_admin() || !has_permission('customer_service', '', 'edit')) && !is_admin()) ? 'disabled' : '') . ' data-switch-url="' . admin_url() . 'customer_service/change_kpi_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['emailtemplateid'] . '" data-id="' . $aRow['emailtemplateid'] . '" data-status="' . $aRow['active'] . '" ' . $checked . '>
	<label class="onoffswitch-label" for="c_' . $aRow['emailtemplateid'] . '"></label>
	</div>';

	$row[] = $status;

	$options = '';

	if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
		$options .= icon_btn('customer_service/add_edit_email_template/' . $aRow['emailtemplateid'], 'fa-regular fa-pen-to-square', 'btn-primary', ['data-original-title' => _l('edit'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']);
		
	}

	if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
		$options .= icon_btn('customer_service/delete_email_template/' . $aRow['emailtemplateid'], 'fa fa-remove', 'btn-danger _delete', ['data-original-title' => _l('delete'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']);
	}

	$row[] = $options;

	$output['aaData'][] = $row;
}

