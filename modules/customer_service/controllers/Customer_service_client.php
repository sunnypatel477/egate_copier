<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * This class describes a warehouse client.
 */
class Customer_service_client extends ClientsController
{

	/**
	 * __construct description
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->model('customer_service_model');
		$this->load->helper('download');

		if(get_option('customer_service_display_on_portal') != 1){
			set_alert('warning', _l('access_denied'));
			redirect(site_url('clients'));
		}

		if(!is_client_logged_in()){ 
			redirect_after_login_to_current_url();
			redirect(site_url('authentication/login'));
		}
	}

	/**
	 * tickets
	 * @param  boolean $status 
	 * @return [type]          
	 */
	public function tickets($status = false)
	{

		if($status && $status != 'all'){
			$data['tickets'] = $this->customer_service_model->get_ticket(false, 'client_id = '.get_client_user_id().' AND status = "'.$status.'"');
		}else{
			$data['tickets'] = $this->customer_service_model->get_ticket(false, 'client_id = '.get_client_user_id());
		}

		$data['ticket_status'] = $this->customer_service_model->count_ticket_by_status(get_client_user_id());

		$get_base_currency =  get_base_currency();
		if($get_base_currency){
			$base_currency_id = $get_base_currency->id;
		}else{
			$base_currency_id = 0;
		}
		$data['base_currency_id'] = $base_currency_id;


		$data['title']    = _l('customer_service_name');
		$this->data($data);
		$this->view('client_portals/tickets/ticket_manage');

		$this->layout();
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
			blank_page(_l('cs_ticket_not_found'));
		}
		
		$this->load->model('departments_model');
		$data = [];

		$data['ticket'] = $ticket;
		$data['title']          = $data['ticket']->code;
		$data['ticket_detail_data'] = $this->customer_service_model->get_ticket_detail_data($id);
		$data['workflow'] = $this->customer_service_model->get_ticket_work_flow($id);
		$data['departments'] = $this->departments_model->get();
		$data['staffs'] = $this->staff_model->get();
		$data['ticket_histories'] = $this->customer_service_model->get_ticket_history($id, true);
		$data['ticket_post_internal_histories'] = $this->customer_service_model->get_ticket_post_internal_history($id, true);

		$this->data($data);
		$this->view('client_portals/tickets/ticket_detail');
		$this->layout();
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

