<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	'id',
	'service_level_agreement_id',
	'level',
	'action',
	'action_value',
	'agent_manager',
	'order_number',
	'1',
];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'cs_service_level_agreement_warnings';

$where = [];
$join= [];

$service_level_agreement_id = $this->ci->input->post('service_level_agreement_id');
if($this->ci->input->post('service_level_agreement_id')){
	$where_routing_id = '';

	if($service_level_agreement_id != '')
	{
		if($where_routing_id == ''){
			$where_routing_id .= 'AND service_level_agreement_id = "'.$service_level_agreement_id. '"';
		}else{
			$where_routing_id .= ' or service_level_agreement_id = "' .$service_level_agreement_id.'"';
		}
	}
	if($where_routing_id != '')
	{
		array_push($where, $where_routing_id);
	}
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id','service_level_agreement_id','level','action','action_value','agent_manager','order_number',]);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];
	$row[] = $aRow['id'];
	$row[] = $aRow['service_level_agreement_id'];
	$row[] = $aRow['level'];
	if($aRow['action'] != null && new_strlen($aRow['action']) > 0){
		$row[] = _l('cs_'.$aRow['action']);
	}else{
		$row[] = $aRow['action'];
	}

	if(new_strlen($aRow['action_value']) > 0){
		$row[] = _l('cs_'.$aRow['action_value']);
	}else{
		$row[] = _l($aRow['action_value']);
	}
	
	if($aRow['agent_manager'] != null && new_strlen($aRow['agent_manager']) != 0){
		$row[] = get_staff_full_name($aRow['agent_manager']);
	}else{
		$row[] = $aRow['agent_manager'];
	}
	$row[] = $aRow['order_number'];

	$options = '';
	if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
		$options .= icon_btn('#', 'fa-regular fa-pen-to-square', 'btn-default', [
			'onclick'    => 'sla_warning_modal('.$aRow['service_level_agreement_id'].',' . $aRow['id'] . ', \'updated\'); return false;',
		]);
	}


	if((has_permission('customer_service', '', 'edit') || has_permission('customer_service', '', 'create') || is_admin())){
		$options .= icon_btn('customer_service/delete_sla_warning/' . $aRow['id'].'/'.$aRow['service_level_agreement_id'], 'fa fa-remove', 'btn-danger _delete', ['data-original-title' => _l('delete'), 'data-toggle' => 'tooltip', 'data-placement' => 'top']);
	}

	$row[] = $options;


	$output['aaData'][] = $row;
}

