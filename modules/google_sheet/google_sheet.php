<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Google Sheets
Module URI: https://codecanyon.net/item/google-sheets-module-for-perfex-crm-twoway-spreadsheets-synchronization/53297436
Description: Two-way Spreadsheets Synchronization between Perfex CRM and Google Sheets
Version: 1.0.0
Requires at least: 1.0.*
Author: Themesic Interactive
Author URI: https://1.envato.market/themesic
*/

define('GOOGLE_SHEET_MODULE_NAME', 'google_sheet');
define('GOOGLE_SHEET_MODULE', 'google_sheet');
$CI = &get_instance();
require_once __DIR__.'/vendor/autoload.php';

/**
 * Load the module helper
 */
$CI->load->helper(GOOGLE_SHEET_MODULE_NAME . '/google_sheet');
// modules\google_sheet\core\Apiinit::the_da_vinci_code(GOOGLE_SHEET_MODULE);
// modules\google_sheet\core\Apiinit::ease_of_mind(GOOGLE_SHEET_MODULE);

/**
 * Register activation module hook
 */
register_activation_hook(GOOGLE_SHEET_MODULE_NAME, 'google_sheet_activation_hook');

function google_sheet_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(GOOGLE_SHEET_MODULE_NAME, [GOOGLE_SHEET_MODULE_NAME]);

/**
 * Actions for inject the custom styles
 */
hooks()->add_action('admin_init', 'google_sheet_init_menu_items');
hooks()->add_action('admin_init', 'google_sheet_permissions');

/**
 * Init theme style module menu items in setup in admin_init hook
 * @return null
 */
function google_sheet_init_menu_items()
{
    if (staff_can('setting', 'google_sheet') || staff_can('view', 'google_sheet') || staff_can('create', 'google_sheet') || staff_can('edit', 'google_sheet') || staff_can('delete', 'google_sheet')) {
        $CI = &get_instance();

        /**
         * If the logged in user is administrator, add custom menu in Setup
         */
        $CI->app_menu->add_sidebar_menu_item('google-sheet', [
            'name'     => _l('google_sheet_google_sheets'),
            'icon'     => 'fa-solid fa-sheet-plastic',
            'collapse' => true,
            'position' => 65,
        ]);
        if (staff_can('setting', 'google_sheet')) {
            $CI->app_menu->add_sidebar_children_item('google-sheet', [
                'slug'     => 'google-sheet-settings',
                'name'     => _l('google_sheet_settings'),
                'href'     => admin_url('google_sheet/settings'),
                'position' => 10,
                'badge'    => [],
            ]);
        }
        $CI->app_menu->add_sidebar_children_item('google-sheet', [
            'slug'     => 'google-sheet-google-sheets',
            'name'     => _l('google_sheet_google_sheets'),
            'href'     => admin_url('google_sheet'),
            'position' => 10,
            'badge'    => [],
        ]);
    }
}

// hooks()->add_action('app_init', GOOGLE_SHEET_MODULE.'_actLib');
// function google_sheet_actLib()
// {
//     $CI = &get_instance();
//     $CI->load->library(GOOGLE_SHEET_MODULE.'/Google_sheet_aeiou');
//     $envato_res = $CI->google_sheet_aeiou->validatePurchase(GOOGLE_SHEET_MODULE);
//     if (!$envato_res) {
//         set_alert('danger', 'One of your modules failed its verification and got deactivated. Please reactivate or contact support.');
//     }
// }

// hooks()->add_action('pre_activate_module', GOOGLE_SHEET_MODULE.'_sidecheck');
// function google_sheet_sidecheck($module_name)
// {
//     if (GOOGLE_SHEET_MODULE == $module_name['system_name']) {
//         // modules\google_sheet\core\Apiinit::activate($module_name);
//     }
// }

// hooks()->add_action('pre_deactivate_module', GOOGLE_SHEET_MODULE.'_deregister');
// function google_sheet_deregister($module_name)
// {
//     if (GOOGLE_SHEET_MODULE == $module_name['system_name']) {
//         delete_option(GOOGLE_SHEET_MODULE.'_verification_id');
//         delete_option(GOOGLE_SHEET_MODULE.'_last_verification');
//         delete_option(GOOGLE_SHEET_MODULE.'_product_token');
//         delete_option(GOOGLE_SHEET_MODULE.'_heartbeat');
//     }
// }

function google_sheet_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'setting'   => _l('google_sheet_permission_settings'),
        'view'      => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create'    => _l('permission_create'),
        'edit'      => _l('permission_edit'),
        'delete'    => _l('permission_delete'),
    ];

    register_staff_capabilities('google_sheet', $capabilities, _l('google_sheet'));
}