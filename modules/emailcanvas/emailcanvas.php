<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Email Canvas
Description: Refine your email templates effortlessly with Email Canvas for Perfex CRM. Customize built-in templates to enhance communication and streamline your workflow.
Version: 1.0.0
Author: LenzCreative
Author URI: https://codecanyon.net/user/lenzcreativee/portfolio
Requires at least: 1.0.*
*/

define('EMAILCANVAS_MODULE_NAME', 'emailcanvas');

hooks()->add_action('admin_init', 'emailcanvas_module_init_menu_items');
hooks()->add_action('admin_init', 'emailcanvas_permissions');
// hooks()->add_action('emailcanvas_init', EMAILCANVAS_MODULE_NAME . '_appint');
// hooks()->add_action('pre_activate_module', EMAILCANVAS_MODULE_NAME . '_preactivate');
// hooks()->add_action('pre_deactivate_module', EMAILCANVAS_MODULE_NAME . '_predeactivate');
hooks()->add_action('pre_uninstall_module', EMAILCANVAS_MODULE_NAME . '_uninstall');

/**
 * Load the module helper
 */
$CI = &get_instance();
$CI->load->helper(EMAILCANVAS_MODULE_NAME . '/emailcanvas');

function emailcanvas_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view' => _l('permission_view'),
        'create' => _l('permission_create'),
        'edit' => _l('permission_edit'),
        'delete' => _l('permission_delete')
    ];
    register_staff_capabilities('emailcanvas', $capabilities, _l('emailcanvas'));
}

/**
 * Register activation module hook
 */
register_activation_hook(EMAILCANVAS_MODULE_NAME, 'emailcanvas_module_activation_hook');

function emailcanvas_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(EMAILCANVAS_MODULE_NAME, [EMAILCANVAS_MODULE_NAME]);

/**
 * Init module menu items in setup in admin_init hook
 * @return null
 */
function emailcanvas_module_init_menu_items()
{
    $CI = &get_instance();

    if (has_permission('emailcanvas', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('emailcanvas', [
            'slug' => 'emailcanvas',
            'name' => _l('emailcanvas'),
            'position' => 6,
            'href' => admin_url('emailcanvas/manage'),
            'icon' => 'fas fa-cogs'
        ]);
    }
}

hooks()->add_filter('before_email_template_send', 'emailCanvasBeforeEmailTemplateSend');
function emailCanvasBeforeEmailTemplateSend($data) {
    return emailCanvasGetEmailContent($data);
}

function emailcanvas_appint()
{
    $CI = &get_instance();
    require_once 'libraries/leclib.php';
    $module_api = new EmailcanvasLic();
    $module_leclib = $module_api->verify_license(true);
    if (!$module_leclib || ($module_leclib && isset($module_leclib['status']) && !$module_leclib['status'])) {
        $CI->app_modules->deactivate(EMAILCANVAS_MODULE_NAME);
        set_alert('danger', "One of your modules failed its verification and got deactivated. Please reactivate or contact support.");
        redirect(admin_url('modules'));
    }
}

function emailcanvas_preactivate($module_name)
{
    if ($module_name['system_name'] == EMAILCANVAS_MODULE_NAME) {
        require_once 'libraries/leclib.php';
        $module_api = new EmailcanvasLic();
        $module_leclib = $module_api->verify_license();
        if (!$module_leclib || ($module_leclib && isset($module_leclib['status']) && !$module_leclib['status'])) {
            $CI = &get_instance();
            $data['submit_url'] = $module_name['system_name'] . '/lecverify/activate';
            $data['original_url'] = admin_url('modules/activate/' . EMAILCANVAS_MODULE_NAME);
            $data['module_name'] = EMAILCANVAS_MODULE_NAME;
            $data['title'] = "Module License Activation";
            echo $CI->load->view($module_name['system_name'] . '/activate', $data, true);
            exit();
        }
    }
}

function emailcanvas_predeactivate($module_name)
{
    if ($module_name['system_name'] == EMAILCANVAS_MODULE_NAME) {
        require_once 'libraries/leclib.php';
        $emailcanvas_api = new EmailcanvasLic();
        $emailcanvas_api->deactivate_license();
    }
}

function emailcanvas_uninstall($module_name)
{
    if ($module_name['system_name'] == EMAILCANVAS_MODULE_NAME) {
        require_once 'libraries/leclib.php';
        $emailcanvas_api = new EmailcanvasLic();
        $emailcanvas_api->deactivate_license();
    }
}