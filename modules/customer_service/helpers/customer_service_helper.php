<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * cs date of week
 * @return [type] 
 */
function cs_date_of_week()
{
	$day_of_week=[];
	$day_of_week[] = [
		'name' => 0,
		'label' => _l('monday'),
	] ;
	$day_of_week[] = [
		'name' => 1,
		'label' => _l('tuesday'),
	] ;
	$day_of_week[] = [
		'name' => 2,
		'label' => _l('wednesday'),
	] ;
	$day_of_week[] = [
		'name' => 3,
		'label' => _l('thursday'),
	] ;
	$day_of_week[] = [
		'name' => 4,
		'label' => _l('friday'),
	] ;
	$day_of_week[] = [
		'name' => 5,
		'label' => _l('saturday'),
	] ;
	$day_of_week[] = [
		'name' => 6,
		'label' => _l('sunday'),
	] ;
	return $day_of_week;
}

/**
 * cs priority
 * @return [type] 
 */
function cs_priority()
{
	$priorities = [

		[
			'id'             => 'low',
			'color'          => '#9e9e9e',
			'name'           => _l('cs_low'),
			'order'          => 1,
			'filter_default' => true,
		],
		[
			'id'             => 'normal',
			'color'          => '#3db8da',
			'name'           => _l('cs_normal'),
			'order'          => 2,
			'filter_default' => true,
		],
		[
			'id'             => 'high',
			'color'          => '#2196f3',
			'name'           => _l('cs_high'),
			'order'          => 3,
			'filter_default' => true,
		],
		[
			'id'             => 'critical',
			'color'          => '#84c529',
			'name'           => _l('cs_critical'),
			'order'          => 4,
			'filter_default' => false,
		],
	];

	usort($priorities, function ($a, $b) {
		return $a['order'] - $b['order'];
	});

	return $priorities;
}

/**
 * cs ticket type
 * @return [type] 
 */
function cs_ticket_type()
{
	$ticket_types = [

		[
			'id'             => 'alert',
			'color'          => '#9e9e9e',
			'name'           => _l('alert'),
			'order'          => 1,
			'filter_default' => true,
		],
		[
			'id'             => 'order',
			'color'          => '#3db8da',
			'name'           => _l('cs_order'),
			'order'          => 2,
			'filter_default' => true,
		],
		[
			'id'             => 'problem',
			'color'          => '#2196f3',
			'name'           => _l('problem'),
			'order'          => 3,
			'filter_default' => true,
		],
	];

	usort($ticket_types, function ($a, $b) {
		return $a['order'] - $b['order'];
	});

	return $ticket_types;
}

/**
 * cs ticket status
 * @return [type] 
 */
function cs_ticket_status()
{
	$ticket_status = [

		[
			'id'             => 'open',
			'color'          => '#9e9e9e',
			'name'           => _l('cs_open'),
			'order'          => 1,
			'filter_default' => true,
		],
		[
			'id'             => 'inprogress',
			'color'          => '#3db8da',
			'name'           => _l('cs_inprogress'),
			'order'          => 2,
			'filter_default' => true,
		],
		[
			'id'             => 'answered',
			'color'          => '#2196f3',
			'name'           => _l('cs_answered'),
			'order'          => 3,
			'filter_default' => true,
		],
		[
			'id'             => 'on_hold',
			'color'          => '#84c529',
			'name'           => _l('cs_on_hold'),
			'order'          => 4,
			'filter_default' => false,
		],
		[
			'id'             => 'closed',
			'color'          => '#84c599',
			'name'           => _l('cs_closed'),
			'order'          => 5,
			'filter_default' => false,
		],
	];

	usort($ticket_status, function ($a, $b) {
		return $a['order'] - $b['order'];
	});

	return $ticket_status;
}


/**
 * cs get department name
 * @param  [type] $departmentid 
 * @return [type]               
 */
