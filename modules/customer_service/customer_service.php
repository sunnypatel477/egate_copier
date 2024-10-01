<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Customer Service Management
Description: This module is a unified multiple-channel tool that helps you communicate with customers, organize and track tickets, and troubleshoot customer requests effectively to deliver better customer support service experiences.
Version: 1.0.0
Requires at least: 2.3.*
Author: GreenTech Solutions
Author URI: https://codecanyon.net/user/greentech_solutions
*/

define('CUSTOMER_SERVICE_MODULE_NAME', 'customer_service');
define('CUSTOMER_SERVICE_MODULE_UPLOAD_FOLDER', module_dir_path(CUSTOMER_SERVICE_MODULE_NAME, 'uploads'));

/*add folder upload link on here*/
define('CUSTOMER_SERVICE_PRODUCT_UPLOAD', module_dir_path(CUSTOMER_SERVICE_MODULE_NAME, 'uploads/products/'));

/*link view on here*/
define('CUSTOMER_SERVICE_PRINT_ITEM', 'modules/customer_service/uploads/print_item/');


hooks()->add_action('admin_init', 'customer_service_permissions');
hooks()->add_action('app_admin_head', 'customer_service_add_head_components');
hooks()->add_action('app_admin_footer', 'customer_service_load_js');
hooks()->add_action('app_search', 'customer_service_load_search');
hooks()->add_action('admin_init', 'customer_service_module_init_menu_items');

//cronjob import_imap_tickets
hooks()->add_action('before_cron_run', 'cs_import_imap_tickets');

/*email template*/
register_merge_fields('customer_service/merge_fields/customer_service_email_template_merge_fields');
hooks()->add_filter('other_merge_fields_available_for', 'customer_service_email_template_register_other_merge_fields');

define('VERSION_CUSTOMER_SERVICE', 100);
define('CUSTOMER_SERVICE_UPLOAD', module_dir_path(CUSTOMER_SERVICE_MODULE_NAME, 'uploads/post_replies/'));


/*add menu on client portal*/
hooks()->add_action('customers_navigation_end', 'init_customer_service_portal_menu');
hooks()->add_action('app_customers_portal_head', 'customer_service_portal_add_head_components');
hooks()->add_action('app_customers_portal_footer', 'customer_service_portal_add_footer_components');
hooks()->add_action('customer_service_init',CUSTOMER_SERVICE_MODULE_NAME.'_appint');
// hooks()->add_action('pre_activate_module', CUSTOMER_SERVICE_MODULE_NAME.'_preactivate');
// hooks()->add_action('pre_deactivate_module', CUSTOMER_SERVICE_MODULE_NAME.'_predeactivate');
/**
* Register activation module hook
*/
register_activation_hook(CUSTOMER_SERVICE_MODULE_NAME, 'customer_service_module_activation_hook');

function customer_service_module_activation_hook()
{
	$CI = &get_instance();
	require_once(__DIR__ . '/install.php');
}


/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(CUSTOMER_SERVICE_MODULE_NAME, [CUSTOMER_SERVICE_MODULE_NAME]);


$CI = & get_instance();
$CI->load->helper(CUSTOMER_SERVICE_MODULE_NAME . '/customer_service');

/**
 * Init goals module menu items in setup in admin_init hook
 * @return null
 */
