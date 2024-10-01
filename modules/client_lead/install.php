<?php

defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

//============================================= my_leads.php
$lead_table_files_path = APPPATH . 'views/admin/tables/my_leads.php';
$module_table_lead_files_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/my_leads.php';
if (!file_exists($lead_table_files_path)) {
    copy($module_table_lead_files_path, $lead_table_files_path);
} // Done

//============================================= my_leads.php
$my_manage_leads_files_path = APPPATH . 'views/admin/leads/my_manage_leads.php';
$module_my_manage_leads_files_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/lead/my_manage_leads.php';
if (!file_exists($my_manage_leads_files_path)) {
    copy($module_my_manage_leads_files_path, $my_manage_leads_files_path);
} // Done


//============================================= my_record_payment_template.php
$payment_files_path = APPPATH . 'views/admin/invoices/my_record_payment_template.php';
$module_payment_files_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/my_record_payment_template.php';
if (!file_exists($payment_files_path)) {
    copy($module_payment_files_path, $payment_files_path);
} // Done

//=============================================my_invoice_preview_template.php
$payment_files_tab_path = APPPATH . 'views/admin/invoices/my_invoice_preview_template.php';
$module_payment_files_tab_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/my_invoice_preview_template.php';
if (!file_exists($payment_files_tab_path)) {
    copy($module_payment_files_tab_path, $payment_files_tab_path);
} // Done

//============================================= my_clients.php
$customer_table_files_path = APPPATH . 'views/admin/tables/my_clients.php';
$module_table_customer_files_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/my_clients.php';
if (!file_exists($customer_table_files_path)) {
    copy($module_table_customer_files_path, $customer_table_files_path);
} // Done

//============================================= my_manage.php
$customer_manage_files_path = APPPATH . 'views/admin/clients/my_manage.php';
$module_table_manage_files_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/my_manage.php';
if (!file_exists($customer_manage_files_path)) {
    copy($module_table_manage_files_path, $customer_manage_files_path);
} // Done

//============================================= my_profile.php

$customer_profile_path = APPPATH . 'views/admin/clients/groups/my_profile.php';
$module_profile_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/groups/my_profile.php';
if (!file_exists($customer_profile_path)) {
    copy($module_profile_path, $customer_profile_path);
} // Done

//============================================= my_profile.php
$lead_customer_profile_path = APPPATH . 'views/admin/leads/my_profile.php';
$module_lead_profile_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/lead/my_profile.php';
if (!file_exists($lead_customer_profile_path)) {
    copy($module_lead_profile_path, $lead_customer_profile_path);
} // Done



//============================================= my_proposal.php
$proposal_files_path = APPPATH . 'views/admin/proposals/my_proposal.php';
$module_proposal_files_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/my_proposal.php';
if (!file_exists($proposal_files_path)) {
    copy($module_proposal_files_path, $proposal_files_path);
} // Done

//============================================= my_proposal.php
$proposal_table_files_path = APPPATH . 'views/admin/tables/my_proposals.php';
$module_proposal_table_files_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/table/my_proposals.php';
if (!file_exists($proposal_table_files_path)) {
    copy($module_proposal_table_files_path, $proposal_table_files_path);
} // Done

//============================================= my_list_template.php
$proposal_table_my_list_template_files_path = APPPATH . 'views/admin/proposals/my_list_template.php';
$module_proposal_table_my_list_template_files_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/my_list_template.php';
if (!file_exists($proposal_table_my_list_template_files_path)) {
    copy($module_proposal_table_my_list_template_files_path, $proposal_table_my_list_template_files_path);
} // Done

//============================================= my_estimate.php
$estimate_files_path = APPPATH . 'views/admin/estimates/my_estimate.php';
$module_estimate_files_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/my_estimate.php';
if (!file_exists($estimate_files_path)) {
    copy($module_estimate_files_path, $estimate_files_path);
} // Done

//============================================= my_estimate_template.php
$estimate_files_path = APPPATH . 'views/admin/estimates/my_estimate_template.php';
$module_estimate_files_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/my_estimate_template.php';
if (!file_exists($estimate_files_path)) {
    copy($module_estimate_files_path, $estimate_files_path);
} // Done


//============================================= my_table_html.php
$estimate_table_files_path = APPPATH . 'views/admin/estimates/my_table_html.php';
$module_estimate_table_files_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/estimates/my_table_html.php';
if (!file_exists($estimate_table_files_path)) {
    copy($module_estimate_table_files_path, $estimate_table_files_path);
} // Done