function cs_get_department_name($departmentid)
{
	$department_name = '';
	if(is_numeric($departmentid) && $departmentid != 0){
		$CI = &get_instance();
		$department = $CI->db->query('select '.db_prefix().'departments.name from '.db_prefix().'departments where departmentid = '.$departmentid)->row();
		if($department){
			$department_name = $department->name;
		}
	}
	return $department_name;
}

/**
 * cs get category name
 * @param  [type] $category_id 
 * @return [type]              
 */
function cs_get_category_name($category_id)
{
	$category_name = '';
	$CI = &get_instance();
	$category = $CI->db->query('select '.db_prefix().'cs_ticket_categories.category_name from '.db_prefix().'cs_ticket_categories where id = '.$category_id)->row();
	if($category){
		$category_name = $category->category_name;
	}
	return $category_name;
}

/**
 * cs get sla name
 * @param  [type] $sla_id 
 * @return [type]         
 */
function cs_get_sla_name($sla_id)
{
	$sla_name = '';
	$CI = &get_instance();
	if(is_numeric($sla_id)){
		$sla = $CI->db->query('select * from '.db_prefix().'cs_service_level_agreements where id = '.$sla_id)->row();
		if($sla){
			$sla_name = $sla->code.' '.$sla->name;
		}
	}
	return $sla_name;
}

/**
 * get customer status by_id
 * @param  [type] $id   
 * @param  [type] $type 
 * @return [type]       
 */
function get_customer_status_by_id($id, $type)
{
	$CI       = &get_instance();

	if($type == 'priority'){
		$statuses = cs_priority();
		$status = [
			'id'             => 'low',
			'color'          => '#9e9e9e',
			'name'           => _l('cs_low'),
			'order'          => 1,
			'filter_default' => true,
		];
	}elseif($type == 'ticket_type'){
		$statuses = cs_ticket_type();
		$status = [
			'id'             => 'alert',
			'color'          => '#9e9e9e',
			'name'           => _l('alert'),
			'order'          => 1,
			'filter_default' => true,
		];
	}elseif($type == 'stage_status'){
		$statuses = cs_stage_status();
		$status = [
			'id'             => 'alert',
			'color'          => '#9e9e9e',
			'name'           => _l('cs_not_started'),
			'order'          => 1,
			'filter_default' => true,
		];

	}else{
		// ticket_status
		$statuses = cs_ticket_status();
		$status = [
			'id'             => 'open',
			'color'          => '#9e9e9e',
			'name'           => _l('cs_open'),
			'order'          => 1,
			'filter_default' => true,
		];
	}

	foreach ($statuses as $s) {
		if ($s['id'] == $id) {
			$status = $s;

			break;
		}
	}

	return $status;
}


/**
 * render customer status html
 * @param  [type]  $id           
 * @param  [type]  $type         
 * @param  string  $status_value 
 * @param  boolean $ChangeStatus 
 * @return [type]                
 */
function render_customer_status_html($id, $type, $status_value = '', $ChangeStatus = true)
{
	$status          = get_customer_status_by_id($status_value, $type);

	if($type == 'priority'){
		$task_statuses = cs_priority();
	}elseif($type == 'ticket_type'){
		$task_statuses = cs_ticket_type();
	}elseif($type == 'ticket_status'){
		$task_statuses = cs_ticket_status();
	}elseif($type == 'stage_status'){
		$task_statuses = cs_stage_status();
	}

	$outputStatus    = '';

	$outputStatus .= '<span class="inline-block label" style="color:' . $status['color'] . ';border:1px solid ' . $status['color'] . '" task-status-table="' . $status_value . '">';
	$outputStatus .= $status['name'];
	$canChangeStatus = (has_permission('customer_service', '', 'edit') || is_admin());

	if ($canChangeStatus && $ChangeStatus) {
		$outputStatus .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
		$outputStatus .= '<a href="#" class="dropdown-toggle text-dark dropdown-font-size" id="tableTaskStatus-' . $id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
		$outputStatus .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
		$outputStatus .= '</a>';

		$outputStatus .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tableTaskStatus-' . $id . '">';
		foreach ($task_statuses as $taskChangeStatus) {
			if ($status_value != $taskChangeStatus['id']) {
				$outputStatus .= '<li>
				<a href="#" onclick="customer_service_status_mark_as(\'' . $taskChangeStatus['id'] . '\',' . $id . ',\'' . $type . '\'); return false;">
				' . _l('task_mark_as', $taskChangeStatus['name']) . '
				</a>
				</li>';
			}
		}
		$outputStatus .= '</ul>';
		$outputStatus .= '</div>';
	}

	$outputStatus .= '</span>';

	return $outputStatus;
}

