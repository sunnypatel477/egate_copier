<?php

defined("BASEPATH") or exit("No direct script access allowed");

/*
Module Name: NOTES
Description: View all your notes in one list.

Author: Halil
Author URI: https://codecanyon.net/user/halilaltndg
Version: 1.0.1
*/

define("NOTES_MODULE_NAME", "notes");

hooks()->add_action("admin_init", "note_module_menu_items");

function note_module_menu_items(){
    $CI = &get_instance();

    if (staff_can('note_manage', 'note_manage' )) {

        $CI->app_menu->add_sidebar_menu_item('notes_manage', [

            'href'     => admin_url('notes/notes/note'),

            'name'     => _l('contracts_notes_tab'),

            'position' => 50,

            'icon' => 'fa fa-file-text',

            'badge'    => [],

        ]);

    }
}

hooks()->add_action('admin_init', "note_module_manage_permission");

function note_module_manage_permission(){

    $capabilities = [];

    $capabilities["capabilities"] = [

        "note_manage"             => _l('contracts_notes_tab') ,

    ];

    register_staff_capabilities("note_manage", $capabilities , _l('contracts_notes_tab') );

}


hooks()->add_action("app_admin_footer", function (){

    if (staff_can('note_manage', 'note_manage' ))
    {

        echo " 
               
                <script src='" . base_url("modules/notes/assets/perfex_notes.js?v=2") . "'></script> 
                
                ";
    }

});





hooks()->add_action('after_invoice_preview_template_rendered', function (){

    echo "<script> perfex_note_set_note_tab(); </script>";

});

hooks()->add_action('after_proposal_view_as_client_link', function (){

    echo "<script> perfex_note_set_note_tab(); </script>";

});

hooks()->add_action('after_estimate_view_as_client_link', function (){

    echo "<script> perfex_note_set_note_tab(); </script>";

});