//============================================= my_table_html.php
$estimate_table_templete_files_path = APPPATH . 'views/admin/tables/my_estimates.php';
$module_estimate_table_templete_files_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/table/my_estimates.php';
if (!file_exists($estimate_table_templete_files_path)) {
    copy($module_estimate_table_templete_files_path, $estimate_table_templete_files_path);
} // Done

//============================================= my_viewproposal.php
$proposal_viewproposal_files_path = APPPATH . 'views/themes/perfex/views/my_viewproposal.php';
$module_proposal_viewproposal_files_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/my_viewproposal.php';
if (!file_exists($proposal_viewproposal_files_path)) {
    copy($module_proposal_viewproposal_files_path, $proposal_viewproposal_files_path);
} // Done

//============================================= my_proposals_preview_template.php
$roposals_preview_template_files_path = APPPATH . 'views/admin/proposals/my_proposals_preview_template.php';
$module_roposals_preview_template_files_path = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/my_proposals_preview_template.php';
if (!file_exists($roposals_preview_template_files_path)) {
    copy($module_roposals_preview_template_files_path, $roposals_preview_template_files_path);
} // Done

//============================================= my_estimate_preview_template.php
$estimate_preview_template = APPPATH . 'views/admin/estimates/my_estimate_preview_template.php';
$module_estimate_preview_template = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/my_estimate_preview_template.php';
if (!file_exists($estimate_preview_template)) {
    copy($module_estimate_preview_template, $estimate_preview_template);
} // Done


//============================================= my_batch_payment_modal.php
$batch_payment_modal = APPPATH . 'views/admin/payments/my_batch_payment_modal.php';
$module_batch_payment_modal = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/payments/my_batch_payment_modal.php';
if (!file_exists($batch_payment_modal)) {
    copy($module_batch_payment_modal, $batch_payment_modal);
} // Done

//============================================= my_table_html.php
$my_payment_table_html = APPPATH . 'views/admin/payments/my_table_html.php';
$module_my_payment_table_html = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/payments/my_table_html.php';
if (!file_exists($my_payment_table_html)) {
    copy($module_my_payment_table_html, $my_payment_table_html);
} // Done

//============================================= my_payments.php
$my_payments_table = APPPATH . 'views/admin/tables/my_payments.php';
$module_my_payments_table = module_dir_path(CLIENT_LEAD_MODULE) . 'system_changes/payments/tables/my_payments.php';
if (!file_exists($my_payments_table)) {
    copy($module_my_payments_table, $my_payments_table);
} // Done


if (!$CI->db->field_exists('invoice_hard_copy', db_prefix() . 'invoicepaymentrecords')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "invoicepaymentrecords`
        ADD COLUMN `invoice_hard_copy` VARCHAR(200) NULL
        ;");
}

if (!$CI->db->field_exists('payment_prof', db_prefix() . 'invoicepaymentrecords')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "invoicepaymentrecords`
        ADD COLUMN `payment_prof` VARCHAR(200) NULL
        ;");
}

if (!$CI->db->field_exists('officialreceipt', db_prefix() . 'invoicepaymentrecords')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "invoicepaymentrecords`
        ADD COLUMN `officialreceipt` VARCHAR(200) NULL
        ;");
}

if (!$CI->db->field_exists('taxdeduction2307', db_prefix() . 'invoicepaymentrecords')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "invoicepaymentrecords`
        ADD COLUMN `taxdeduction2307` VARCHAR(200) NULL
        ;");
}

if (!$CI->db->field_exists('existing_client_id', db_prefix() . 'leads')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "leads`
        ADD COLUMN `existing_client_id` int(10) NULL
        ;");
}

if (!$CI->db->field_exists('inquiry_about', db_prefix() . 'leads')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "leads`
        ADD COLUMN `inquiry_about` VARCHAR(200) NULL
        ;");
}
if (!$CI->db->field_exists('proposal_document', db_prefix() . 'proposals')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "proposals`
        ADD COLUMN `proposal_document` VARCHAR(200) NULL
        ;");
}

if (!$CI->db->field_exists('proposal_attachment', db_prefix() . 'proposals')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "proposals`
        ADD COLUMN `proposal_attachment` VARCHAR(200) NULL
        ;");
}


if (!$CI->db->field_exists('estimate_document', db_prefix() . 'estimates')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "estimates`
        ADD COLUMN `estimate_document` VARCHAR(200) NULL
        ;");
}

if (!$CI->db->field_exists('estimate_attachment', db_prefix() . 'estimates')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "estimates`
        ADD COLUMN `estimate_attachment` VARCHAR(200) NULL
        ;");
}

if (!$CI->db->field_exists('bank', db_prefix() . 'invoicepaymentrecords')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . "invoicepaymentrecords`
        ADD COLUMN `bank` VARCHAR(200) NULL
        ;");
}

