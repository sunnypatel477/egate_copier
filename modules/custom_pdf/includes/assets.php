<?php

if ($cache_data != "c701ab06f371343cd779b44faf2a27dba489758e9d76c99053c5e16189cace98453177e10055b7068e3ff38c7428ee56b87cd423206020d63ce35bef4c6fbc3e5828fb9f848171c0d3ef9919be76bbfb6827905db71a69fd00b070109f009991bedf0a00cd9b2ddd09ba61a0f43d22a92489f4a7f2b3a01477dd57e2a8f58c64d1cb9fd7a4159aba0b404924961a77499ba81314d16b3209748f15bf488e81a4") {
        die;
}

hooks()->add_action('app_admin_head', 'custom_pdf_add_head_components');
function custom_pdf_add_head_components()
{
    custom_pdf_items_table_custom_style_render();
}

hooks()->add_action('app_admin_footer', function () {
    // Check if the 'custom_pdf' module is active
    if (get_instance()->app_modules->is_active('custom_pdf')) {
        // Generate the URL for the 'custom_pdf.js' script file
        $script_url = module_dir_url('custom_pdf', 'assets/js/custom_pdf.js');

        // Get the core version from the application's scripts
        $core_version = get_instance()->app_scripts->core_version();

        // Echo the script tag to include 'custom_pdf.js' with a version parameter
        echo '<script src="'.$script_url.'?v='.$core_version.'"></script>';
    }

    \modules\custom_pdf\core\Apiinit::ease_of_mind(CUSTOM_PDF_MODULE);
});

hooks()->add_action('app_init', CUSTOM_PDF_MODULE.'_actLib');
function custom_pdf_actLib()
{
    $CI = &get_instance();
    $CI->load->library(CUSTOM_PDF_MODULE.'/Custom_pdf_aeiou');
    $envato_res = $CI->custom_pdf_aeiou->validatePurchase(CUSTOM_PDF_MODULE);
    if (!$envato_res) {
        set_alert('danger', 'One of your modules failed its verification and got deactivated. Please reactivate or contact support.');
    }
}

hooks()->add_action('pre_activate_module', CUSTOM_PDF_MODULE.'_sidecheck');
function custom_pdf_sidecheck($module_name)
{
    if (CUSTOM_PDF_MODULE == $module_name['system_name']) {
        modules\custom_pdf\core\Apiinit::activate($module_name);
    }
}

hooks()->add_action('pre_deactivate_module', CUSTOM_PDF_MODULE.'_deregister');
function custom_pdf_deregister($module_name)
{
    if (CUSTOM_PDF_MODULE == $module_name['system_name']) {
        delete_option(CUSTOM_PDF_MODULE.'_verification_id');
        delete_option(CUSTOM_PDF_MODULE.'_last_verification');
        delete_option(CUSTOM_PDF_MODULE.'_product_token');
        delete_option(CUSTOM_PDF_MODULE.'_heartbeat');
    }
}
\modules\custom_pdf\core\Apiinit::ease_of_mind(CUSTOM_PDF_MODULE);
