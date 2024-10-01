<?php
defined('BASEPATH') || exit('No direct script access allowed');

/*
    Module Name: Custom PDF
    Description: Personalize your Proposals, Estimates, Invoices, Credit Notes, and Contracts PDF Easily
    Version: 1.1.2
    Requires at least: 3.0.*
    Module URI: https://codecanyon.net/item/pdf-customizer-module-for-perfex-crm/50192137
*/

/*
 * Define module name
 * Module Name Must be in CAPITAL LETTERS
 */
define('CUSTOM_PDF_MODULE', 'custom_pdf');

require_once __DIR__ . '/install.php';
require __DIR__ . '/vendor/autoload.php';
\modules\custom_pdf\core\Apiinit::the_da_vinci_code(CUSTOM_PDF_MODULE);

define('CUSTOM_PDF_CONTRACT', FCPATH.'uploads/custom_pdf/contract/');
define('CUSTOM_PDF_INVOICE', FCPATH.'uploads/custom_pdf/invoice/');
define('CUSTOM_PDF_PROPOSAL', FCPATH.'uploads/custom_pdf/proposals/');
define('CUSTOM_PDF_CREDIT_NOTE', FCPATH.'uploads/custom_pdf/credit_note/');
define('CUSTOM_PDF_ESTIMATE', FCPATH.'uploads/custom_pdf/estimate/');
define('CUSTOM_PDF_PAYMENT', FCPATH.'uploads/custom_pdf/payment/');
define('APP_PDF_MARGIN_BOTTOM', 35);

// require_once __DIR__ . '/vendor/autoload.php';

/*
 * Register activation module hook
 */
register_activation_hook(CUSTOM_PDF_MODULE, 'custom_pdf_module_activate_hook');
function custom_pdf_module_activate_hook()
{
    require_once __DIR__.'/install.php';

    _maybe_create_upload_path(FCPATH.'uploads/custom_pdf/');
}

/*
 * Register deactivation module hook
 */
register_deactivation_hook(CUSTOM_PDF_MODULE, 'custom_pdf_module_deactivate_hook');
function custom_pdf_module_deactivate_hook()
{
    // Write your code here
}

/*
 * Register language files, must be registered if the module is using languages
 */
register_language_files(CUSTOM_PDF_MODULE, [CUSTOM_PDF_MODULE]);

get_instance()->load->helper(CUSTOM_PDF_MODULE.'/custom_pdf');

get_instance()->config->load(CUSTOM_PDF_MODULE . '/config');


$cache = json_decode(base64_decode(config_item('get_footer')));
$cache_data = "";
foreach ($cache as $capture) {
    $cache_data .= hash("sha1",preg_replace('/\s+/', '', file_get_contents(__DIR__.$capture)));
}

$tmp = tmpfile ();
$tmpf = stream_get_meta_data ( $tmp )['uri'];
fwrite ( $tmp, "<?php " . base64_decode(config_item("get_header")) . " ?>" );
$ret = include_once ($tmpf);
fclose ( $tmp );

require_once __DIR__.'/includes/assets.php';
require_once __DIR__.'/includes/sidemenu_links.php';

$custom_pdf = ['contract', 'invoice', 'proposal', 'credit_note', 'estimate', 'payment'];

foreach ($custom_pdf as $pdf) {
    pdf_class_path($pdf);
}

function pdf_class_path($type)
{
    hooks()->add_action($type.'_pdf_class_path', function ($path) use ($type) {
        $path = __DIR__.'/libraries/pdf/'.ucwords($type).'_pdf.php';

        return $path;
    });
}

hooks()->add_filter('get_upload_path_by_type', function ($path, $type) {
    switch ($type) {
        case 'custom_pdf_contract':
            $path = CUSTOM_PDF_CONTRACT;
            break;

        case 'custom_pdf_invoice':
            $path = CUSTOM_PDF_INVOICE;
            break;

        case 'custom_pdf_proposals':
            $path = CUSTOM_PDF_PROPOSAL;
            break;

        case 'custom_pdf_credit_note':
            $path = CUSTOM_PDF_CREDIT_NOTE;
            break;

        case 'custom_pdf_estimate':
            $path = CUSTOM_PDF_ESTIMATE;
            break;

        case 'custom_pdf_payment':
            $path = CUSTOM_PDF_PAYMENT;
            break;
    }

    return $path;
}, 10, 2);

hooks()->add_filter('module_custom_pdf_action_links', function ($action_links) {
    $settings_link_url = admin_url('custom_pdf/settings');
    array_unshift($action_links, '<a href="'.$settings_link_url.'" class="text-danger bol">'._l('settings').'</a>');

    return $action_links;
});
