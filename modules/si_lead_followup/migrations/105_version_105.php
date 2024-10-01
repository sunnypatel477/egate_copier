<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_105 extends App_module_migration
{
	public function up()
	{ 
		$CI = &get_instance();
		if(!$CI->db->field_exists('email_content',db_prefix() . 'si_lead_followup_schedule')) {
			$CI->db->query('ALTER TABLE `' . db_prefix() . 'si_lead_followup_schedule` CHANGE `content` `content` TEXT NULL,
							 ADD `email_content` text NULL AFTER `content`, 
							 ADD `send_sms` int(11) NOT NULL DEFAULT "0" AFTER `filter_by`, 
							 ADD `send_email` int(11) NOT NULL DEFAULT "0" AFTER `send_sms`');
		   	$CI->db->query('UPDATE `' . db_prefix() . 'si_lead_followup_schedule` SET send_sms = 1');//set all sent type as SMS for previously added schedules.
		}
		if(!$CI->db->field_exists('sent_sms',db_prefix() . 'si_lead_followup_schedule_rel')) {
			$CI->db->query('ALTER TABLE `' . db_prefix() . 'si_lead_followup_schedule_rel` ADD `sent_sms` int(11) NOT NULL DEFAULT "0" AFTER `comment`, 
			ADD `sent_email` int(11) NOT NULL DEFAULT "0" AFTER `sent_sms`');
		   	$CI->db->query('UPDATE `' . db_prefix() . 'si_lead_followup_schedule_rel` SET sent_sms = 1');//set all sent type as SMS for previously added schedules.
		}

		//create email templates
		create_email_template('Email Lead follow-up!', 
			'<span style=\"font-size: 12pt;\"> Hello {lead_name}, </span><br /><br /><span style=\"font-size: 12pt;\"><strong>This email a followup Email from {companyname}.<br/><br/>This email is regarding {lead_description}.</strong></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards</span><br /><br /><span style=\"font-size: 12pt;\">{email_signature}</span>',
			'si_lead_followup', 'Follow-up Email', 'si-lead-followup-lead-followup-email');   
	}
}