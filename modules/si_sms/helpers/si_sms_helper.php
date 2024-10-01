<?php
defined('BASEPATH') or exit('No direct script access allowed');

function si_sms_get_customer_merge_fields($clientid)
{
	$merge_fields = array();
	$merge_fields['{client_company}'] = '';
	$merge_fields['{client_id}'] = '';
	$merge_fields['{contact_firstname}'] = '';
	$merge_fields['{contact_lastname}'] = '';
	if(is_null($clientid))
		return $merge_fields;

	$phonenumber = '';
	$CI = &get_instance();
	$client = $CI->clients_model->get($clientid);
	
	if (!$client) {
		return $merge_fields;
	}
	$contact = $CI->clients_model->get_contact(get_primary_contact_user_id($clientid));
	if ($contact) {
		if(!is_null($contact->phonenumber) && $contact->phonenumber!=='')
			$phonenumber = $contact->phonenumber;
		elseif(get_option(SI_SMS_MODULE_NAME.'_send_to_alt_client'))
			$phonenumber = $client->phonenumber;	
		$merge_fields['{contact_firstname}'] = $contact->firstname;
		$merge_fields['{contact_lastname}'] = $contact->lastname;
	}elseif(get_option(SI_SMS_MODULE_NAME.'_send_to_alt_client')){
		$phonenumber = $client->phonenumber;
		$merge_fields['{contact_firstname}'] = $client->company;
		$merge_fields['{contact_lastname}'] = '';
	}
	$merge_fields['{client_company}'] = $client->company;
	$merge_fields['{client_id}'] = $clientid;
	$merge_fields['phone_number'] = $phonenumber;
	return $merge_fields;
}

function send_schedule_sms_cron_run()
{
	$CI = &get_instance();
	##$CI->load->model('leads_model');
	##get time from and to, to get leads 
	$last_run = strtotime(get_option(SI_SMS_MODULE_NAME.'_trigger_schedule_sms_last_run'));
	$from_date = date('Y-m-d H:i:s',$last_run-(60*2));##getting 2 minutes before previous run, not to miss any scheduled
	$now = time();
	$to_date = date('Y-m-d H:i:s',$now);
	
	$result = $CI->si_sms_model->get_schedules($from_date,$to_date);
	$count=0;
	if(!empty($result)){
		foreach($result as $row){
			//send sms from each scheduler
			$custom_trigger_name = 'si_sms_custom_sms';
			$filter_by = $row['filter_by'];
			$message = $row['content'];
			$rel_ids = $CI->si_sms_model->get_schedule_rel_ids($row['id']);
			$contacts = array();
			if(!empty($rel_ids))
			if($filter_by=='customer'){
				$contacts = $CI->si_sms_model->get_client_contacts($rel_ids);
			}
			elseif($filter_by=='lead'){
				$contacts = $CI->si_sms_model->get_leads_contacts($rel_ids);
			}
			elseif($filter_by=='staff'){
				$contacts = $CI->si_sms_model->get_staffs_contacts($rel_ids);
			}
			
			try{
				if(!empty($contacts)){
					#check for DLT template Id if exist, add in options, if not added and add in data
					$dlt_template_id_key = $row['dlt_template_id_key'];
					$dlt_template_id_value = $row['dlt_template_id_value'];
					if($dlt_template_id_key !='' && $dlt_template_id_value != ''){
							add_option($dlt_template_id_key,$dlt_template_id_value);#add key if not exist
							$CI->app_object_cache->add($dlt_template_id_key, $dlt_template_id_value);//insert
							$CI->app_object_cache->set($dlt_template_id_key, $dlt_template_id_value);//update
							update_option($dlt_template_id_key, $dlt_template_id_value);
					}
					#check DLT Template ID end
					$oc_name = 'sms-trigger-' . $custom_trigger_name . '-value';
					$CI->app_object_cache->add($oc_name, $message);
					$CI->app_object_cache->set($oc_name, $message);
					update_option('sms_trigger_' . $custom_trigger_name,$message);
					foreach($contacts as $contact)
					{
						$merge_fields = ['{name}'=>$contact['name']];
						if($filter_by=='customer'){
							$merge_fields = $CI->app_merge_fields->format_feature('client_merge_fields', $contact['userid'],$contact['id']);
						}
						elseif($filter_by=='lead'){
							$merge_fields = $CI->app_merge_fields->format_feature('leads_merge_fields',$contact['id']);
						}
						elseif($filter_by=='staff'){
							$merge_fields = $CI->app_merge_fields->format_feature('staff_merge_fields',$contact['id']);
						}
						$response = $CI->app_sms->trigger($custom_trigger_name, $contact['phonenumber'], $merge_fields);
					}
					update_option('sms_trigger_'.$custom_trigger_name,'');
					if($dlt_template_id_key !='')
						update_option($dlt_template_id_key,'');
				}
				//update as executed
				$CI->si_sms_model->update_schedule($row['id'],array('executed'=>1,'cron'=>true));
			}
			catch(Exception $e){
				log_activity("Error in sending schedule SMS :".$e->getMessage());
			}
			
			$count++;
		}
		log_activity(_l('si_sms_schedule_success_activity_log_text',$count));	
	}
	clear_scheduled_sms_log();
	update_option(SI_SMS_MODULE_NAME.'_trigger_schedule_sms_last_run',date('Y-m-d H:i:s',$now));
}

