<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Class Customer service
 */
class Customer_service extends AdminController
{
	/**
	 * __construct
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('customer_service_model');
		hooks()->do_action('customer_service_init');

	}

	/*write your function on here*/

	public function setting()
	{
		if (!has_permission('customer_service', '', 'edit') && !is_admin() && !has_permission('customer_service', '', 'create')) {
			access_denied('customer_service');
		}

		$data['group'] = $this->input->get('group');
		$data['title'] = _l('setting');

		$data['tab'][] = 'general';
		$data['tab'][] = 'mail_scan_rule';
		$data['tab'][] = 'email_template';
		$data['tab'][] = 'support_term_condition';
		$data['tab'][] = 'prefix_number';

		if ($data['group'] == '' || $data['group'] == 'general') {
			$this->load->model('departments_model');
			$data['tabs']['view'] = 'settings/general/' . $data['group'];
			$data['departments'] = $this->departments_model->get();
		}elseif($data['group'] == 'kpi'){
			$data['tabs']['view'] = 'settings/kpis/kpi';
		}elseif($data['group'] == 'prefix_number'){
			$data['tabs']['view'] = 'settings/prefixs/prefix_number';
		}elseif($data['group'] == 'mail_scan_rule'){
			$data['title'] = _l('cs_mail_scan_rules');
			$data['mail_scan_rules'] = $this->customer_service_model->get_mail_scan_rule();
			$data['tabs']['view'] = 'settings/mail_scan_rules/mail_scan_rule';
			
		}elseif($data['group'] == 'sla'){
			$data['title'] = _l('cs_slas');
			$data['tabs']['view'] = 'settings/slas/sla_manage';
		}elseif($data['group'] == 'category'){
			$data['title'] = _l('cs_categories');
			$data['tabs']['view'] = 'settings/categories/category_manage';
		}elseif($data['group'] == 'email_template'){
			$data['title'] = _l('cs_email_templates');
			$data['tabs']['view'] = 'settings/email_templates/manage';
		}elseif($data['group'] == 'support_term_condition'){
			$data['title'] = _l('cs_support_term_condition');
			$data['tabs']['view'] = 'settings/support_term_conditions/support_term_condition';
		}

		$this->load->view('settings/manage_setting', $data);
	}

	/**
	 * blocked sender table
	 * @return [type] 
	 */
	public function blocked_sender_table()
	{
		$this->app->get_table_data(module_views_path('customer_service', 'settings/mail_scan_rules/mail_scan_rule_table'));
	}

	/**
	 * change mail scan rule status
	 * @param  [type] $id     
	 * @param  [type] $status 
	 * @return [type]         
	 */
	public function change_mail_scan_rule_status($id, $status) {
		if (has_permission('customer_service', '', 'edit')) {
			if ($this->input->is_ajax_request()) {
				if($status == 0){
					$status = 'disabled';
				}else{
					$status = 'enabled';
				}

				$this->customer_service_model->change_mail_scan_rule_status($id, $status);
			}
		}
	}

	/**
	 * mail scan rule
	 * @return [type] 
	 */
	public function mail_scan_rule()
	{
		$message = '';
		$success = false;
		if ($this->input->post()) {
			if ($this->input->post('id')) {
				$id = $this->input->post('id');
				$data = $this->input->post();
				if(isset($data['id'])){
					unset($data['id']);
				}

				$success = $this->customer_service_model->update_mail_scan_rule($data, $id);
				if ($success == true) {
					$message = _l('updated_successfully', _l('spam_filter'));
				}
			} else {
				$success = $this->customer_service_model->add_mail_scan_rule($this->input->post());
				if ($success == true) {
					$message = _l('added_successfully', _l('spam_filter'));
				}
			}
		}
		echo json_encode([
			'success' => $success,
			'message' => $message,
		]);
	}

	/**
	 * delete mail scan rule
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_mail_scan_rule($id)
	{
		if (!$id) {
			redirect(admin_url('customer_service/setting?group=mail_scan_rule'));
		}

		if(!has_permission('customer_service', '', 'delete')  &&  !is_admin()) {
			access_denied('customer_service');
		}

		$response = $this->customer_service_model->delete_mail_scan_rule($id);
		if ($response) {
			set_alert('success', _l('deleted'));
			redirect(admin_url('customer_service/setting?group=mail_scan_rule'));
		} else {
			set_alert('warning', _l('problem_deleting'));
			redirect(admin_url('customer_service/setting?group=mail_scan_rule'));
		}

	}

	/**
	 * cs check box setting
	 * @return [type] 
	 */
	public function cs_check_box_setting()
	{
		$data = $this->input->post();

		if (!has_permission('customer_service', '', 'edit') && !is_admin()) {
			$success = false;
			$message = _l('Not permission edit');

			echo json_encode([
				'message' => $message,
				'success' => $success,
			]);
			die;
		}

		if($data != 'null'){
			$value = $this->customer_service_model->change_setting_with_checkbox($data);
			if($value){
				$success = true;
				$message = _l('updated_successfully');
			}else{
				$success = false;
				$message = _l('updated_false');
			}
			echo json_encode([
				'message' => $message,
				'success' => $success,
			]);
			die;
		}
	}

	/**
	 * prefix number
	 * @return [type] 
	 */
	public function prefix_number()
	{
		if (!has_permission('customer_service', '', 'edit') && !is_admin() && !has_permission('customer_service', '', 'create')) {
			access_denied('customer_service');
		}

		$data = $this->input->post();

		if ($data) {

			$success = $this->customer_service_model->update_prefix_number($data);

			if ($success == true) {

				$message = _l('wm_updated_successfully');
				set_alert('success', $message);
			}

			redirect(admin_url('customer_service/setting?group=prefix_number'));
		}
	}

	/**
	 * kpi table
	 * @return [type] 
	 */
	public function kpi_table()
	{
		$this->app->get_table_data(module_views_path('customer_service', 'settings/kpis/kpi_table'));
	}

	/**
	 * kpi
	 * @return [type] 
	 */
	public function kpi()
	{
		$message = '';
		$success = false;
		if ($this->input->post()) {
			if ($this->input->post('id')) {
				$id = $this->input->post('id');
				$data = $this->input->post();
				if(isset($data['id'])){
					unset($data['id']);
				}

				$success = $this->customer_service_model->update_kpi($data, $id);
				if ($success == true) {
					$message = _l('updated_successfully');
				}
			} else {
				$success = $this->customer_service_model->add_kpi($this->input->post());
				if ($success == true) {
					$message = _l('added_successfully');
				}
			}
		}
		echo json_encode([
			'success' => $success,
			'message' => $message,
		]);
	}

	/**
	 * delete kpi
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_kpi($id)
	{
		if (!$id) {
			redirect(admin_url('customer_service/kpi_manage'));
		}

		if(!has_permission('customer_service', '', 'delete')  &&  !is_admin()) {
			access_denied('customer_service');
		}

		$response = $this->customer_service_model->delete_kpi($id);
		if ($response) {
			set_alert('success', _l('deleted'));
			redirect(admin_url('customer_service/kpi_manage'));
		} else {
			set_alert('warning', _l('problem_deleting'));
			redirect(admin_url('customer_service/kpi_manage'));
		}

	}

	/**
	 * get prefix code
	 * @param  [type] $type 
	 * @return [type]       
	 */
	public function get_prefix_code($type)
	{
		$prefix_code = $this->customer_service_model->create_code($type);

		echo json_encode([
			'prefix_code' => $prefix_code,
		]);
	}

