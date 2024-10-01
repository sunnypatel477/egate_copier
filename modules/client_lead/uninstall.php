<?php defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

$unlink_files = array(
        APPPATH . 'views/admin/tables/my_leads.php',
        APPPATH . 'views/admin/invoices/my_record_payment_template.php',
        APPPATH . 'views/admin/invoices/my_invoice_preview_template.php',
        APPPATH . 'views/admin/tables/my_clients.php',
        APPPATH . 'views/admin/clients/my_manage.php',
        APPPATH . 'views/admin/clients/groups/my_profile.php',
        APPPATH . 'views/admin/proposals/my_proposal.php',
        APPPATH . 'views/admin/tables/my_proposals.php.php',
        APPPATH . 'views/admin/proposals/my_list_template.php',
        APPPATH . 'views/admin/estimates/my_estimate.php',
        APPPATH . 'views/admin/estimates/my_estimate_template.php',
        APPPATH . 'views/admin/estimates/my_table_html.php',
        APPPATH . 'views/admin/tables/my_estimates.php',
        APPPATH . 'views/themes/perfex/views/my_viewproposal.php',
        APPPATH . 'views/admin/proposals/my_proposals_preview_template.php',
        APPPATH . 'views/admin/estimates/my_estimate_preview_template.php',
        APPPATH . 'views/admin/leads/my_manage_leads.php',
        APPPATH . 'views/admin/leads/my_profile.php',
        APPPATH . 'views/admin/payments/my_batch_payment_modal.php',
        APPPATH . 'views/admin/tables/my_payments.php',
        APPPATH . 'views/admin/payments/my_table_html.php',

);

foreach ($unlink_files as $file) {
    if (file_exists($file)) {
        unlink($file);
    }
}