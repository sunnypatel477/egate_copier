<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
  Module Name: Instax printing  
  Description: Module for printing software
  Version: 2.3.0
  Requires at least: 2.3.*
  Author: Sunny Patel
  Author URI: 
*/

/*
 * Seesa Staff Replace Module
*/
define('INSTAX_PRINTING_MODULE_NAME', 'instax_printing');

// Register language files, must be registered if the module is using languages
register_language_files(INSTAX_PRINTING_MODULE_NAME, [INSTAX_PRINTING_MODULE_NAME]);

hooks()->add_action('admin_init', 'instax_printing_module_init_menu_items', 10, 2);

hooks()->add_action('admin_init', 'instax_printing_module_permissions');

/**
 * Register activation module hook
 */
register_activation_hook(INSTAX_PRINTING_MODULE_NAME, 'instax_printing_module_activation_hook');

function instax_printing_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Load the module helper
 */
$CI = &get_instance();
$CI->load->helper(INSTAX_PRINTING_MODULE_NAME . '/instax_printing');

/**
 * Init staff_replace module menu items in setup in admin_init hook.
 *
 * @return null
 */
function instax_printing_module_init_menu_items()
{
    $CI = &get_instance();

    // $CI->app_menu->add_sidebar_menu_item('instax_printing', [
    //     'slug' => 'instax_printing',
    //     'name' => _l('instax_printing'),
    //     'href' => admin_url('instax_printing'),
    //     'position' => 100,
    //     'icon' => 'fa fa-tasks',
    // ]);

    $CI->app_menu->add_sidebar_menu_item('instax_printing', [
        'slug' => 'instax_printing',
        'name' => _l('instax_printing'),
        'href' => '',
        'position' => 100,
        'icon' => 'fa fa-tasks',
    ]);
    $CI->app_menu->add_sidebar_children_item('instax_printing', [
        'slug' => 'instax_printing',
        'name' => _l('instax_printing'),
        'href' => admin_url('instax_printing'),
        'position' => 100,
        'badge'    => []
    ]);
    $CI->app_menu->add_sidebar_children_item('instax_printing', [
        'slug' => 'instax_printing_background',
        'name' => _l('instax_printing_background'),
        'href' => admin_url('instax_printing/background_images'),
        'position' => 100,
        'badge'    => []
    ]);
    $CI->app_menu->add_sidebar_children_item('instax_printing', [
        'slug' => 'instax_printing_category',
        'name' => _l('instax_printing_category'),
        'href' => admin_url('instax_printing/background_category'),
        'position' => 100,
        'badge'    => []
    ]);
    $CI->app_menu->add_sidebar_children_item('instax_printing', [
        'slug' => 'instax_printing_event_category',
        'name' => _l('instax_printing_event_category'),
        'href' => admin_url('instax_printing/event_category'),
        'position' => 100,
        'badge'    => []
    ]);
    $CI->app_menu->add_sidebar_children_item('instax_printing', [
        'slug' => 'instax_printing_order_from',
        'name' => _l('instax_printing_order_from'),
        'href' => admin_url('instax_printing/order_from'),
        'position' => 100,
        'badge'    => []
    ]);
    $CI->app_menu->add_sidebar_children_item('instax_printing', [
        'slug' => 'instax_printing_set_up',
        'name' => _l('instax_printing_set_up'),
        'href' => admin_url('instax_printing/set_up'),
        'position' => 100,
        'badge'    => []
    ]);
    
}

/**
 * Register staff_replace module permissions in admin_init hook.
 *
 * @return null
 */
hooks()->add_action('admin_init', 'instax_printing_module_permissions');
function instax_printing_module_permissions()
{
    $capabilities = [];
    $capabilities['capabilities'] = [
        
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('instax_printing', $capabilities, _l('instax_printing'));
}
hooks()->add_action('customers_navigation_start', 'instax_printing_show_client_menu');
function instax_printing_show_client_menu()
{
    echo '<li class="">
      <a href="' . site_url('instax_printing/instax_printing_client') . '">
         ' . _l('instax_printing') . '
      </a>
   </li>';
}