function customer_service_module_init_menu_items()
{   
	$CI = &get_instance();

	/*add menu on here*/

	if(has_permission('customer_service','','view') ){

		$CI->app_menu->add_sidebar_menu_item('customer_service', [
			'name'     => _l('customer_service_name'),
			'icon'     => 'fa fa-user-secret', 
			'position' => 5,
		]);
	}

	if(has_permission('customer_service','','view')){
		$CI->app_menu->add_sidebar_children_item('customer_service', [
			'slug'     => 'customer_service_dashboard',
			'name'     => _l('cs_dashboard'),
			'icon'     => 'fa fa-dashboard',
			'href'     => admin_url('customer_service/dashboard'),
			'position' => 1,
		]);
	}

	if(has_permission('customer_service','','view')){
		$CI->app_menu->add_sidebar_children_item('customer_service', [
			'slug'     => 'customer_service_sla_manage',
			'name'     => _l('cs_slas'),
			'icon'     => 'fa fa-list-alt',
			'href'     => admin_url('customer_service/sla_manage'),
			'position' => 1,
		]);
	}

	if(has_permission('customer_service','','view')){
		$CI->app_menu->add_sidebar_children_item('customer_service', [
			'slug'     => 'customer_service_kpi_manage',
			'name'     => _l('cs_kpis'),
			'icon'     => 'fa fa-check-square',
			'href'     => admin_url('customer_service/kpi_manage'),
			'position' => 1,
		]);
	}

	if(has_permission('customer_service','','view')){
		$CI->app_menu->add_sidebar_children_item('customer_service', [
			'slug'     => 'customer_service_work_flows',
			'name'     => _l('cs_work_flows'),
			'icon'     => 'fa-brands fa-stack-overflow',
			'href'     => admin_url('customer_service/work_flows'),
			'position' => 1,
		]);
	}
	if(has_permission('customer_service','','view')){
		$CI->app_menu->add_sidebar_children_item('customer_service', [
			'slug'     => 'customer_service_category_manage',
			'name'     => _l('cs_categories'),
			'icon'     => 'fa fa-list-alt',
			'href'     => admin_url('customer_service/category_manage'),
			'position' => 1,
		]);
	}


	if(has_permission('customer_service','','view')){
		$CI->app_menu->add_sidebar_children_item('customer_service', [
			'slug'     => 'customer_service_scanned_mail',
			'name'     => _l('cs_scanned_mails'),
			'icon'     => 'fa fa-envelope',
			'href'     => admin_url('customer_service/ticket_pipe_logs'),
			'position' => 1,
		]);
	}

	if(has_permission('customer_service','','view')){
		$CI->app_menu->add_sidebar_children_item('customer_service', [
			'slug'     => 'customer_service_ticket',
			'name'     => _l('cs_tickets'),
			'icon'     => 'fa fa-ticket',
			'href'     => admin_url('customer_service/tickets'),
			'position' => 1,
		]);
	}

	if(has_permission('customer_service','','view')){
		$CI->app_menu->add_sidebar_children_item('customer_service', [
			'slug'     => 'customer_service_setting',
			'name'     => _l('cs_settings'),
			'icon'     => 'fa fa-cog menu-icon',
			'href'     => admin_url('customer_service/setting?group=mail_scan_rule'),
			'position' => 10,
		]);
	}


}

	/**
	 * customer_service load js
	 */
	function customer_service_load_js(){    
		$CI = &get_instance();    
		$viewuri = $_SERVER['REQUEST_URI'];
		
		/*change this code*/
		if(!(strpos($viewuri, '/admin/customer_service') === false)){
			echo '<script src="' . module_dir_url(CUSTOMER_SERVICE_MODULE_NAME, 'assets/js/tinymce_init.js') .'?v=' . VERSION_CUSTOMER_SERVICE.'"></script>';
		}
		
		if (!(strpos($viewuri, 'admin/customer_service/add_edit_work_flow') === false) || !(strpos($viewuri, 'admin/customer_service/ticket_detail') === false)) {
			echo '<script src="' . module_dir_url(CUSTOMER_SERVICE_MODULE_NAME, 'assets/plugins/Drawflow-master/drawflow.js') . '?v=' . VERSION_CUSTOMER_SERVICE . '"></script>';
		}

		if(!(strpos($viewuri,'admin/customer_service/dashboard') === false)){

			echo '<script src="'.module_dir_url(CUSTOMER_SERVICE_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js').'?v=' . VERSION_CUSTOMER_SERVICE.'"></script>';
			echo '<script src="'.module_dir_url(CUSTOMER_SERVICE_MODULE_NAME, 'assets/plugins/highcharts/variable-pie.js').'?v=' . VERSION_CUSTOMER_SERVICE.'"></script>';
			echo '<script src="'.module_dir_url(CUSTOMER_SERVICE_MODULE_NAME, 'assets/plugins/highcharts/export-data.js').'?v=' . VERSION_CUSTOMER_SERVICE.'"></script>';
			echo '<script src="'.module_dir_url(CUSTOMER_SERVICE_MODULE_NAME, 'assets/plugins/highcharts/accessibility.js').'?v=' . VERSION_CUSTOMER_SERVICE.'"></script>';
			echo '<script src="'.module_dir_url(CUSTOMER_SERVICE_MODULE_NAME, 'assets/plugins/highcharts/exporting.js').'?v=' . VERSION_CUSTOMER_SERVICE.'"></script>';
			echo '<script src="'.module_dir_url(CUSTOMER_SERVICE_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js').'?v=' . VERSION_CUSTOMER_SERVICE.'"></script>';
		}
	}


	/**
	 * customer_service add head components
	 */
	function customer_service_add_head_components(){    
		$CI = &get_instance();
		$viewuri = $_SERVER['REQUEST_URI'];

		/*change this code*/
		
		if (!(strpos($viewuri, 'admin/customer_service/add_edit_work_flow') === false) || !(strpos($viewuri, 'admin/customer_service/ticket_detail') === false)) {
			echo '<link href="' . module_dir_url(CUSTOMER_SERVICE_MODULE_NAME, 'assets/plugins/Drawflow-master/drawflow.min.css') . '?v=' . VERSION_CUSTOMER_SERVICE . '"  rel="stylesheet" type="text/css" />';
			echo '<link href="' . module_dir_url(CUSTOMER_SERVICE_MODULE_NAME, 'assets/plugins/Drawflow-master/beautiful.css') . '?v=' . VERSION_CUSTOMER_SERVICE . '"  rel="stylesheet" type="text/css" />';
		}

		if (!(strpos($viewuri, 'admin/customer_service') === false) || !(strpos($viewuri, 'admin/customer_service/ticket_detail') === false)) {
			echo '<link href="' . module_dir_url(CUSTOMER_SERVICE_MODULE_NAME, 'assets/css/styles.css')  .'?v=' . VERSION_CUSTOMER_SERVICE. '"  rel="stylesheet" type="text/css" />'; 
			
		}

	}



	/**
	 * customer_service permissions
	 */
	function customer_service_permissions()
	{

		$capabilities = [];

		$capabilities['capabilities'] = [
			'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
			'create' => _l('permission_create'),
			'edit'   => _l('permission_edit'),
			'delete' => _l('permission_delete'),
		];

		
		register_staff_capabilities('customer_service', $capabilities, _l('customer_service_name'));

	}

	/**
	 * cs import imap tickets
	 * @param  [type] $manually 
	 * @return [type]           
	 */
	function cs_import_imap_tickets($manually)
	{
		$CI = &get_instance();

		$CI->load->model('customer_service/customer_service_model');
        $CI->customer_service_model->cs_auto_import_imap_tickets();
	}

	/**
	 * customer service email template register other merge_fields
	 * @param  [type] $for 
	 * @return [type]      
	 */
	function customer_service_email_template_register_other_merge_fields($for)
	{
		$for[] = 'customer_service_email_template';

		return $for;
	}

	/**
	 * init customer service portal menu
	 * @return [type] 
	 */
	function init_customer_service_portal_menu()
	{
		$item ='';
		if(is_client_logged_in()){
			if(get_option('customer_service_display_on_portal') == 1){
				$item .= '<li class="customers-nav-item">';
				$item .= '<a href="'.site_url('customer_service/customer_service_client/tickets').'">'._l("customer_service_name").'';        
				$item .= '</a>';
				$item .= '</li>';
			}
		}
		echo new_html_entity_decode($item);
	}

	function customer_service_portal_add_head_components() {
		$CI = &get_instance();
		$viewuri = $_SERVER['REQUEST_URI'];

		if(!(strpos($viewuri,'customer_service/customer_service_client') === false)){
			echo '<link href="' . module_dir_url(CUSTOMER_SERVICE_MODULE_NAME, 'assets/css/styles.css') . '?v=' . VERSION_CUSTOMER_SERVICE. '"  rel="stylesheet" type="text/css" />';
		}

	}

	/**
	 * warranty management portal add footer components
	 * @return [type] 
	 */
	function customer_service_portal_add_footer_components() {
		$CI = &get_instance();
		$viewuri = $_SERVER['REQUEST_URI'];

		if(!(strpos($viewuri,'customer_service/customer_service_client') === false)){
			echo '<script src="' . module_dir_url(CUSTOMER_SERVICE_MODULE_NAME, 'assets/js/client_portals/main.js') . '?v=' . VERSION_CUSTOMER_SERVICE . '"></script>';
		}
	}

/**
 * new html entity decode
 * @param  [type] $str 
 * @return [type]      
 */
if (!function_exists('new_html_entity_decode')) {
	function new_new_html_entity_decode($str){
		return new_html_entity_decode($str ?? '');
	}
}

function customer_service_appint(){
    $CI = & get_instance();    
    require_once 'libraries/gtsslib.php';
    $cs_api = new CustomerServiceLic();
    // $cs_gtssres = $cs_api->verify_license(true);    
    // if(!$cs_gtssres || ($cs_gtssres && isset($cs_gtssres['status']) && !$cs_gtssres['status'])){
    //      $CI->app_modules->deactivate(CUSTOMER_SERVICE_MODULE_NAME);
    //     set_alert('danger', "One of your modules failed its verification and got deactivated. Please reactivate or contact support.");
    //     redirect(admin_url('modules'));
    // }    
}

function customer_service_preactivate($module_name){
    if ($module_name['system_name'] == CUSTOMER_SERVICE_MODULE_NAME) {             
        require_once 'libraries/gtsslib.php';
        // $cs_api = new CustomerServiceLic();
        // $cs_gtssres = $cs_api->verify_license();          
        // if(!$cs_gtssres || ($cs_gtssres && isset($cs_gtssres['status']) && !$cs_gtssres['status'])){
        //      $CI = & get_instance();
        //     $data['submit_url'] = $module_name['system_name'].'/gtsverify/activate'; 
        //     $data['original_url'] = admin_url('modules/activate/'.CUSTOMER_SERVICE_MODULE_NAME); 
        //     $data['module_name'] = CUSTOMER_SERVICE_MODULE_NAME; 
        //     $data['title'] = "Module License Activation"; 
        //     echo $CI->load->view($module_name['system_name'].'/activate', $data, true);
        //     exit();
        // }        
    }
}

// function customer_service_predeactivate($module_name){
//     if ($module_name['system_name'] == CUSTOMER_SERVICE_MODULE_NAME) {
//         require_once 'libraries/gtsslib.php';
//         $cs_api = new CustomerServiceLic();
//         $cs_api->deactivate_license();
//     }
// }