/**
 * cs stage status
 * @return [type] 
 */
function cs_stage_status()
{
	$ticket_status = [

		[
			'id'             => 'not_started',
			'color'          => '#9e9e9e',
			'name'           => _l('cs_not_started'),
			'order'          => 1,
			'filter_default' => true,
		],
		[
			'id'             => 'inprogress',
			'color'          => '#3db8da',
			'name'           => _l('cs_inprogress'),
			'order'          => 2,
			'filter_default' => true,
		],
		[
			'id'             => 'waiting_reply_from_customer',
			'color'          => '#2196f3',
			'name'           => _l('cs_waiting_reply_from_customer'),
			'order'          => 3,
			'filter_default' => true,
		],
		[
			'id'             => 'resolved',
			'color'          => '#84c529',
			'name'           => _l('cs_resolved'),
			'order'          => 4,
			'filter_default' => false,
		],
		[
			'id'             => 'closed',
			'color'          => '#84c599',
			'name'           => _l('cs_closed'),
			'order'          => 5,
			'filter_default' => false,
		],
	];

	usort($ticket_status, function ($a, $b) {
		return $a['order'] - $b['order'];
	});

	return $ticket_status;
}

/**
 * cs get staff emai
 * @param  [type] $staff_id
 * @return [type]          
 */
function cs_get_staff_email($staff_id)
{
	$email = '';
	$CI = & get_instance();
	$CI->db->where('staffid', $staff_id);
	$staff = $CI->db->select('email')->from(db_prefix() . 'staff')->get()->row();
	if($staff){
		$email = $staff->email;
	}

	return $email;
}

/**
 * handle post reply attachments
 * @param  [type] $id 
 * @return [type]     
 */
function handle_post_reply_attachments($id)
{

	if (isset($_FILES['file']) && _perfex_upload_error($_FILES['file']['error'])) {
		header('HTTP/1.0 400 Bad error');
		echo _perfex_upload_error($_FILES['file']['error']);
		die;
	}
	$path = CUSTOMER_SERVICE_UPLOAD . $id . '/';
	$CI   = & get_instance();

	if (isset($_FILES['file']['name'])) {

        // 
        // Get the temp file path
		$tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
		if (!empty($tmpFilePath) && $tmpFilePath != '') {

			_maybe_create_upload_path($path);
			$filename    = $_FILES['file']['name'];
			$newFilePath = $path . $filename;
            // Upload the file into the temp dir
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {

				$attachment   = [];
				$attachment[] = [
					'file_name' => $filename,
					'filetype'  => $_FILES['file']['type'],
				];

				$CI->misc_model->add_attachment_to_database($id, 'cs_post_reply', $attachment);
			}
		}
	}

}

/**
 * cs get status modules
 * @param  [type] $module_name 
 * @return [type]              
 */
function cs_get_status_modules($module_name){
	$CI             = &get_instance();

	$sql = 'select * from '.db_prefix().'modules where module_name = "'.$module_name.'" AND active =1 ';
	$module = $CI->db->query($sql)->row();
	if($module){
		return true;
	}else{
		return false;
	}
}

/**
 * cs get item variatiom
 * @param  [type] $id 
 * @return [type]     
 */
function cs_get_item_variatiom($id)
{
	$CI           = & get_instance();

	$CI->db->where('id', $id);
	$item_value = $CI->db->get(db_prefix() . 'items')->row();

	$name = '';
	if($item_value){
		if(cs_get_status_modules('warehouse')){

			$CI->load->model('warehouse/warehouse_model');
			$new_item_value = $CI->warehouse_model->row_item_to_variation($item_value);

			$name .= $item_value->commodity_code.'_'.$new_item_value->new_description;
		}else{
			$name .= $item_value->description;
		}
    }

    return $name;
}

