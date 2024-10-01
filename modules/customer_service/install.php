<?php
defined('BASEPATH') or exit('No direct script access allowed');

add_option("customer_service_display_on_portal", 1, 1);
add_option("customer_service_business_from_hours", '08:00', 1);
add_option("customer_service_business_to_hours", '17:00', 1);
/*0 is monday, 6 is sunday*/
add_option("customer_service_business_days", '0,1,2,3,4,5,6', 1);

add_option("cs_sla_prefix", '#SLA_', 1);
add_option("cs_sla_number", 1, 1);
add_option("cs_kpi_prefix", '#KPI_', 1);
add_option("cs_kpi_number", 1, 1);
add_option("cs_ticket_category_prefix", '#TICKETCATEGORY_', 1);
add_option("cs_ticket_category_number", 1, 1);
add_option("cs_ticket_prefix", '#TICKET_', 1);
add_option("cs_ticket_number", 1, 1);
add_option("cs_mail_scan_from_departments", '', 1);
add_option("cs_workflow_prefix", '#WORKFLOW_', 1);
add_option("cs_workflow_number", 1, 1);
add_option("cs_support_term_condition", '', 1);


if (!$CI->db->table_exists(db_prefix() . "cs_spam_filters")) {
	$CI->db->query("CREATE TABLE `" . db_prefix() . "cs_spam_filters` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`type` TEXT NULL COMMENT 'sender, subject, phrase',
		`rel_type` TEXT NULL COMMENT 'blocked, allowed',
		`value` text NULL,
		`status` TEXT COMMENT 'enabled, disabled',
		`datecreated` datetime NULL,
		`staffid` int(11) NULL,

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->table_exists(db_prefix() . "cs_service_level_agreements")) {
	$CI->db->query("CREATE TABLE `" . db_prefix() . "cs_service_level_agreements` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`code` TEXT NULL,
		`name` TEXT NULL,
		`status` TEXT NULL COMMENT 'enabled, disabled',
		`grace_period` DECIMAL(15,2) NULL DEFAULT '0.00',
		`over_due_warning_alert` TEXT NULL COMMENT 'enabled, disabled',
		`event` TEXT NULL COMMENT 'fist_response, close',
		`breach_action` TEXT NULL COMMENT 'trigger_an_email, increase_the_priority',
		`breach_action_value` TEXT NULL,
		`breach_action_agent_manager` TEXT NULL,
		`hours_of_operation` TEXT NULL COMMENT 'full_support, business_hours',
		`admin_note` TEXT NULL,

		`datecreated` datetime NULL,
		`dateupdated` datetime NULL,
		`staffid` int(11) NULL,

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->table_exists(db_prefix() . "cs_service_level_agreement_warnings")) {
	$CI->db->query("CREATE TABLE `" . db_prefix() . "cs_service_level_agreement_warnings` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`service_level_agreement_id` INT(11),
		`level` DECIMAL(15,2) NULL DEFAULT '0.00',
		`action` TEXT NULL COMMENT 'trigger_an_email, increase_the_priority',
		`action_value` TEXT NULL,
		`agent_manager` TEXT NULL,
		`order_number` DECIMAL(15,0) DEFAULT '0',

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->table_exists(db_prefix() . "cs_kpis")) {
	$CI->db->query("CREATE TABLE `" . db_prefix() . "cs_kpis` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`code` TEXT NULL,
		`name` TEXT NULL,
		`first_response_time` INT(11) NULL,
		`first_response_time_measure` TEXT NULL COMMENT 'seconds, minutes, hours, days',
		`average_resolution_time` INT(11) NULL,
		`average_resolution_time_measure` TEXT NULL COMMENT 'seconds, minutes, hours, days',
		`average_handle_time` INT(11) NULL,
		`average_handle_time_measure` TEXT NULL COMMENT 'seconds, minutes, hours, days',
		`number_of_tickets` INT(11) NULL,
		`number_of_resolved_tickets` INT(11) NULL,
		`number_of_tickets_by_medium` INT(11) NULL,
		`escalation_rate` INT(11) NULL,
		`customer_satisfaction_score` INT(11) NULL,
		`datecreated` datetime NULL,
		`staffid` int(11) NULL,
		`status` TEXT COMMENT 'enabled, disabled',

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->table_exists(db_prefix() . "cs_ticket_categories")) {
	$CI->db->query("CREATE TABLE `" . db_prefix() . "cs_ticket_categories` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`code` TEXT NULL,
		`category_name` TEXT NULL,
		`status` TEXT  NULL COMMENT 'enabled, disabled',
		`type` TEXT NULL COMMENT 'public, private',
		`priority` TEXT NULL COMMENT 'low, normal, high, critical',
		`work_flow_id` INT(11) NULL,
		`department_id` INT(11) NULL,
		`custom_form_id` INT(11) NULL,
		`thank_you_page_id` INT(11) NULL,
		`auto_response` text NULL COMMENT 'enabled, disabled',

		`datecreated` datetime NULL,
		`dateupdated` datetime NULL,
		`staffid` int(11) NULL,

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->table_exists(db_prefix() . "cs_work_flows")) {
	$CI->db->query("CREATE TABLE `" . db_prefix() . "cs_work_flows` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`code` TEXT NULL,
		`workflow_name` TEXT NULL,
		`status` TEXT  NULL COMMENT 'enabled, disabled',
		`workflow` LONGTEXT NULL,
		`datecreated` datetime NULL,
		`dateupdated` datetime NULL,
		`staffid` int(11) NULL,

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->table_exists(db_prefix() . "cs_tickets_pipe_logs")) {
	$CI->db->query("CREATE TABLE `" . db_prefix() . "cs_tickets_pipe_logs` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`date` datetime NOT NULL,
		`email_to` varchar(100) NOT NULL,
		`name` varchar(191) NOT NULL,
		`subject` varchar(191) NOT NULL,
		`message` mediumtext NOT NULL,
		`email` varchar(100) NOT NULL,
		`status` varchar(100) NOT NULL,
		`ticket_id` INT(11) NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->table_exists(db_prefix() . "cs_ticket_timeline_logs")) {
	$CI->db->query("CREATE TABLE `" . db_prefix() . "cs_ticket_timeline_logs` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`rel_id` int NULL ,
		`rel_type` varchar(100) NULL ,
		`description` mediumtext NULL,
		`additional_data` text NULL,
		`date` datetime NULL,
		`staffid` int(11) NULL,
		`full_name` varchar(100) NULL,
		`from_date` DATETIME NULL ,
    `to_date` DATETIME NULL ,
    `duration` DECIMAL(15,2) DEFAULT '0',
    `created_type` VARCHAR(200) NULL DEFAULT 'System',

		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->table_exists(db_prefix() . "cs_tickets")) {
	$CI->db->query("CREATE TABLE `" . db_prefix() . "cs_tickets` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`created_id` int(11) NULL,
		`created_type` VARCHAR(20) NULL DEFAULT 'staff',
		`client_id` int(11) NULL,
		`invoice_id` int(11) NULL,
		`item_id` int(11) NULL,
		`ticket_source` TEXT NULL,
		`category_id` INT(11) NULL,
		`department_id` INT(11) NULL,
		`assigned_id` INT(11) NULL,
		`sla_id` INT(11) NULL,
		`time_spent` DECIMAL(15,2) NULL,
		`due_date` datetime NULL,
		`code` TEXT NULL,
		`issue_summary` TEXT NULL,
		`priority_level` TEXT NULL,
		`ticket_type` TEXT NULL,
		`internal_note` TEXT NULL,

		`last_message_time` datetime NULL,
		`last_response_time` datetime NULL,
		`first_reply_time` datetime NULL,
		`last_update_time` datetime NULL,
		`resolution` LONGTEXT NULL,
		`status` TEXT NULL,

		`datecreated` datetime NULL,
		`dateupdated` datetime NULL,
		`staffid` int(11) NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->table_exists(db_prefix() . "cs_ticket_workflows")) {
	$CI->db->query("CREATE TABLE `" . db_prefix() . "cs_ticket_workflows` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`ticket_id` int(11) NULL,
		`workflow_id` int(11) NULL,
		`workflow` LONGTEXT NULL,

		`datecreated` datetime NULL,
		`dateupdated` datetime NULL,
		`staffid` int(11) NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->table_exists(db_prefix() . "cs_ticket_action_post_replies")) {
	$CI->db->query("CREATE TABLE `" . db_prefix() . "cs_ticket_action_post_replies` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`ticket_id` int(11) NULL,
		`ticket_workflow_id` int(11) NULL,
		`to_staff_id` INT(11) NULL,
		`to_email` TEXT NULL,
		`cc_email` TEXT NULL,
		`collaborators_email` TEXT NULL,
		`response` TEXT NULL,
		`ticket_status` TEXT NULL COMMENT 'Close on reply if your response will complete all possible work on the ticket',
		`resolution` TEXT NULL COMMENT 'Set Reply as Resolution if you want the message entered fix issue',

		`datecreated` datetime NULL,
		`dateupdated` datetime NULL,
		`staffid` int(11) NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->table_exists(db_prefix() . "cs_ticket_action_post_internal_notes")) {
	$CI->db->query("CREATE TABLE `" . db_prefix() . "cs_ticket_action_post_internal_notes` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`ticket_id` int(11) NULL,
		`ticket_workflow_id` int(11) NULL,
		`note_title` TEXT NULL,
		`note_details` TEXT NULL,
		`ticket_status` TEXT NULL COMMENT 'select workflow progess Resolved Closed',
		`resolution` TEXT NULL COMMENT 'Set Reply as Resolution if you want the message entered fix issue',

		`datecreated` datetime NULL,
		`dateupdated` datetime NULL,
		`staffid` int(11) NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->table_exists(db_prefix() . "cs_ticket_action_change_departments")) {
	$CI->db->query("CREATE TABLE `" . db_prefix() . "cs_ticket_action_change_departments` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`ticket_id` int(11) NULL,
		`ticket_workflow_id` int(11) NULL,
		`department_id` TEXT NULL,
		`comment` TEXT NULL,

		`datecreated` datetime NULL,
		`dateupdated` datetime NULL,
		`staffid` int(11) NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->table_exists(db_prefix() . "cs_ticket_action_reassign_tickets")) {
	$CI->db->query("CREATE TABLE `" . db_prefix() . "cs_ticket_action_reassign_tickets` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`ticket_id` int(11) NULL,
		`ticket_workflow_id` int(11) NULL,
		`assignee_id` INT(11) NULL,
		`comment` TEXT NULL,

		`datecreated` datetime NULL,
		`dateupdated` datetime NULL,
		`staffid` int(11) NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->table_exists(db_prefix() . 'cs_ticket_flows_logs')) {
    $CI->db->query('CREATE TABLE ' . db_prefix() . "cs_ticket_flows_logs (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `ticket_id` INT(11) NOT NULL,
      `node_id` INT(11) NOT NULL,
      `lead_id` INT(11) NOT NULL,
      `output` TEXT NULL,
      `client_id` INT(11) NULL,
      `stage_status` VARCHAR(100) NULL COMMENT 'open inprogress waiting_reply_from_customer resolved closed',
      `date_start` datetime NULL,
      `date_end` datetime NULL,

      `dateadded` DATETIME NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('created_type' ,db_prefix() . 'cs_ticket_timeline_logs')) { 
  $CI->db->query('ALTER TABLE `' . db_prefix() . "cs_ticket_timeline_logs`
    ADD COLUMN `created_type` VARCHAR(200) NULL DEFAULT 'System'
  ;");
}

if (!$CI->db->field_exists('sla_id' ,db_prefix() . 'cs_ticket_categories')) { 
  $CI->db->query('ALTER TABLE `' . db_prefix() . "cs_ticket_categories`
    ADD COLUMN 	`sla_id` INT(11) NULL

  ;");
}

if (!$CI->db->field_exists('created_type' ,db_prefix() . 'cs_ticket_action_post_internal_notes')) { 
  $CI->db->query('ALTER TABLE `' . db_prefix() . "cs_ticket_action_post_internal_notes`
    ADD COLUMN 	`created_type` VARCHAR(20) NULL DEFAULT 'staff'

  ;");
}

if (!$CI->db->field_exists('category_default' ,db_prefix() . 'cs_ticket_categories')) { 
	$CI->db->query('ALTER TABLE `' . db_prefix() . "cs_ticket_categories`
		ADD COLUMN 	`category_default` INT(11) NULL DEFAULT '0'

		;");
}
if (!$CI->db->field_exists('ticket_id' ,db_prefix() . 'cs_tickets_pipe_logs')) { 
	$CI->db->query('ALTER TABLE `' . db_prefix() . "cs_tickets_pipe_logs`
		ADD COLUMN 	`ticket_id` INT(11) NULL

		;");
}

if (!$CI->db->field_exists('client_rating' ,db_prefix() . 'cs_tickets')) { 
	$CI->db->query('ALTER TABLE `' . db_prefix() . "cs_tickets`
		ADD COLUMN 	`client_rating` INT(11) NULL DEFAULT 0,
		ADD COLUMN 	`client_feedback` TEXT NULL

		;");
}

if (!$CI->db->field_exists('sla_id' ,db_prefix() . 'cs_work_flows')) { 
	$CI->db->query('ALTER TABLE `' . db_prefix() . "cs_work_flows`
		ADD COLUMN 	`sla_id` INT(11) NULL DEFAULT 0,
		ADD COLUMN 	`kpi_id` INT(11) NULL DEFAULT 0

		;");
}
if (!$CI->db->field_exists('workflow_id' ,db_prefix() . 'cs_tickets')) { 
	$CI->db->query('ALTER TABLE `' . db_prefix() . "cs_tickets`
		ADD COLUMN 	`workflow_id` INT(11) NULL,
		ADD COLUMN 	`kpi_id` INT(11) NULL

		;");
}
// V 1.0.1
if (!$CI->db->field_exists('item_description' ,db_prefix() . 'cs_tickets')) { 
	$CI->db->query('ALTER TABLE `' . db_prefix() . "cs_tickets`
		ADD COLUMN 	`item_description` TEXT NULL

		;");
}
