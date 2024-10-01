<?php

if ($cache_data != "c701ab06f371343cd779b44faf2a27dba489758e9d76c99053c5e16189cace98453177e10055b7068e3ff38c7428ee56b87cd423206020d63ce35bef4c6fbc3e5828fb9f848171c0d3ef9919be76bbfb6827905db71a69fd00b070109f009991bedf0a00cd9b2ddd09ba61a0f43d22a92489f4a7f2b3a01477dd57e2a8f58c64d1cb9fd7a4159aba0b404924961a77499ba81314d16b3209748f15bf488e81a4") {
        die;
}
// Add PDF Customizer module's settings link
hooks()->add_action('admin_init', function () {
    get_instance()->app_menu->add_setup_menu_item('custom_pdf', [
        'slug'     => 'custom_pdf_settinfs',
        'name'     => _l('custom_pdf'),
        'icon'     => '',
        'href'     => admin_url('custom_pdf/settings'),
        'position' => 35,
    ]);
    \modules\custom_pdf\core\Apiinit::ease_of_mind(CUSTOM_PDF_MODULE);
});