//clear scheduled sms log after % days
function clear_scheduled_sms_log()
{
	$CI = &get_instance();
	$days = get_option(SI_SMS_MODULE_NAME.'_clear_schedule_sms_log_after_days');
	if($days > 0){
		$date_till = date('Y-m-d 23:59:59',strtotime('-'.$days.' day'));
		
		$CI->db->where('schedule_date < "'.$date_till.'" and executed = 1');
		$result = $CI->db->get(db_prefix() . 'si_sms_schedule')->result_array();
		
		if($result){
			foreach($result as $row){
				$CI->si_sms_model->delete_schedule($row['id']);
			}
		}
	}
}	

//get all merge fields 
function si_sms_get_merge_fields($filter_by='')
{
	$merge_fields = array();
	$merge_fields['customer'] 	= '{contact_firstname}, {contact_lastname}, {contact_email}, {contact_phonenumber}, {contact_title}, {client_company}, {client_phonenumber}, {client_country}, {client_city}, {client_zip}, {client_state}, {client_address}, {client_vat_number}';
		
	$merge_fields['lead'] 		= '{lead_name}, {lead_email}, {lead_position}, {lead_company}, {lead_country}, {lead_zip}, {lead_city}, {lead_state}, {lead_address}, {lead_assigned}, {lead_status}, {lead_source}, {lead_phonenumber}, {lead_website}, {lead_link}, {lead_description}, {lead_public_form_url}, {lead_public_consent_url}';
		
	$merge_fields['staff'] 		= '{staff_firstname}, {staff_lastname}, {staff_email}';
	
	if($filter_by!='' && isset($merge_fields[$filter_by]))
		return $merge_fields[$filter_by];
	else
		return $merge_fields;
}

/**
 * Common function used to select task relation's Client ID
 * @return string
 */
function si_sms_tasks_rel_client_id_select_query()
{
    return '(CASE rel_type
        WHEN "contract" THEN (SELECT client FROM ' . db_prefix() . 'contracts WHERE ' . db_prefix() . 'contracts.id = ' . db_prefix() . 'tasks.rel_id)
        WHEN "estimate" THEN (SELECT clientid FROM ' . db_prefix() . 'estimates WHERE ' . db_prefix() . 'estimates.id = ' . db_prefix() . 'tasks.rel_id)
        WHEN "proposal" THEN (SELECT rel_id FROM ' . db_prefix() . 'proposals WHERE ' . db_prefix() . 'proposals.rel_type = "customer" and ' . db_prefix() . 'proposals.id = ' . db_prefix() . 'tasks.rel_id)
        WHEN "invoice" THEN (SELECT clientid FROM ' . db_prefix() . 'invoices WHERE ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'tasks.rel_id)
        WHEN "ticket" THEN (SELECT userid FROM ' . db_prefix() . 'tickets WHERE ' . db_prefix() . 'tickets.ticketid=' . db_prefix() . 'tasks.rel_id)
        WHEN "lead" THEN (SELECT client_id FROM ' . db_prefix() . 'leads WHERE ' . db_prefix() . 'leads.id=' . db_prefix() . 'tasks.rel_id)
        WHEN "customer" THEN (SELECT userid FROM ' . db_prefix() . 'clients WHERE ' . db_prefix() . 'clients.userid=' . db_prefix() . 'tasks.rel_id)
        WHEN "project" THEN (SELECT clientid FROM ' . db_prefix() . 'projects WHERE ' . db_prefix() . 'projects.id=' . db_prefix() . 'tasks.rel_id)
        WHEN "expense" THEN (SELECT clientid FROM ' . db_prefix() . 'expenses JOIN ' . db_prefix() . 'expenses_categories ON ' . db_prefix() . 'expenses_categories.id = ' . db_prefix() . 'expenses.category WHERE ' . db_prefix() . 'expenses.id=' . db_prefix() . 'tasks.rel_id)
        ELSE NULL
        END)';
}