				$id = $this->customer_service_model->client_post_reply($data);
				if ($id) {
					set_alert('success', _l('cs_added_successfully'));
				}
				redirect(site_url('customer_service/customer_service_client/ticket_detail/'.$data['ticket_id']));
			}
		}
	}

	/**
	 * add edit ticket
	 * @param string $id 
	 */
	public function add_edit_ticket($id='')
	{
		if ($this->input->post()) {
			$data = $this->input->post();

			if ($id == '') {
			
				$id = $this->customer_service_model->add_ticket($data);
				if ($id) {
					set_alert('success', _l('cs_added_successfully'));
					redirect(site_url('customer_service/customer_service_client/tickets'));
				}

			} else {
			
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
				redirect(site_url('customer_service/customer_service_client/tickets'));

			}
		}

		if(is_numeric($id)){
			$data['is_edit'] = false;
			$data['title'] = _l('cs_edit_ticket');
			$data['ticket'] = $this->customer_service_model->get_ticket($id);
			$data['categories'] = $this->customer_service_model->get_category(false, 'enabled', 'public');
			if(cs_get_status_modules('warranty_management')){
				$this->load->model('warranty_management/warranty_management_model');

				$invoices = $this->warranty_management_model->get_list_item_warranty($data['ticket']->client_id);
				$data['item_tickets'] = $this->warranty_management_model->get_list_item_warranty_by_invoice($data['ticket']->invoice_id);
			}else{
				$this->load->model('invoices_model');

				$invoices = $this->invoices_model->get(false, db_prefix().'invoices.clientid = '.get_client_user_id());
				$data['item_tickets'] = $this->customer_service_model->cs_get_commodity();
			}

			$arr_temp_invoice_ids = [];
			$invoice_data = [];
			foreach ($invoices as $invoice) {
				$select='';
				if(cs_get_status_modules('warranty_management')){
					if(!in_array($invoice['invoice_id'], $arr_temp_invoice_ids)){
						$invoice_data[] = [
							'id' => $invoice['invoice_id'],
						];
					}
					$arr_temp_invoice_ids[] = $invoice['invoice_id'];
				}else{

					if(!in_array($invoice['id'], $arr_temp_invoice_ids)){
						$invoice_data[] = [
							'id' => $invoice['id'],
						];
					}
					$arr_temp_invoice_ids[] = $invoice['id'];
				}
			}

			$data['invoices'] = $invoice_data;

		}else{

			$data['is_edit'] = true;
			$data['title'] = _l('cs_add_ticket');
			$data['categories'] = $this->customer_service_model->get_category(false, 'enabled', 'public');
			if(cs_get_status_modules('warranty_management')){
				$this->load->model('warranty_management/warranty_management_model');
				$invoices = $this->warranty_management_model->get_list_item_warranty(get_client_user_id());
			}else{
				$this->load->model('invoices_model');
				$invoices = $this->invoices_model->get(false, db_prefix().'invoices.clientid = '.get_client_user_id());
			}

			$arr_temp_invoice_ids = [];
			$invoice_data = [];
			foreach ($invoices as $invoice) {
				$select='';
				if(cs_get_status_modules('warranty_management')){
					if(!in_array($invoice['invoice_id'], $arr_temp_invoice_ids)){
						$invoice_data[] = [
							'id' => $invoice['invoice_id'],
						];
					}
					$arr_temp_invoice_ids[] = $invoice['invoice_id'];
				}else{

					if(!in_array($invoice['id'], $arr_temp_invoice_ids)){
						$invoice_data[] = [
							'id' => $invoice['id'],
						];
					}
					$arr_temp_invoice_ids[] = $invoice['id'];
				}
			}
			$data['invoices'] = $invoice_data;

		}
		$this->load->model('staff_model');
		$data['clients'] = $this->clients_model->get();
		$data['ticket_code'] = $this->customer_service_model->create_code('ticket_code');
		$data['staffs'] = $this->staff_model->get('', ['active' => 1]);
		$data['slas'] = $this->customer_service_model->get_sla();
		$data['id'] = $id;

		$this->load->model('departments_model');
		$data['departments'] = $this->departments_model->get();

		$this->data($data);
		$this->view('client_portals/tickets/add_edit_ticket');
		$this->layout();
	}

	/**
	 * get invoice by client
	 * @param  string $client_id 
	 * @return [type]            
	 */
	public function get_invoice_by_client($client_id = '')
	{

		if(cs_get_status_modules('warranty_management')){
			$this->load->model('warranty_management/warranty_management_model');
			$invoices = $this->warranty_management_model->get_list_item_warranty($client_id);
		}else{
			$this->load->model('invoices_model');
			$invoices = $this->invoices_model->get(false, db_prefix().'invoices.clientid = '.get_client_user_id());
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
	 * delete ticket
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_ticket($id)
	{
		
		if(!$id){
			set_alert('warning', _l('cs_ticket_not_found'));
			redirect(site_url('customer_service/customer_service_client/tickets'));
		}

		$ticket = $this->customer_service_model->get_ticket($id);

		if(!$ticket){
			set_alert('warning', _l('cs_ticket_not_found'));
			redirect(site_url('customer_service/customer_service_client/tickets'));
		}

		if($ticket->client_id != get_client_user_id()){
			access_denied('cs_ticket');
		}

		$success = $this->customer_service_model->delete_ticket($id);
		if ($success) {
			set_alert('success', _l('cs_deleted'));
		} else {
			set_alert('warning', _l('problem_deleting'));
		}
		redirect(site_url('customer_service/customer_service_client/tickets'));
	}

	/**
	 * support term condition
	 * @return [type] 
	 */
	public function support_term_condition()
	{
		$data = [];
		$this->data($data);
		$this->view('client_portals/support_term_conditions/support_term_condition');
		$this->layout();
	}

	/**
	 * rating modal
	 * @return [type] 
	 */
	public function rating_modal()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		$data['title'] = 'cs_rating';
		$data['ticket_id'] = $this->input->get('ticket_id');

		$this->data($data);
		$this->view('client_portals/ratings/rating_modal', $data);
		$this->layout();

	}

	/**
	 * get ticket information
	 * @param  [type] $ticket_id 
	 * @return [type]            
	 */
	public function get_ticket_information($ticket_id)
	{
		$ticket_name = '';
		$ticket = $this->customer_service_model->get_ticket($ticket_id);
		if($ticket){
			$ticket_name .= $ticket->code.' '.$ticket->issue_summary;
		}

		echo json_encode([
			'ticket_name' => $ticket_name,
		]);
	}

	/**
	 * ticket_rating
	 * @return [type] 
	 */
	public function ticket_rating()
	{
		$data = $this->input->post();
		if($data['ticket_id']){
			$this->db->where('id', $data['ticket_id']);
			$this->db->update(db_prefix().'cs_tickets', ['client_rating' => $data['rating_value']]);
			if ($this->db->affected_rows() > 0) {
				$rating_status = 'cs_satisfied';
				switch ($data['rating_value']) {
					case 1:
					$rating_status = 'cs_very_unsatisfied';
					break;
					case 2:
					$rating_status = 'cs_very_satisfied';
					break;
					case 3:

					$rating_status = 'cs_unsatisfied';

					break;
					case 4:
					$rating_status = 'cs_neutral';

					break;
					case 5:
					$rating_status = 'cs_satisfied';

					break;
					
					default:

					break;
				}

				$this->customer_service_model->cs_ticket_log($data['ticket_id'], 'cs_ticket', _l('cs_customer_satisfied_rating ').': '. _l($rating_status));
			}


			set_alert('success', _l('cs_rating_successfully'));
		}
		redirect(site_url('customer_service/customer_service_client/tickets'));
	}


}