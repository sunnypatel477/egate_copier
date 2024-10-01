<?php

defined('BASEPATH') or exit('No direct script access allowed');

#[\AllowDynamicProperties]
class Customer_service_email_template_merge_fields extends App_merge_fields
{
	public function build()
	{
		return [
			//staff infor
			[
				'name'      => 'Ticket ID',
				'key'       => '{ticket_id}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Ticket Code',
				'key'       => '{ticket_code}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Ticket Summary',
				'key'       => '{issue_summary}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Client',
				'key'       => '{client_name}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Invoice',
				'key'       => '{invoice_name}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Product name',
				'key'       => '{product_name}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Ticket Source',
				'key'       => '{ticket_source}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Ticket Category',
				'key'       => '{category_name}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Assignee Department',
				'key'       => '{assignee_department}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Assignee User',
				'key'       => '{assignee_user}',
				'available' => [
					'customer_service_email_template',
				],
			],

			[
				'name'      => 'Assignee User Email',
				'key'       => '{assignee_user_email}',
				'available' => [
					'customer_service_email_template',
				],
			],
			
			[
				'name'      => 'SLA Name',
				'key'       => '{ticket_sla_name}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Time Spent',
				'key'       => '{ticket_time_spent}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Ticket Due Date',
				'key'       => '{ticket_due_date}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Ticket Priority',
				'key'       => '{priority_level}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Ticket Type',
				'key'       => '{ticket_type}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Ticket Internal Note',
				'key'       => '{internal_note}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Ticket Lass Message Time',
				'key'       => '{last_message_time}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Ticket Lass Respone Time',
				'key'       => '{last_response_time}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Ticket First Reply Time',
				'key'       => '{first_reply_time}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Ticket Resolution',
				'key'       => '{ticket_resolution}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Ticket Status',
				'key'       => '{ticket_status}',
				'available' => [
					'customer_service_email_template',
				],
			],

				//Stage
			[
				'name'      => 'Stage Name',
				'key'       => '{stage_name}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Stage Description',
				'key'       => '{stage_description}',
				'available' => [
					'customer_service_email_template',
				],
			],
			[
				'name'      => 'Stage Status',
				'key'       => '{stage_status}',
				'available' => [
					'customer_service_email_template',
				],
			],

		];
	}

	/**
	 * Merge field for contracts
	 * @param  mixed $contract_id contract id
	 * @return array
	 */
	public function format($data)
	{
		$ticket_id = $data->ticket_id;
		$stage_id = $data->stage_id;

		$fields = [];

		$this->ci->db->select('*,'.db_prefix().'cs_tickets.id as id');
        $this->ci->db->where('id', $ticket_id);
        $this->ci->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.userid = ' . db_prefix() . 'cs_tickets.client_id', 'left');
        $this->ci->db->join(db_prefix() . 'staff', '' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'cs_tickets.assigned_id');
		$ticket = $this->ci->db->get(db_prefix() . 'cs_tickets')->row();

		if (!$ticket) {
			return $fields;
		}

		/*get Stage data*/
		$get_ticket = new stdClass();
		$get_ticket->id = $ticket->id;
		$get_client = new stdClass();
		$get_client->userid =  $ticket->client_id;
		$data_node = [];
		$data_node['id'] = $stage_id;

		$stage_name = '';
		$stage_description = '';
		$stage_status = '';

		$ticket_detail_data = $this->ci->customer_service_model->get_ticket_detail_data($ticket_id);

		if(count($ticket_detail_data['stages']) > 0){
			foreach ($ticket_detail_data['stages'] as $ticket_detail) {
				if($ticket_detail['id'] == $stage_id){
					$stage_name = $ticket_detail['name'];
					$stage_description = $ticket_detail['stage_description'];
					$stage_status = _l($ticket_detail['status']);
				}
			}
		}

		$currency = get_base_currency();

		$fields['{ticket_id}'] = $ticket->id;
		$fields['{ticket_code}'] = $ticket->code;
		$fields['{issue_summary}'] = $ticket->issue_summary;
		$fields['{client_name}'] = get_company_name($ticket->client_id);
		$fields['{invoice_name}'] = format_invoice_number($ticket->invoice_id);
		$fields['{product_name}'] = cs_get_item_variatiom($ticket->item_id);
		$fields['{ticket_source}'] = _l($ticket->ticket_source);
		$fields['{category_name}'] = cs_get_category_name($ticket->category_id);
		$fields['{assignee_department}'] = cs_get_department_name($ticket->department_id);
		$fields['{assignee_user}'] = get_staff_full_name($ticket->assigned_id);
		$fields['{assignee_user_email}'] = cs_get_staff_email($ticket->assigned_id);
		$fields['{ticket_sla_name}'] = cs_get_sla_name($ticket->sla_id);
		$fields['{ticket_time_spent}'] = $ticket->time_spent;
		$fields['{ticket_due_date}'] = $ticket->due_date;
		$fields['{priority_level}'] = _l('cs_'.$ticket->priority_level);
		$fields['{ticket_type}'] = $ticket->ticket_type;
		$fields['{internal_note}'] = $ticket->internal_note;
		$fields['{last_message_time}'] = $ticket->last_message_time;
		$fields['{last_response_time}'] = $ticket->last_response_time;
		$fields['{first_reply_time}'] = $ticket->first_reply_time;
		$fields['{ticket_resolution}'] = $ticket->resolution;
		$fields['{ticket_status}'] = _l('cs_'.$ticket->status);

		$fields['{stage_name}'] = $stage_name;
		$fields['{stage_description}'] = $stage_description;
		$fields['{stage_status}'] = $stage_status;
		
		return hooks()->apply_filters('customer_service_email_template_merge_fields', $fields, [
			'ticket_id'       => $ticket_id,
			'stage_id'       => $stage_id,
		]);
	}
}
