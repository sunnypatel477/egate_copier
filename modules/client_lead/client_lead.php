<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Egate
Description: Invoice Payment Attachment & Lead , Customer Changes
Version: 1.1.0
Perfex Version: 3.1.6
Requires at least: 2.3.*
Author: Egate
*/

define('CLIENT_LEAD_MODULE', 'client_lead');

define('CLIENT_LEAD_MODULE_TAB', 'lead');

register_language_files(CLIENT_LEAD_MODULE, [CLIENT_LEAD_MODULE]);

register_activation_hook(CLIENT_LEAD_MODULE, 'client_lead_activation_hook');

hooks()->add_action('admin_init', 'client_lead_init_menu_items');

hooks()->add_action('app_admin_footer', 'client_lead_load_js');

$CI = &get_instance();
$CI->load->helper(CLIENT_LEAD_MODULE . '/client_lead');

function client_lead_init_menu_items() {

	$CI = &get_instance();
	if (has_permission('client_lead_module', '', 'view')) {
		$CI->app_menu->add_sidebar_menu_item('client_lead_module', [
            'slug' => 'client_lead_module',
			'name' => _l('current_client_lead'),
			'icon' => 'fa fa-tty menu-icon',
            'href' => admin_url('client_lead/leads'),
			'position' => 10,
		]);
	}

	App_table::register(
		App_table::new('table-client-leads', module_views_path(CLIENT_LEAD_MODULE, 'tables/leads'))->setDbTableName('leads')->customfieldable('leads')
	  );
}

function client_lead_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

function client_lead_load_js()
{
    echo '<script src="' . module_dir_url(CLIENT_LEAD_MODULE, 'asset/js/client_custom.js') . '"></script>';
}

// Register deactivation module hook
register_deactivation_hook(CLIENT_LEAD_MODULE, 'client_lead__deactivation_hook');

// Register uninstall module hook
register_uninstall_hook(CLIENT_LEAD_MODULE, 'client_lead__uninstall_hook');

function client_lead__deactivation_hook()
{
  require_once(__DIR__ . '/uninstall.php');
}

function client_lead__uninstall_hook()
{
  require_once(__DIR__ . '/uninstall.php');
}


$CI->app_tabs->add_customer_profile_tab(CLIENT_LEAD_MODULE, [
	'name'     => _l(CLIENT_LEAD_MODULE_TAB),
	'icon'     => 'fa-regular fa-file-lines',
	'view'     => 'client_lead/admin/client_lead/groups/lead_tab',
	'position' => 46,
	'badge'    => [],
]);



hooks()->add_filter('customers_profile_tab_badge', function ($data) use ($CI) {
	if ($data['feature'] === CLIENT_LEAD_MODULE) {
		$customerid = $data['customer_id'];

		$leads = $CI->db->where('existing_client_id', $customerid)->where('status !=', CLOSED_WON)->where('status !=', CLOSED_LOST)->get(db_prefix() . 'leads')
                            ->result_array();
        $num_leads = count($leads);

	
		if ($num_leads > 0) {
			$badge = [
				'value' => $num_leads,
				'color' => 'default',
				'type'  => 'default',
			];
			$data['badge'] = $badge;
		}
	}
	return $data;
});

define('CLOSED_WON', 5);
define('CLOSED_LOST', 6);
define('TASK_PENDING', 5);
?>