	/**
	 * change kpi status
	 * @param  [type] $id     
	 * @param  [type] $status 
	 * @return [type]         
	 */
	public function change_kpi_status($id, $status) {
		if (has_permission('customer_service', '', 'edit')) {
			if ($this->input->is_ajax_request()) {
				if($status == 0){
					$status = 'disabled';
				}else{
					$status = 'enabled';
				}

				$this->customer_service_model->change_kpi_status($id, $status);
			}
		}
	}

	/**
	 * general
	 * @return [type] 
	 */
	public function general()
	{
		if (!has_permission('customer_service', '', 'edit') && !is_admin() && !has_permission('customer_service', '', 'create')) {
			access_denied('customer_service');
		}

		$data = $this->input->post();
		if ($data) {
			if(isset($data['customer_service_business_days'])){
				$data['customer_service_business_days'] = implode(",", $data['customer_service_business_days']);
			}else{
				$data['customer_service_business_days'] = '';
			}
			if(isset($data['cs_mail_scan_from_departments'])){
				$data['cs_mail_scan_from_departments'] = implode(",", $data['cs_mail_scan_from_departments']);
			}else{
				$data['cs_mail_scan_from_departments'] = '';
			}
			

			$success = $this->customer_service_model->update_prefix_number($data);
			if ($success == true) {

				$message = _l('wm_updated_successfully');
				set_alert('success', $message);
			}
			redirect(admin_url('customer_service/setting?group=general'));
		}
	}

	/**
	 * sla table
	 * @return [type] 
	 */
	public function sla_table()
	{
			$this->app->get_table_data(module_views_path('customer_service', 'settings/slas/sla_table'));
	}

	/**
	 * sla modal
	 * @return [type] 
	 */
	public function sla_modal()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		$data=[];
		$data['code'] = $this->customer_service_model->create_code('sla_code');
		$data['staffs'] = $this->staff_model->get('', ['active' => 1]);

