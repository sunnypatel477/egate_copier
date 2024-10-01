<?php

defined("BASEPATH") or exit("No direct script access allowed");

/*
Module Name: Perfex Table Title
Description: Manage the titles of your table lists. You can hide the column you don't want to see.
Author: Halil [ halilaltndg@gmail.com ]
Author URI: https://www.fiverr.com/halilaltndg
Version: 1.0.2
*/


define('TABLE_MANAGE_MODULE_NAME', "table_manage");


// Table manage permission.
hooks()->add_action("admin_init", "table_manage_permission");

hooks()->add_action("app_admin_footer", "table_manage_include_footer_static_assets");


/**
 *
 * Table js file and model html are included
 *
 */
function table_manage_include_footer_static_assets()
{

    $has_table_permission   = 0;
    $modal_html             = "";

    if( staff_can( 'perfex_table_manage' , 'perfex_table_manage'  ) )
    {

        $has_table_permission = 1;


        // set table header for yourself
        $modal_html = '<div class="modal fade" id="table_manage_modal" tabindex="-1" role="dialog">

                            <div class="modal-dialog  modal-md" role="document">
        
                                <div class="modal-content">
        
                                    <div class="modal-body" id="table_manage_modal_body"> 
        
                                    </div> 
        
                                </div>  
        
                            </div>  
        
                        </div>';


        // set table header for staff roles
        $tm_staff_roles = get_instance()->db->select('roleid, name')->from(db_prefix().'roles')->order_by('name')->get()->result();

        $tm_staff_roles_options = "";

        foreach ( $tm_staff_roles as $tm_staff_role )
        {
            $tm_staff_roles_options .= "<option value='$tm_staff_role->roleid'> $tm_staff_role->name </option>";
        }

        $modal_html .= '<div class="modal fade" id="table_manage_modal_staff_role" tabindex="-1" role="dialog">

                            <div class="modal-dialog modal-md" role="document">
                                
                                <div class="modal-content">
                                 
                                    <div class="modal-header">
                                       
                                        <select onchange="tm_dt_staff_role_change()" id="tb_dt_staff_role" class="selectpicker" data-width="100%" data-live-search="true" data-none-selected-text="'. _l('dropdown_non_selected_tex').'" >
        
                                            <option></option>
                                            
                                            '.$tm_staff_roles_options.'
                                                    
                                        </select>
                                        
                                    </div>
                                
                                    <div class="modal-body" id="table_manage_modal_body_staff_role"> 
        
                                    </div> 
                                    
                                    <div class="modal-footer">
                                        <a onclick="tm_dt_staff_role_save()" class="btn btn-primary btn-block"> '._l('save').' </a>
                                    </div>
        
                                </div>  
        
                            </div>  
        
                        </div>';

    }


    echo "
    <script> var tm_dt_has_moule = $has_table_permission; </script>
    
    <script src='" . base_url("modules/table_manage/assets/table_manage_theme.js?v=3") ."'></script> ";


    echo $modal_html;



}


/**
 * Permission
 */
function table_manage_permission()
{

    $capabilities = [];

    $capabilities["capabilities"] = [

        "perfex_table_manage"      => "Perfex Table Title" ,

    ];

    register_staff_capabilities("perfex_table_manage", $capabilities , 'Perfex Table Title' );

}