/**
 * cs waits
 * @return [type] 
 */
function cs_waits()
{
	$waits = [

		[
			'id'             => 'days',
			'color'          => '#9e9e9e',
			'name'           => _l('cs_days'),
			'order'          => 1,
			'filter_default' => true,
		],
		[
			'id'             => 'hours',
			'color'          => '#3db8da',
			'name'           => _l('cs_hours'),
			'order'          => 2,
			'filter_default' => true,
		],
		[
			'id'             => 'minutes',
			'color'          => '#3db8da',
			'name'           => _l('cs_minutes'),
			'order'          => 2,
			'filter_default' => true,
		],
		
	];

	usort($waits, function ($a, $b) {
		return $a['order'] - $b['order'];
	});

	return $waits;
}

/**
 * cs get contact email
 * @param  [type] $type 
 * @param  [type] $id   
 * @return [type]       
 */
function cs_get_contact_email($type, $id){
	$email = '';
	$CI           = & get_instance();

	if($type == 'contact'){
		$CI->db->where('id', $id);
		$primary = $CI->db->get(db_prefix() . 'contacts')->row();
		if($primary ){
			$email = $primary->email;
		}

	}else{
		// type client
		$CI->db->where('userid', $id)
		->where('is_primary', 1);

		$primary = $CI->db->get(db_prefix() . 'contacts')->row();
		if($primary ){
			$email = $primary->email;
		}
	}

	return $email;
}

/**
 * cs get department email
 * @param  [type] $departmentid 
 * @return [type]               
 */
function cs_get_department_email($departmentid){
	$email = '';
	$CI           = & get_instance();

	$CI->db->select(db_prefix() . 'departments.email')
	->where('departmentid', $departmentid);

	$department = $CI->db->get(db_prefix() . 'departments')->row();
	if($department ){
		$email = $department->email;
	}
	return $email;
	
}

/**
 * cs_get_ticket_code
 * @param  [type] $ticket_id 
 * @return [type]            
 */
function cs_get_ticket_code($ticket_id)
{
	$code = '';
	$CI = & get_instance();
	$CI->db->where('id', $ticket_id);
	$ticket = $CI->db->get(db_prefix() . 'cs_tickets')->row();
	if($ticket){
		$code = $ticket->code;
	}

	return $code;
}

/**
 * cs get invoice hash
 * @param  [type] $id 
 * @return [type]     
 */
function cs_get_invoice_hash($id)
{
	$hash = '';
	$CI           = & get_instance();
	$CI->db->where('id',$id);

	$invoices = $CI->db->get(db_prefix().'invoices')->row();
	if($invoices){
		$hash = $invoices->hash;
	}
	return $hash;
}

/**
 * cs get kpi name
 * @param  [type] $kpi_id 
 * @return [type]         
 */
function cs_get_kpi_name($kpi_id)
{
	$kpi_name = '';
	$CI = &get_instance();
	if(is_numeric($kpi_id)){
		$sla = $CI->db->query('select * from '.db_prefix().'cs_kpis where id = '.$kpi_id)->row();
		if($sla){
			$kpi_name = $sla->code.' '.$sla->name;
		}
	}
	return $kpi_name;
}

if (!function_exists('new_html_entity_decode')) {
	
	function new_html_entity_decode($str){
		return html_entity_decode($str ?? '');
	}
}

if (!function_exists('new_strlen')) {
	
	function new_strlen($str){
		return strlen($str ?? '');
	}
}

if (!function_exists('new_str_replace')) {
	
	function new_str_replace($search, $replace, $subject){
		return str_replace($search, $replace, $subject ?? '');
	}
}

if (!function_exists('new_explode')) {

	function new_explode($delimiter, $string){
		return explode($delimiter, $string ?? '');
	}
}