		$this->load->view('settings/slas/add_sla_modal', $data);
	}

	/**
	 * add sla modal
	 * @param string $id 
	 */
	public function add_sla_modal($id='')
	{

		if (!has_permission('customer_service', '', 'view')  && !is_admin()) {
			access_denied('sla');
		}
		
		if ($this->input->post()) {
			$data = $this->input->post();
			$data['admin_note']     = $this->input->post('admin_note', false);

			if ($id == '') {
				if (!has_permission('customer_service', '', 'create') && !is_admin()) {
					access_denied('cs_sla');
				}

				$id = $this->customer_service_model->add_sla($data);
				if ($id) {
					set_alert('success', _l('cs_added_successfully'));
					redirect(admin_url('customer_service/sla_warning_manage/'.$id));
				}

			} else {
				if (!has_permission('customer_service', '', 'edit') && !is_admin()) {
					access_denied('cs_sla');
				}

				$response = $this->customer_service_model->update_sla($data, $id);

				if (is_array($response)) {
					if (isset($response['cant_remove_main_admin'])) {
						set_alert('warning', _l('staff_cant_remove_main_admin'));
					} elseif (isset($response['cant_remove_yourself_from_admin'])) {
						set_alert('warning', _l('staff_cant_remove_yourself_from_admin'));
					}
				} elseif ($response == true) {
					set_alert('success', _l('cs_updated_successfully'));
				}
				redirect(admin_url('customer_service/sla_warning_manage/'.$id));
			}
		}

	}

	/**
	 * delete sla
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_sla($id)
	{
		if (!has_permission('customer_service', '', 'delete')  && !is_admin()) {
			access_denied('cs_sla');
		}

		$success = $this->customer_service_model->delete_sla($id);
		if ($success) {
			set_alert('success', _l('cs_deleted'));
		} else {
			set_alert('warning', _l('problem_deleting'));
		}
		redirect(admin_url('customer_service/sla_manage'));

	}

	/**
	 * sla warning manage
	 * @param  string $id 
	 * @return [type]     
	 */
	public function sla_warning_manage($id='')
	{
	    if (!has_permission('customer_service', '', 'view') ) {
			access_denied('cs_sla');
		}

		$data['title'] = _l('cs_sla');
		if($id != ''){
			$data['sla'] = $this->customer_service_model->get_sla($id);
		}
		$data['staffs'] = $this->staff_model->get('', ['active' => 1]);

		$this->load->view('settings/slas/sla_warnings/sla_warning_manage', $data);
	}

	/**
	 * sal warning table
	 * @return [type] 
	 */
	public function sla_warning_table()
	{
		$this->app->get_table_data(module_views_path('customer_service', 'settings/slas/sla_warnings/sla_warning_table'));
	}

	/**
	 * sla warning modal
	 * @return [type] 
	 */
	public function sla_warning_modal()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		$this->load->model('staff_model');
		$data=[];
		$data = $this->input->post();
		if($data['sla_warning_id'] != 0){
			$data['sla_warning'] = $this->customer_service_model->get_sla_warning($data['sla_warning_id']);
		}
		$data['get_order_number'] = $this->customer_service_model->get_max_sla_warning_order_number($data['service_level_agreement_id']);
		$data['staffs'] = $this->staff_model->get('', ['active' => 1]);

		$this->load->view('settings/slas/sla_warnings/add_edit_sla_warning_modal', $data);
	}

	/**
	 * add edit sla warning
	 * @param string $id 
	 */
	public function add_edit_sla_warning($id='')
	{
	    if (!has_permission('customer_service', '', 'view')  && !is_admin()) {
			access_denied('process');
		}
		
		if ($this->input->post()) {
			$data = $this->input->post();
			$service_level_agreement_id = $data['service_level_agreement_id'];

			if ($id == '') {
				if (!has_permission('customer_service', '', 'create') && !is_admin()) {
					access_denied('cs_sla_warning');
				}

				$id = $this->customer_service_model->add_sla_warning($data);
				if ($id) {
					set_alert('success', _l('cs_added_successfully'));
					redirect(admin_url('customer_service/sla_warning_manage/'.$service_level_agreement_id));
				}

			} else {
				if (!has_permission('customer_service', '', 'edit') && !is_admin()) {
					access_denied('cs_sla_warning');
				}

				$response = $this->customer_service_model->update_sla_warning($data, $id);

				if (is_array($response)) {
					if (isset($response['cant_remove_main_admin'])) {
						set_alert('warning', _l('staff_cant_remove_main_admin'));
					} elseif (isset($response['cant_remove_yourself_from_admin'])) {
						set_alert('warning', _l('staff_cant_remove_yourself_from_admin'));
					}
				} elseif ($response == true) {
					set_alert('success', _l('cs_updated_successfully'));
				}
				redirect(admin_url('customer_service/sla_warning_manage/'.$service_level_agreement_id));
			
			}
		}

	}

	/**
	 * delete sla warning
	 * @param  [type] $id                  
	 * @param  [type] $service_level_agreement_id 
	 * @return [type]                      
	 */
	public function delete_sla_warning($id, $service_level_agreement_id)
	{
	    if (!has_permission('customer_service', '', 'delete')  && !is_admin()) {
			access_denied('cs_sla_warning');
		}

		$success = $this->customer_service_model->delete_sla_warning($id);
		if ($success) {
			set_alert('success', _l('cs_deleted'));
		} else {
			set_alert('warning', _l('problem_deleting'));
		}
		redirect(admin_url('customer_service/sla_warning_manage/'.$service_level_agreement_id));


	}

	/**
	 * change sla status
	 * @param  [type] $id     
	 * @param  [type] $status 
	 * @return [type]         
	 */
	public function change_sla_status($id, $status)
	{
		if (has_permission('customer_service', '', 'edit')) {
			if ($this->input->is_ajax_request()) {
				if($status == 0){
					$status = 'disabled';
				}else{
					$status = 'enabled';
				}

				$this->customer_service_model->change_sla_status($id, $status);
			}
		}
	}

	/**
	 * category_table
	 * @return [type] 
	 */
	public function category_table()
	{
		$this->app->get_table_data(module_views_path('customer_service', 'settings/categories/category_table'));
	}

	/**
	 * category modal
	 * @return [type] 
	 */
	public function category_modal()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		$this->load->model('staff_model');
		$data=[];
		$data = $this->input->post();
		$this->load->model('departments_model');
		$data['departments'] = $this->departments_model->get();

		if($data['category_id'] != 0){
			$data['category'] = $this->customer_service_model->get_category($data['category_id']);
			$data['workflows'] = $this->customer_service_model->get_workflow_by_status("enabled", $data['category']->work_flow_id);
			$item_tags = $this->customer_service_model->get_list_category_tags($data['category_id']);
			$data['item_tags'] = $item_tags['htmltag'];

		}else{
			$data['workflows'] = $this->customer_service_model->get_workflow_by_status("enabled");
			$data['item_tags'] = $this->customer_service_model->get_category_tag_filter();
		}

		$data['category_code'] = $this->customer_service_model->create_code('ticket_category_code');
		$data['slas'] = $this->customer_service_model->get_sla(false, 'enabled');


		$this->load->view('settings/categories/add_edit_category_modal', $data);
	}

	/**
	 *[add edit category
	 * @param string $id 
	 */
	public function add_edit_category($id='')
	{
	    if (!has_permission('customer_service', '', 'view')  && !is_admin()) {
			access_denied('process');
		}
		
		if ($this->input->post()) {
			$data = $this->input->post();

			if ($id == '') {
				if (!has_permission('customer_service', '', 'create') && !is_admin()) {
					access_denied('cs_sla_warning');
				}

				$id = $this->customer_service_model->add_category($data);
				if ($id) {
					set_alert('success', _l('cs_added_successfully'));
					redirect(admin_url('customer_service/category_manage'));
				}

			} else {
				if (!has_permission('customer_service', '', 'edit') && !is_admin()) {
					access_denied('cs_sla_warning');
				}

				$response = $this->customer_service_model->update_category($data, $id);

				if (is_array($response)) {
					if (isset($response['cant_remove_main_admin'])) {
						set_alert('warning', _l('staff_cant_remove_main_admin'));
					} elseif (isset($response['cant_remove_yourself_from_admin'])) {
						set_alert('warning', _l('staff_cant_remove_yourself_from_admin'));
					}
				} elseif ($response == true) {
					set_alert('success', _l('cs_updated_successfully'));
				}
				redirect(admin_url('customer_service/category_manage'));

			}
		}

	}

	/**
	 * delete category
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_category($id)
	{
	    if (!has_permission('customer_service', '', 'delete')  && !is_admin()) {
			access_denied('cs_category');
		}

		$success = $this->customer_service_model->delete_category($id);
		if ($success) {
			set_alert('success', _l('cs_deleted'));
		} else {
			set_alert('warning', _l('problem_deleting'));
		}
		redirect(admin_url('customer_service/category_manage'));

	}

	/**
	 * change category status
	 * @param  [type] $id     
	 * @param  [type] $status 
	 * @return [type]         
	 */
	public function change_category_status($id, $status)
	{
		if (has_permission('customer_service', '', 'edit')) {
			if ($this->input->is_ajax_request()) {
				if($status == 0){
					$status = 'disabled';
				}else{
					$status = 'enabled';
				}

				$this->customer_service_model->change_category_status($id, $status);
			}
		}
	}

	/**
	 * change category default
	 * @param  [type] $id     
	 * @param  [type] $status 
	 * @return [type]         
	 */
	public function change_category_default($id, $status)
	{
		if (has_permission('customer_service', '', 'edit')) {
			if ($this->input->is_ajax_request()) {
				
				$this->customer_service_model->change_category_default($id, $status);
			}
		}
	}

	/**
	 * work flows
	 * @return [type] 
	 */
	public function work_flows()
	{
		if (!has_permission('customer_service', '', 'edit') && !is_admin() && !has_permission('customer_service', '', 'create')) {
			access_denied('customer_service');
		}
		$data = [];
		$this->load->view('work_flows/work_flow_manage', $data);
	}

	/**
	 * work flow table
	 * @return [type] 
	 */
	public function work_flow_table()
	{
		$this->app->get_table_data(module_views_path('customer_service', 'work_flows/work_flow_table'));
	}

	/**
	 * add edit work flow
	 * @param string $id 
	 */
	public function add_edit_work_flow($id='')
	{
	    if (!has_permission('customer_service', '', 'view')  && !is_admin()) {
			access_denied('process');
		}
		
		if ($this->input->post()) {
			$data = $this->input->post();

			if ($id == '') {
				if (!has_permission('customer_service', '', 'create') && !is_admin()) {
					access_denied('cs_sla_warning');
				}

				$id = $this->customer_service_model->add_workflow($data);
				if ($id) {
					set_alert('success', _l('cs_added_successfully'));
					redirect(admin_url('customer_service/add_edit_work_flow/'.$id));
				}

			} else {
				if (!has_permission('customer_service', '', 'edit') && !is_admin()) {
					access_denied('cs_sla_warning');
				}
				if($this->input->post('workflow') != null ){
					$data['workflow'] = $this->input->post('workflow', false);
				}

				$response = $this->customer_service_model->update_workflow($data, $id);

				if (is_array($response)) {
					if (isset($response['cant_remove_main_admin'])) {
						set_alert('warning', _l('staff_cant_remove_main_admin'));
					} elseif (isset($response['cant_remove_yourself_from_admin'])) {
						set_alert('warning', _l('staff_cant_remove_yourself_from_admin'));
					}
				} elseif ($response == true) {
					set_alert('success', _l('cs_updated_successfully'));
				}
				redirect(admin_url('customer_service/work_flows'));
			
			}
		}

		if(is_numeric($id)){
			$data['is_edit'] = false;
			$data['title'] = _l('cs_add_work_flow');
			$data['workflow'] = $this->customer_service_model->get_workflow($id);
		}else{
			$data['is_edit'] = true;
			$data['title'] = _l('cs_edit_work_flow');
		}
		$data['id'] = $id;

        $this->load->view('work_flows/add_edit_work_flow', $data);
	}

	/**
	 * delete workflow
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_workflow($id)
	{
	    if (!has_permission('customer_service', '', 'delete')  && !is_admin()) {
			access_denied('cs_sla_warning');
		}

		$success = $this->customer_service_model->delete_workflow($id);
		if ($success) {
			set_alert('success', _l('cs_deleted'));
		} else {
			set_alert('warning', _l('problem_deleting'));
		}
		redirect(admin_url('customer_service/work_flows'));
	}

	/**
	 * change workflow
	 * @param  [type] $id     
	 * @param  [type] $status 
	 * @return [type]         
	 */
	public function change_workflow_status($id, $status)
	{
		if (has_permission('customer_service', '', 'edit')) {
			if ($this->input->is_ajax_request()) {
				if($status == 0){
					$status = 'disabled';
				}else{
					$status = 'enabled';
				}

				$this->customer_service_model->change_workflow_status($id, $status);
			}
		}
	}


	/**
	 * workflow modal
	 * @return [type] 
	 */
	public function workflow_modal()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}

		$data_post = $this->input->post();
		$data=[];
		$data['ex_code'] = $this->customer_service_model->create_code('workflow_code');
		if($data_post['slug'] == 'add'){
		}else{
			$data['workflow'] = $this->customer_service_model->get_workflow($data_post['work_flow_id']);
		}
		$data['slas'] = $this->customer_service_model->get_sla(false, 'enabled');
		$data['kpis'] = $this->customer_service_model->get_kpi(false, true);

		$this->load->view('work_flows/work_flow_modal', $data);
	}

	/**
	 * get workflow node html
	 * @return [type] 
	 */
	public function get_workflow_node_html()
	{
		$data = $this->input->post();

		switch ($data['type']) {
			case 'flow_start':
			break;
			case 'sms':
			$data['sms'] = $this->ma_model->get_sms();
			break;
			case 'email':
			$data['emails'] = $this->ma_model->get_email();
			break;
			case 'action':
			$data['segments'] = [];
			$data['stages'] = [];
			$data['point_actions'] = [];
			
			break;

			case 'stage':
			$this->load->model('departments_model');
			$this->load->model('staff_model');
			$data['departments'] = $this->departments_model->get();
			$data['slas'] = $this->customer_service_model->get_sla();
			$data['staffs'] = $this->staff_model->get('', ['active' => 1]);
			break;

			case 'ticket_status':
			$data['ticket_status'] = cs_ticket_status();
			break;

			case 'stage_status':
			$data['stage_status'] = cs_stage_status();
			break;
			
			case 'ticket_priority':
			$data['ticket_priority'] = cs_priority();
			break;
			
			case 'ticket_type':
			$data['ticket_type'] = cs_ticket_type();
			break;
			
			case 'email_user':
			$data['staffs'] = [];
			$other_user_type  = [];

			$other_user_type[] = [
				'staffid' => 'requester',
				'firstname' => _l('requester'),
				'lastname' => '',
			];
			$other_user_type[] = [
				'staffid' => 'ticket_assigned_user',
				'firstname' => _l('cs_ticket_assigned_user'),
				'lastname' => '',
			];
			$staffs = $this->staff_model->get('', ['active' => 1]);

			$data['staffs'] = array_merge($other_user_type, $staffs);
			$data['email_templates'] = $this->customer_service_model->get_email_template_slug();

			break;
			
			case 'email_group':
			$data['email_groups'] = [];
			$data['email_groups'][] = [
				'name' => 'ticket_assigness_department',
				'label' => _l('ticket_assigness_department'),
			];

			$data['email_templates'] = $this->customer_service_model->get_email_template_slug();

			break;
			
			case 'assignee':
			$this->load->model('departments_model');
			$this->load->model('staff_model');
			$data['departments'] = $this->departments_model->get();
			$data['staffs'] = $this->staff_model->get('', ['active' => 1]);
			break;
			
			
			default:
                // code...
			break;
		}

		$this->load->view('work_flows/workflow_node/'.$data['type'], $data);
	}

	/**
	 * ticket pipe logs
	 * @return [type] 
	 */
	public function ticket_pipe_logs()
	{
		if (!has_permission('customer_service', '', 'edit') && !is_admin() && !has_permission('customer_service', '', 'create')) {
			access_denied('customer_service');
		}
		$data = [];
		$this->load->view('ticket_pipe_logs/ticket_pipe_log_manage', $data);
	}

	/**
	 * ticket pipe logs table
	 * @return [type] 
	 */
	public function ticket_pipe_log_table()
	{
		$this->app->get_table_data(module_views_path('customer_service', 'ticket_pipe_logs/ticket_pipe_log_table'));
	}
	
	/**
	 * delete tickets pipe log
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_tickets_pipe_log($id)
	{
		if (!has_permission('customer_service', '', 'delete')  && !is_admin()) {
			access_denied('cs_category');
		}

		$success = $this->customer_service_model->delete_tickets_pipe_log($id);
		if ($success) {
			set_alert('success', _l('cs_deleted'));
		} else {
			set_alert('warning', _l('problem_deleting'));
		}
		redirect(admin_url('customer_service/ticket_pipe_logs'));

	}

	/**
	 * sla manage
	 * @return [type] 
	 */
	public function sla_manage()
	{
		if (!has_permission('customer_service', '', 'edit') && !is_admin() && !has_permission('customer_service', '', 'create')) {
			access_denied('customer_service');
		}
		$data = [];
		$this->load->view('settings/slas/sla_manage', $data);
	}

	/**
	 * kpi manage
	 * @return [type] 
	 */
	public function kpi_manage()
	{
		if (!has_permission('customer_service', '', 'edit') && !is_admin() && !has_permission('customer_service', '', 'create')) {
			access_denied('customer_service');
		}
		$data = [];
		$this->load->view('settings/kpis/kpi', $data);
	}

	/**
	 * category manage
	 * @return [type] 
	 */
	public function category_manage()
	{
		if (!has_permission('customer_service', '', 'edit') && !is_admin() && !has_permission('customer_service', '', 'create')) {
			access_denied('customer_service');
		}
		$data = [];

		$this->load->view('settings/categories/category_manage', $data);
	}

	/**
	 * tickets
	 * @return [type] 
	 */
	public function tickets()
	{
		if (!has_permission('customer_service', '', 'edit') && !is_admin() && !has_permission('customer_service', '', 'create')) {
			access_denied('customer_service');
		}
		$data = [];
		$data['categories'] = $this->customer_service_model->get_category();
		$data['clients'] = $this->clients_model->get();

		$this->load->view('tickets/ticket_manage', $data);
	}

	/**
	 * ticket table
	 * @return [type] 
	 */
	public function ticket_table()
	{
		$this->app->get_table_data(module_views_path('customer_service', 'tickets/ticket_table'));
	}

	/**
	 * add edit ticket
	 * @param string $id 
	 */
	public function add_edit_ticket($id='')
	{
	    if (!has_permission('customer_service', '', 'view')  && !is_admin()) {
			access_denied('process');
		}
		
		if ($this->input->post()) {
			$data = $this->input->post();

			if ($id == '') {
				if (!has_permission('customer_service', '', 'create') && !is_admin()) {
					access_denied('cs_sla_warning');
				}

				$id = $this->customer_service_model->add_ticket($data);
				if ($id) {
					set_alert('success', _l('cs_added_successfully'));
					redirect(admin_url('customer_service/tickets'));
				}

			} else {
				if (!has_permission('customer_service', '', 'edit') && !is_admin()) {
					access_denied('cs_sla_warning');
				}
				if(isset($data['id'])){
					unset($data['id']);
				}
				$response = $this->customer_service_model->update_ticket($data, $id);

				if (is_array($response)) {
					if (isset($response['cant_remove_main_admin'])) {
						set_alert('warning', _l('staff_cant_remove_main_admin'));
					} elseif (isset($response['cant_remove_yourself_from_admin'])) {
						set_alert('warning', _l('staff_cant_remove_yourself_from_admin'));
					}
				} elseif ($response == true) {
					set_alert('success', _l('cs_updated_successfully'));
				}
				redirect(admin_url('customer_service/tickets'));
			
			}
		}

		if(is_numeric($id)){
			$data['is_edit'] = false;
			$data['title'] = _l('cs_edit_ticket');
			$data['ticket'] = $this->customer_service_model->get_ticket($id);
			if(cs_get_status_modules('warranty_management')){
				$this->load->model('warranty_management/warranty_management_model');
				$invoices = $this->warranty_management_model->get_list_item_warranty($data['ticket']->client_id);
				$data['item_tickets'] = $this->warranty_management_model->get_list_item_warranty_by_invoice($data['ticket']->invoice_id);

				$arr_temp_invoice_ids = [];
				$arr_invoice = [];
				foreach ($invoices as $key => $value) {
					if(!in_array($value['invoice_id'], $arr_temp_invoice_ids)){

						$arr_invoice[] = [
							'id' => $value['invoice_id'],
						];
					}
					$arr_temp_invoice_ids[] = $value['invoice_id'];
				}
				$data['invoices'] = $arr_invoice;

			}else{
				$this->load->model('invoices_model');

				$data['invoices'] = $this->invoices_model->get(false, db_prefix().'invoices.clientid = '.$data['ticket']->client_id);
				$data['item_tickets'] = $this->customer_service_model->cs_get_commodity();
			}

		}else{
			$data['is_edit'] = true;
			$data['title'] = _l('cs_add_ticket');
			$data['item_tickets'] = $this->customer_service_model->cs_get_commodity();

		}
		$this->load->model('staff_model');
		$data['categories'] = $this->customer_service_model->get_category(false, 'enabled');
		$data['clients'] = $this->clients_model->get();
		$data['ticket_code'] = $this->customer_service_model->create_code('ticket_code');
		$data['staffs'] = $this->staff_model->get('', ['active' => 1]);
		$data['slas'] = $this->customer_service_model->get_sla();
		$data['id'] = $id;

		$this->load->model('departments_model');
		$data['departments'] = $this->departments_model->get();

        $this->load->view('tickets/add_edit_ticket', $data);
	}

	/**
	 * delete ticket
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_ticket($id)
	{
		if (!has_permission('customer_service', '', 'delete')  && !is_admin()) {
			access_denied('cs_sla_warning');
		}

		$success = $this->customer_service_model->delete_ticket($id);
		if ($success) {
			set_alert('success', _l('cs_deleted'));
		} else {
			set_alert('warning', _l('problem_deleting'));
		}
		redirect(admin_url('customer_service/tickets'));
	}

	/**
	 * get category info
	 * @param  string $category_id 
	 * @return [type]              
	 */
	public function get_category_info($category_id = '')
	{
		$category = $this->customer_service_model->get_category($category_id);

		$priority_level = '';
		$department_id = '';
		$sla_id = '';

		if($category){
			$priority_level = $category->priority;
			$department_id = $category->department_id;
			$sla_id = $category->sla_id;
		}

		echo json_encode([
			'priority_level' => $priority_level,
			'department_id' => $department_id,
			'sla_id' => $sla_id,
		]);
	}

	/**
	 * ticket detail
	 * @param  string $id 
	 * @return [type]     
	 */
	public function ticket_detail($id = '')
	{

		$ticket = $this->customer_service_model->get_ticket($id);
		if (!$ticket) {
			blank_page(_l('ticket_not_found'));
		}
		
		$this->load->model('departments_model');
		$data = [];

		$data['ticket'] = $ticket;
		$data['title']          = $data['ticket']->code;
		$data['ticket_detail_data'] = $this->customer_service_model->get_ticket_detail_data($id);
		$data['workflow'] = $this->customer_service_model->get_ticket_work_flow($id);
		$data['departments'] = $this->departments_model->get();
		$data['staffs'] = $this->staff_model->get('', ['active' => 1]);
		$data['ticket_histories'] = $this->customer_service_model->get_ticket_history($id);
		$data['ticket_post_internal_histories'] = $this->customer_service_model->get_ticket_post_internal_history($id, true);
		$data['ticket_the_sames'] = $this->customer_service_model->find_similar_content_tickets($id);

		// invoices
		$client_id = $data['ticket']->client_id;
		$filterdate = date('Y-m-d H:i:s', strtotime('-10 days'));

		$this->load->model('invoices_model');
		$invoices = $this->invoices_model->get('', db_prefix().'invoices.datecreated >= "'.$filterdate.'" AND '.db_prefix().'invoices.clientid = '.$client_id);
		$data['invoices'] = $invoices;

		// payments
		// expenses
		$this->load->model('expenses_model');
		$expenses = $this->expenses_model->get('', db_prefix().'expenses.dateadded >= "'.$filterdate.'" AND '.db_prefix().'expenses.clientid = '.$client_id);
		$data['expenses'] = $expenses;

		// projects
		$project_filterdate = date('Y-m-d', strtotime('-10 days'));
		$this->load->model('projects_model');
		$projects = $this->projects_model->get('', db_prefix().'projects.project_created >= "'.$project_filterdate.'" AND '.db_prefix().'projects.clientid = '.$client_id);
		$data['projects'] = $projects;


		// contracts
		$this->load->model('contracts_model');
		$contracts = $this->contracts_model->get('', db_prefix().'contracts.dateadded >= "'.$filterdate.'" AND '.db_prefix().'contracts.client = '.$client_id);
		$data['contracts'] = $contracts;


		if(cs_get_status_modules('warranty_management')){
			$this->load->model('warranty_management/warranty_management_model');

			$this->db->where(db_prefix().'sm_service_details.datecreated >= "'.$filterdate.'" AND '.db_prefix().'sm_service_details.client_id = '.$client_id);
			$warranties_managements = $this->db->get(db_prefix().'sm_service_details')->result_array();
			$warranty_claims = $this->warranty_management_model->get_warranty_claim(false, db_prefix().'wm_warranty_claim_informations.datecreated >= "'.$filterdate.'" AND '.db_prefix().'wm_warranty_claim_informations.client_id = '.$client_id);

			$data['warranties_managements'] = $warranties_managements;
			$data['warranty_claims'] = $warranty_claims;

		}

		if(cs_get_status_modules('service_management')){
			$this->load->model('service_management/service_management_model');

			$transactions = $this->service_management_model->get_service(false, db_prefix().'sm_service_details.datecreated >= "'.$filterdate.'" AND '.db_prefix().'sm_service_details.client_id = '.$client_id);
			$orders = $this->service_management_model->get_order(false, db_prefix().'sm_orders.datecreated >= "'.$filterdate.'" AND '.db_prefix().'sm_orders.client_id = '.$client_id);

			$data['transactions'] = $transactions;
			$data['orders'] = $orders;
		}

		if(cs_get_status_modules('warehouse')){
			$goods_delivery_filterdate = date('Y-m-d', strtotime('-10 days'));

			$this->load->model('warehouse/warehouse_model');

			$this->db->where(db_prefix().'goods_delivery.date_add >= "'.$goods_delivery_filterdate.'" AND '.db_prefix().'goods_delivery.customer_code = '.$client_id);
			$goods_delivery = $this->db->get(db_prefix() . 'goods_delivery')->result_array();
			$data['goods_delivery'] = $goods_delivery;
		}

		$this->load->view('tickets/ticket_detail', $data);
	}

	/**
	 * get status value html
	 * @return [type] 
	 */
	public function get_status_value_html()
	{
		$data = $this->input->post();
		$html = '';

		if($data['status_type'] == 'stage_status'){
			$status_value = cs_stage_status();
		}else{
			$status_value = cs_ticket_status();
		}

		foreach ($status_value as $value) {
    		$html .= '<option value="' . $value['id'] . '">' . ($value['name']) . '</option>';
		}

		echo json_encode([
			'html' => $html,
		]);

	}

	/**
	 * customer service status mark as
	 * @param  [type] $status 
	 * @param  [type] $id     
	 * @param  [type] $type   
	 * @return [type]         
	 */
	public function customer_service_status_mark_as($status, $id, $type)
	{
		$success = $this->customer_service_model->customer_service_status_mark_as($status, $id, $type);
		$message = '';

		if ($success) {
			$message = _l('cs_change_status_successfully');
		}
		echo json_encode([
			'success'  => $success,
			'message'  => $message
		]);
	}

	/**
	 * cs run cron manually
	 * @return [type] 
	 */
	public function cs_run_cron_manually()
	{
		if (is_admin()) {
			$this->customer_service_model->cron_manually(true);
			set_alert('success', _l('cs_manual_mail_scanning_successfully'));

			redirect(admin_url('customer_service/ticket_pipe_logs'));
		}
	}

	/**
	 * add post reply attachment
	 * @param [type] $id 
	 */
	public function add_post_reply_attachment($id, $tiket_id) {
		handle_post_reply_attachments($id);
		echo json_encode([
			'url' => admin_url('customer_service/ticket_detail/'.$tiket_id),
		]);
	}

	/**
	 * ticket post reply
	 * @param  string $id 
	 * @return [type]     
	 */
	public function ticket_post_reply($id = '')
	{
		$data = $this->input->post();
		if ($data) {
			if (!isset($data['id'])) {

				$id = $this->customer_service_model->add_ticket_reply($data);
				if ($id) {

					// handle commodity list add edit file
					$success = true;
					$message = _l('cs_added_successfully');
					set_alert('success', $message);
					/*upload multifile*/
					echo json_encode([
						'url' => admin_url('customer_service/ticket_detail/'.$data['ticket_id']),
						'ticket_id' => $data['ticket_id'],
						'post_id' => $id,
					]);
					die;
				}
				echo json_encode([
					'url' => admin_url('customer_service/ticket_detail/'.$data['ticket_id']),
				]);
				die;
			}
		}
	}

	/**
	 * ticket post internal reply
	 * @param  string $id 
	 * @return [type]     
	 */
	public function ticket_post_internal_reply($id = '')
	{
		$data = $this->input->post();
		if ($data) {
			if (!isset($data['id'])) {

				$id = $this->customer_service_model->add_ticket_internal_reply($data);
				if ($id) {
					set_alert('success', _l('cs_added_successfully'));
				}
				redirect(admin_url('customer_service/ticket_detail/'.$data['ticket_id']));
			}
		}
	}

	/**
	 * ticket department transfer
	 * @return [type] 
	 */
	public function ticket_department_transfer()
	{
		$data = $this->input->post();
		if ($data) {
			if (!isset($data['id'])) {

				$id = $this->customer_service_model->add_department_transfer($data);
				if ($id) {
					set_alert('success', _l('cs_added_successfully'));
				}
				redirect(admin_url('customer_service/ticket_detail/'.$data['ticket_id']));
			}
		}
	}

	/**
	 * ticket reassign
	 * @return [type] 
	 */
	public function ticket_reassign()
	{
		$data = $this->input->post();
		if ($data) {
			if (!isset($data['id'])) {

				$id = $this->customer_service_model->add_ticket_reassign($data);
				if ($id) {
					set_alert('success', _l('cs_added_successfully'));
				}
				redirect(admin_url('customer_service/ticket_detail/'.$data['ticket_id']));
			}
		}
	}

	/**
	 * delete ticket history
	 * @param  [type] $id   
	 * @param  [type] $type 
	 * @return [type]       
	 */
	public function delete_ticket_history($id, $type)
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}

		$delete = $this->customer_service_model->delete_ticket_history($id, $type);
		if($delete){
			$status = true;
		}else{
			$status = false;
		}

		echo json_encode([
			'success' => $status,
		]);
	}

	/**
	 * email template table
	 * @return [type] 
	 */
	public function email_template_table()
	{
		$this->app->get_table_data(module_views_path('customer_service', 'settings/email_templates/email_template_table'));
	}

	/**
	 * add edit email template
	 * @param string $id 
	 */
	public function add_edit_email_template($id='')
	{
		if (!has_permission('customer_service', '', 'view')) {
			access_denied('email_templates');
		}
		$this->load->model('emails_model');
		if ($this->input->post()) {
			if (!has_permission('customer_service', '', 'create') && !has_permission('customer_service', '', 'edit')) {
				access_denied('email_templates');
			}

			if ($id == '') {
				$data = $this->input->post();
				$tmp  = $this->input->post(null, false);

				foreach ($data['message'] as $key => $contents) {
					$data['message'][$key] = $tmp['message'][$key];
				}

				foreach ($data['subject'] as $key => $contents) {
					$data['subject'][$key] = $tmp['subject'][$key];
				}

				$data['fromname'] = $tmp['fromname'];

				$id = $this->customer_service_model->cs_add_email_template($data);

				if ($id) {
					set_alert('success', _l('updated_successfully', _l('email_template')));
				}

				redirect(admin_url('customer_service/add_edit_email_template/' . $id));
			}else{
				$this->load->model('emails_model');
				$data = $this->input->post();
				$tmp  = $this->input->post(null, false);

				foreach ($data['message'] as $key => $contents) {
					$data['message'][$key] = $tmp['message'][$key];
				}

				foreach ($data['subject'] as $key => $contents) {
					$data['subject'][$key] = $tmp['subject'][$key];
				}

				$data['fromname'] = $tmp['fromname'];

				$success = $this->emails_model->update($data);

				if ($success) {
					set_alert('success', _l('updated_successfully', _l('email_template')));
				}

				redirect(admin_url('customer_service/add_edit_email_template/' . $id));
			}
		}

        // English is not included here
		$data['available_languages'] = $this->app->get_available_languages();

		if (($key = array_search('english', $data['available_languages'])) !== false) {
			unset($data['available_languages'][$key]);
		}

		$data['available_merge_fields'] = $this->app_merge_fields->all();

		if(is_numeric($id)){
			$data['template'] = $this->emails_model->get_email_template_by_id($id);
			$title            = $data['template']->name;
		}else{
			$data['template'] = $this->emails_model->get_email_template_by_id($id);
			$title            = _l('cs_add_email_template');
		}

		$data['title']    = $title;

        $this->load->view('settings/email_templates/add_edit_email_template', $data);
	}

	/**
	 * delete email template
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_email_template($id)
	{
		if (!$id) {
			redirect(admin_url('customer_service/kpi_manage'));
		}

		if(!has_permission('customer_service', '', 'delete')  &&  !is_admin()) {
			access_denied('customer_service');
		}

		$response = $this->customer_service_model->delete_email_template($id);
		if ($response) {
			set_alert('success', _l('deleted'));
			redirect(admin_url('customer_service/setting?group=email_template'));
		} else {
			set_alert('warning', _l('problem_deleting'));
			redirect(admin_url('customer_service/setting?group=email_template'));
		}
	}

	/**
	 * get staff option by department
	 * @return [type] 
	 */
	public function get_staff_option_by_department()
	{
		$data = $this->input->post();
		$html = $this->customer_service_model->cs_get_staff_by_department($data['department_id']);

		echo json_encode([
			'html' => $html,
		]);

	}

	/**
	 * run ticket manually
	 * @return [type] 
	 */
	public function run_ticket_manually()
	{
		$this->customer_service_model->cs_cron_ticket();
		redirect(admin_url('customer_service/tickets'));
	}

	/**
	 * update support term condition
	 * @return [type] 
	 */
	public function update_support_term_condition()
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->input->post();

			if ((isset($data)) && $data != '') {
				$myContent = $this->input->post('myContent', false);
				$status = update_option('cs_support_term_condition', $myContent, 1);
				if($status){
					$message = _l('updated_successfully');
				}else{
					$message = _l('updated_failed');
				}

				echo json_encode([
					'message' => $message,
					'status' =>$status,
				]);
			}
		}
	}

	/**
	 * dashboard
	 * @return [type] 
	 */
	public function dashboard()
	{
	    if (!has_permission('customer_service', '', 'view')  && !is_admin()) {
			access_denied('dashboard');
		}

		$data['title'] = _l('dashboard');
		$data['ticket_status'] = $this->customer_service_model->count_ticket_by_status();
		$data['ticket_total_hours'] = $this->customer_service_model->ticket_total_hours();
		$data['cal_CSAT'] = $this->customer_service_model->cal_CSAT();

		$this->load->view('customer_service/dashboards/dashboard', $data);
	}

	/**
	 * ticket report by status
	 * @return [type] 
	 */
	public function report_by_ticket_on_hold_closed()
	{
		if ($this->input->is_ajax_request()) { 
			$data = $this->input->get();

			$months_report = $data['months_report'];
			$report_from = $data['report_from'];
			$report_to = $data['report_to'];

			if($months_report == ''){

				$from_date = date('Y-m-d', strtotime('1997-01-01'));
				$to_date = date('Y-m-d', strtotime(date('Y-12-31')));
			}

			if($months_report == 'this_month'){
				$from_date = date('Y-m-01');
				$to_date   = date('Y-m-t');
			}

			if($months_report == '1'){ 
				$from_date = date('Y-m-01', strtotime('first day of last month'));
				$to_date   = date('Y-m-t', strtotime('last day of last month'));
			}

			if($months_report == 'this_year'){
				$from_date = date('Y-m-d', strtotime(date('Y-01-01')));
				$to_date = date('Y-m-d', strtotime(date('Y-12-31')));
			}

			if($months_report == 'last_year'){

				$from_date = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01')));
				$to_date = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31')));  


			}

			if($months_report == '3'){
				$months_report = 3;
				$months_report--;
				$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
				$to_date   = date('Y-m-t');

			}

			if($months_report == '6'){
				$months_report = 6;
				$months_report--;
				$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
				$to_date   = date('Y-m-t');
			}

			if($months_report == '12'){
				$months_report = 12;
				$months_report--;
				$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
				$to_date   = date('Y-m-t');
			}

			if($months_report == 'custom'){
				$from_date = to_sql_date($report_from);
				$to_date   = to_sql_date($report_to);
			}
	
			$mo_data = $this->customer_service_model->get_ticket_on_hold_closed_data($from_date, $to_date);


			echo json_encode([
				'categories' => $mo_data['categories'],
				'open' => $mo_data['open'],
				'inprogress' => $mo_data['inprogress'],
				'answered' => $mo_data['answered'],
				'on_hold' => $mo_data['on_hold'],
				'closed' => $mo_data['closed'],
			]); 
		}
	}

	/**
	 * ticket total hours
	 * @return [type] 
	 */
	public function ticket_total_hours()
	{
		if ($this->input->is_ajax_request()) { 
			$data = $this->input->get();

			$months_report = $data['months_report'];
			$report_from = $data['report_from'];
			$report_to = $data['report_to'];

			if($months_report == ''){

				$from_date = date('Y-m-d', strtotime('1997-01-01'));
				$to_date = date('Y-m-d', strtotime(date('Y-12-31')));
			}

			if($months_report == 'this_month'){
				$from_date = date('Y-m-01');
				$to_date   = date('Y-m-t');
			}

			if($months_report == '1'){ 
				$from_date = date('Y-m-01', strtotime('first day of last month'));
				$to_date   = date('Y-m-t', strtotime('last day of last month'));
			}

			if($months_report == 'this_year'){
				$from_date = date('Y-m-d', strtotime(date('Y-01-01')));
				$to_date = date('Y-m-d', strtotime(date('Y-12-31')));
			}

			if($months_report == 'last_year'){

				$from_date = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01')));
				$to_date = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31')));  


			}

			if($months_report == '3'){
				$months_report = 3;
				$months_report--;
				$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
				$to_date   = date('Y-m-t');

			}

			if($months_report == '6'){
				$months_report = 6;
				$months_report--;
				$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
				$to_date   = date('Y-m-t');
			}

			if($months_report == '12'){
				$months_report = 12;
				$months_report--;
				$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
				$to_date   = date('Y-m-t');
			}

			if($months_report == 'custom'){
				$from_date = to_sql_date($report_from);
				$to_date   = to_sql_date($report_to);
			}
	
			$mo_data = $this->customer_service_model->ticket_total_hours($from_date, $to_date);


			echo json_encode([
				'total_hours' => round((float)$mo_data['total_hours'], 2),
				'avg_resolution_time' => round((float)$mo_data['avg_resolution_time'], 2).' '._l('cs_hours'),
			]); 
		}
	}

	/**
	 * report by ticket status
	 * @return [type] 
	 */
	public function report_by_ticket_status()
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->input->get();

			$months_report = $data['months_report'];
			$report_from = $data['report_from'];
			$report_to = $data['report_to'];

			if($months_report == ''){

				$from_date = date('Y-m-d', strtotime('1997-01-01'));
				$to_date = date('Y-m-d', strtotime(date('Y-12-31')));
			}

			if($months_report == 'this_month'){
				$from_date = date('Y-m-01');
				$to_date   = date('Y-m-t');
			}

			if($months_report == '1'){ 
				$from_date = date('Y-m-01', strtotime('first day of last month'));
				$to_date   = date('Y-m-t', strtotime('last day of last month'));
			}

			if($months_report == 'this_year'){
				$from_date = date('Y-m-d', strtotime(date('Y-01-01')));
				$to_date = date('Y-m-d', strtotime(date('Y-12-31')));
			}

			if($months_report == 'last_year'){

				$from_date = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01')));
				$to_date = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31')));  


			}

			if($months_report == '3'){
				$months_report = 3;
				$months_report--;
				$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
				$to_date   = date('Y-m-t');

			}

			if($months_report == '6'){
				$months_report = 6;
				$months_report--;
				$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
				$to_date   = date('Y-m-t');
			}

			if($months_report == '12'){
				$months_report = 12;
				$months_report--;
				$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
				$to_date   = date('Y-m-t');
			}

			if($months_report == 'custom'){
				$from_date = to_sql_date($report_from);
				$to_date   = to_sql_date($report_to);
			}

			$list_status = array(
				"open",
				"inprogress",
				"answered",
				"on_hold",
				"closed",
			);
			$list_result = array();
			$ticket_by_status = $this->customer_service_model->count_ticket_by_status_with_time($from_date, $to_date);

			$count_total = $ticket_by_status['all'];

			foreach ($list_status as $key => $value) {
				if ($count_total <= 0) {
					$ca_percent = 0;
				} else {
					$status_qty = 0;
					if(isset($ticket_by_status[$value])){
						$status_qty = $ticket_by_status[$value];
					}
					$ca_percent = round((float)(((int)$status_qty * 100) / $count_total), 2);
				}
				array_push($list_result, array('name' => _l('cs_'.$value), 'y' => $ca_percent));
			}


			echo json_encode([
				'data_result' => $list_result,
			]);
			die;
		}
	}

	/**
	 * report by ticket category
	 * @return [type] 
	 */
	public function report_by_ticket_category()
	{
		if ($this->input->is_ajax_request()) {
			$data = $this->input->get();

			$months_report = $data['months_report'];
			$report_from = $data['report_from'];
			$report_to = $data['report_to'];

			if($months_report == ''){

				$from_date = date('Y-m-d', strtotime('1997-01-01'));
				$to_date = date('Y-m-d', strtotime(date('Y-12-31')));
			}

			if($months_report == 'this_month'){
				$from_date = date('Y-m-01');
				$to_date   = date('Y-m-t');
			}

			if($months_report == '1'){ 
				$from_date = date('Y-m-01', strtotime('first day of last month'));
				$to_date   = date('Y-m-t', strtotime('last day of last month'));
			}

			if($months_report == 'this_year'){
				$from_date = date('Y-m-d', strtotime(date('Y-01-01')));
				$to_date = date('Y-m-d', strtotime(date('Y-12-31')));
			}

			if($months_report == 'last_year'){

				$from_date = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01')));
				$to_date = date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31')));  


			}

			if($months_report == '3'){
				$months_report = 3;
				$months_report--;
				$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
				$to_date   = date('Y-m-t');

			}

			if($months_report == '6'){
				$months_report = 6;
				$months_report--;
				$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
				$to_date   = date('Y-m-t');
			}

			if($months_report == '12'){
				$months_report = 12;
				$months_report--;
				$from_date = date('Y-m-01', strtotime("-$months_report MONTH"));
				$to_date   = date('Y-m-t');
			}

			if($months_report == 'custom'){
				$from_date = to_sql_date($report_from);
				$to_date   = to_sql_date($report_to);
			}

			
			$list_result = $this->customer_service_model->ticket_by_category($from_date, $to_date);

			echo json_encode([
				'data_result' => $list_result['chart'],
				'categories' => $list_result['categories'],
			]);
			die;
		}
	}

	/**
	 * work flow detail
	 * @param  string $id 
	 * @return [type]     
	 */
	public function work_flow_detail($id = '')
	{

		$get_workflow = $this->customer_service_model->get_workflow($id);
		if (!$get_workflow) {
			blank_page(_l('workflow_not_found'));
		}
		
		$data = [];
		$data['workflow'] = $get_workflow;
		$this->load->view('work_flows/workflow_detail', $data);
	}

	/**
	 * kpi detail
	 * @param  string $id 
	 * @return [type]     
	 */
	public function kpi_detail($id = '')
	{

		$get_kpi = $this->customer_service_model->get_kpi($id);
		if (!$get_kpi) {
			blank_page(_l('cs_kpi_not_found'));
		}
		
		$data = [];
		$data['kpi'] = $get_kpi;
		$data['kpi_rule'] = $this->customer_service_model->get_ticket_by_kpi_rule($id);

		$this->load->view('settings/kpis/kpi_detail', $data);
	}

	/**
	 * sla detail
	 * @param  string $id 
	 * @return [type]     
	 */
	public function sla_detail($id = '')
	{

		$get_sla = $this->customer_service_model->get_sla($id);
		if (!$get_sla) {
			blank_page(_l('cs_sla_not_found'));
		}
		
		$data = [];
		$data['sla'] = $get_sla;
		$data['sla_rule'] = $this->customer_service_model->get_ticket_by_sla($id);

		$this->load->view('settings/slas/sla_detail', $data);
	}

	public function get_invoice_by_client($client_id = '')
	{
		$this->load->model('invoices_model');
		if(cs_get_status_modules('warranty_management')){
			$this->load->model('warranty_management/warranty_management_model');
			$invoices = $this->warranty_management_model->get_list_item_warranty($client_id);
		}else{
			$this->load->model('invoices_model');
			$invoices = $this->invoices_model->get(false, db_prefix().'invoices.clientid = '.$client_id);
		}

		$invoice_option = '';
		$invoice_option .=' <option value=""></option>';
		$arr_temp_invoice_ids = [];
		foreach ($invoices as $invoice) {

			$select='';

			if(cs_get_status_modules('warranty_management')){
				if(!in_array($invoice['invoice_id'], $arr_temp_invoice_ids)){

					$invoice_option .= '<option value="' .$invoice['invoice_id'] . '" '.$select.'>' . format_invoice_number($invoice['invoice_id']) . '</option>';
				}
				$arr_temp_invoice_ids[] = $invoice['invoice_id'];
			}else{
				if(!in_array($invoice['id'], $arr_temp_invoice_ids)){

					$invoice_option .= '<option value="' .$invoice['id'] . '" '.$select.'>' . format_invoice_number($invoice['id']) . '</option>';
				}
				$arr_temp_invoice_ids[] = $invoice['id'];
			}
		}
		echo json_encode([
			'invoice_option' => $invoice_option,
		]);
	}

	/**
	 * get list item warranty by invoice
	 * @param  string $invoice_id 
	 * @return [type]             
	 */
	public function get_list_item_warranty_by_invoice($invoice_id = '')
	{

		$item_warranty_option = '';
		$list_item_warranty = $this->customer_service_model->cs_get_list_item_warranty_by_invoice($invoice_id);

		$get_base_currency =  get_base_currency();
		if($get_base_currency){
			$base_currency_id = $get_base_currency->id;
		}else{
			$base_currency_id = 0;
		}

		$item_warranty_option .=' <option value=""></option>';
		foreach ($list_item_warranty as $item) {

			$select='';

			$item_name = $item['item_name'];
			if(new_strlen($item_name) == 0){
				$item_name = cs_get_item_variatiom($item['item_id']);
			}

			if(!isset($item['billing_plan_rate'])){
				$data_sub_text = app_format_money((float)$item['rate'], $base_currency_id);
			}else{
				$data_sub_text = app_format_money((float)$item['billing_plan_rate'], $base_currency_id).' ('. $item['rate'].' '. _l($item['billing_plan_type']) . ')';
			}

			if(isset($item['start_date'])){
				$data_sub_text .= ' - '._dt($item['start_date']) .' - '. _dt($item['warranty_period']);
			}else{
				if($item['warranty_period'] != null && new_strlen($item['warranty_period']) > 0){
					$data_sub_text .= ' - '._d($item['date_add']) .' - '. _d($item['warranty_period']);
				}else{
					$data_sub_text .= ' - '._d($item['date_add']) .' - ...';
				}
			}

			$item_warranty_option .= '<option value="' . $item['item_id'] . '" '.$select.' data-subtext="'. strip_tags($data_sub_text).'">' . $item_name . '</option>';
		}
		echo json_encode([
			'item_warranty_option' => $item_warranty_option,
		]);
	}
	

	/*end file*/
}