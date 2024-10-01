<?php
defined('BASEPATH') or exit('No direct script access allowed');
if(!$CI->db->table_exists(db_prefix() . 'si_lead_followup_schedule')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . "si_lead_followup_schedule` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(100) NOT NULL,
	`status` int(11) NOT NULL DEFAULT '0',
	`source` int(11) NOT NULL DEFAULT '0',
	`filter_by` varchar(25) NOT NULL,
	`send_sms` int(11) NOT NULL DEFAULT '0',
	`send_email` int(11) NOT NULL DEFAULT '0',
	`content` text NULL,
	`email_content` text NULL,
	`dlt_template_id_key` varchar(100) NOT NULL DEFAULT '',
	`dlt_template_id_value` varchar(100) NOT NULL DEFAULT '',
	`staff_id` int(11) NOT NULL DEFAULT '0',
	`schedule_days` int(11) NOT NULL DEFAULT '1',
	`schedule_hour` int(11) NOT NULL DEFAULT '12',
	`last_executed` datetime NULL,
	`dateadded` datetime NOT NULL,
	PRIMARY KEY (`id`),
	KEY `staff_id` (`staff_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}
if(!$CI->db->table_exists(db_prefix() . 'si_lead_followup_schedule_rel')) {
	$CI->db->query('CREATE TABLE `' . db_prefix() . "si_lead_followup_schedule_rel` (
	`schedule_id` int(11) NOT NULL DEFAULT '0',
	`rel_id` int(11) NOT NULL DEFAULT '0',
	`comment` text NULL,
	`sent_sms` int(11) NOT NULL DEFAULT '0',
	`sent_email` int(11) NOT NULL DEFAULT '0',
	`dateadded` datetime NOT NULL,
	KEY `schedule_id` (`schedule_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}
#add in settings
add_option(SI_LEAD_FOLLOWUP_MODULE_NAME.'_activated',1);
add_option(SI_LEAD_FOLLOWUP_MODULE_NAME.'_activation_code',1);
add_option('sms_trigger_'.SI_LEAD_FOLLOWUP_MODULE_NAME.'_custom_sms','');
add_option(SI_LEAD_FOLLOWUP_MODULE_NAME.'_trigger_schedule_sms_last_run',date('Y-m-d H:i:s',strtotime('-1 hour')));
add_option(SI_LEAD_FOLLOWUP_MODULE_NAME.'_clear_schedule_sms_log_after_days',30);

//create email templates
create_email_template('Email Lead follow-up!', 
'<span style=\"font-size: 12pt;\"> Hello {lead_name}, </span><br /><br /><span style=\"font-size: 12pt;\"><strong>This email a followup Email from {companyname}.<br/><br/>This email is regarding {lead_description}.</strong></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards</span><br /><br /><span style=\"font-size: 12pt;\">{email_signature}</span>',
'si_lead_followup', 'Follow-up Email', 'si-lead-followup-lead-followup-email');