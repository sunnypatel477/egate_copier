<?php

defined("BASEPATH") or exit("No direct script access allowed");

/*
Module Name: Email Template Manage
Description: Design email templates and send them in bulk to clients or leads.
Author: Halil
Author URI: https://www.fiverr.com/halilaltndg
Version: 1.2.2
*/


define("EMAIL_TEMPLATE_MANAGE_MODULE_NAME", "email_template_manage");

hooks()->add_action("admin_init", "email_template_manage_permission");

hooks()->add_action("admin_init", "email_template_manage_module_init_menu_items");


/**
 * @note Language uploading
 */
register_language_files(EMAIL_TEMPLATE_MANAGE_MODULE_NAME, [EMAIL_TEMPLATE_MANAGE_MODULE_NAME]);

register_activation_hook(EMAIL_TEMPLATE_MANAGE_MODULE_NAME, "email_template_manage_module_activation_hook");

/**
 * @date email file folder path
 */
hooks()->add_filter('get_upload_path_by_type' , 'get_upload_path_by_type_email_template_manage' , 10 , 2 );

function get_upload_path_by_type_email_template_manage( $path  , $type )
{

    if ( $type == 'email_template' )
        return FCPATH . 'uploads/email_template/';

    return $path;

}




/**
 * @note email template manage db installing
 */
function email_template_manage_module_activation_hook()
{

    $CI = &get_instance();

    require_once __DIR__ . "/install.php";

}


/**
 * @note module permission
 *
 * @return void
 */
function email_template_manage_permission()
{

    $capabilities = [];

    $capabilities["capabilities"] = [

        "email_template_manage"             => _l('email_template_manage_permission') ,

        "email_template_manage_timer"       => _l('email_template_manage_mail_timer') ,

        "email_template_manage_special"     => _l('email_template_manage_special') ,

        "email_template_manage_trigger"     => _l('email_template_manage_reminder') ,

        "email_template_manage_webhook"     => _l('email_template_manage_webhook') ,

        "email_template_manage_mail_logs"   => _l('email_template_manage_email_log') ,

        "email_template_manage_smtp"        => _l('email_template_manage_smtp') ,

    ];

    register_staff_capabilities("email_template_manage", $capabilities , _l('email_template_manage_permission') );

}


/**
 * @note modul menu
 *
 * @return void
 */
function email_template_manage_module_init_menu_items()
{

    $CI = & get_instance();

    if(
        staff_can( 'email_template_manage' , 'email_template_manage' ) ||
        staff_can( 'email_template_manage_timer' , 'email_template_manage' ) ||
        staff_can( 'email_template_manage_special' , 'email_template_manage' ) ||
        staff_can( 'email_template_manage_trigger' , 'email_template_manage' ) ||
        staff_can( 'email_template_manage_webhook' , 'email_template_manage' ) ||
        staff_can( 'email_template_manage_mail_logs' , 'email_template_manage' ) ||
        staff_can( 'email_template_manage_smtp' , 'email_template_manage' )
    )
    {

        $CI->app_menu->add_sidebar_menu_item("email_template_manage_menu", [

            'collapse' => true,

            'name' => _l("email_template_manage"),

            'position' => 26,

            'icon' => 'fa fa-envelope',

        ]);// cogs

        if ( staff_can( 'email_template_manage' , 'email_template_manage' )  )
            $CI->app_menu->add_sidebar_children_item('email_template_manage_menu', [

                'slug' => 'email_template_manage_templates',

                'name' => _l('email_template_manage_templates'),

                'href' => admin_url('email_template_manage'),

                'position' => 3,

            ]);


        if ( staff_can( 'email_template_manage_timer' , 'email_template_manage' ) )
            $CI->app_menu->add_sidebar_children_item('email_template_manage_menu', [

                'slug' => 'email_template_manage_timer',

                'name' => _l('email_template_manage_mail_timer'),

                'href' => admin_url('email_template_manage/timer'),

                'position' => 10,

            ]);


        if ( staff_can( 'email_template_manage_special' , 'email_template_manage' ) )
            $CI->app_menu->add_sidebar_children_item('email_template_manage_menu', [

                'slug' => 'email_template_manage_special',

                'name' => _l('email_template_manage_specials'),

                'href' => admin_url('email_template_manage/special'),

                'position' => 15,

            ]);

        if ( staff_can( 'email_template_manage_mail_logs' , 'email_template_manage' ) )
            $CI->app_menu->add_sidebar_children_item('email_template_manage_menu', [

                'slug' => 'email_template_manage_email_log',

                'name' => _l('email_template_manage_email_log'),

                'href' => admin_url('email_template_manage/mail_log'),

                'position' => 66,

            ]);


        /**
         * @Version 1.1.5
         */
        if ( staff_can( 'email_template_manage_smtp' , 'email_template_manage' ) )
            $CI->app_menu->add_sidebar_children_item('email_template_manage_menu', [

                'slug' => 'email_template_manage_smtp',

                'name' => _l('email_template_manage_smtp'),

                'href' => admin_url('email_template_manage/smtp_settings'),

                'position' => 55,

            ]);


        /**
         * System templates
         */
        if ( is_admin() )
            $CI->app_menu->add_sidebar_children_item('email_template_manage_menu', [

                'slug' => 'email_template_manage_setting',

                'name' => _l('email_template_manage_system_templates'),

                'href' => admin_url('email_template_manage/system_templates'),

                'position' => 28,

            ]);


        if ( staff_can( 'email_template_manage_trigger' , 'email_template_manage' ) )
            $CI->app_menu->add_sidebar_children_item('email_template_manage_menu', [

                'slug' => 'email_template_manage_trigger',

                'name' => _l('email_template_manage_reminder'),

                'href' => admin_url('email_template_manage/trigger'),

                'position' => 25,

            ]);

        /**
         * @version 1.1.6 WebHooks
         */
        if ( staff_can( 'email_template_manage_webhook' , 'email_template_manage' ) )
            $CI->app_menu->add_sidebar_children_item('email_template_manage_menu', [

                'slug' => 'email_template_manage_webhook',

                'name' => _l('email_template_manage_webhook'),

                'href' => admin_url('email_template_manage/webhooks'),

                'position' => 25,

            ]);




        /**
         * Client profil tab
         */
        if ( staff_can( 'email_template_manage_mail_logs' , 'email_template_manage' )  )
            $CI->app_tabs->add_customer_profile_tab('email_template_manage_menu', [

                'name'     => _l('email_template_manage_email_log'),

                'icon'     => 'fa fa-envelope',

                'view'     => '../../modules/email_template_manage/views/inc_client_tab',

                'position' => 60 ,

            ]);// <span class="badge pull-right bg-bg-default" style="background-color: "> 2 </span>



    }

}



/**
 * Mail send cron
 */
hooks()->add_action("before_cron_run", "email_template_manage_email_cron");


function email_template_manage_email_cron(  $data = null )
{


    $CI        = &get_instance();

    $CI->load->model('email_template_manage/email_template_manage_model');

    /**
     * Timers
     */
    $CI->email_template_manage_model->send_templates();

    /**
     * Reminders
     */
    $CI->email_template_manage_model->send_triggers();

    /**
     * Special mails
     */
    $CI->email_template_manage_model->send_specials();


}




/**
 * Module js file
 */


hooks()->add_action("app_admin_footer", "email_template_manage_include_footer_static_assets");

function email_template_manage_include_footer_static_assets()
{

    if(
        staff_can( 'email_template_manage' , 'email_template_manage'  ) ||
        staff_can( 'email_template_manage_mail_logs' , 'email_template_manage' )
    )
    {

        // Send mail modal
        echo '

<div class="modal fade" id="email_template_manage_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> </div>';


        echo "
    
    <script src='" . base_url("modules/email_template_manage/assets/email_template_manage_js.js?v=2") ."'></script> ";

    }

}


/**
 * Lead modal emila logs
 */
hooks()->add_action("after_lead_lead_tabs","email_template_manage_lead_tab");

function email_template_manage_lead_tab( $lead = null )
{
    if ( staff_can( 'email_template_manage_mail_logs' , 'email_template_manage' )  )
    {

        echo '<li role="presentation">
            <a href="#email_template_manage_mail_tab" aria-controls="gdpr" role="tab" data-toggle="tab">
                '. _l('email_template_manage_email_log') .'
            </a>
        </li>';

    }
}

hooks()->add_action("after_lead_tabs_content","email_template_manage_lead_tab_content");

function email_template_manage_lead_tab_content( $lead = null )
{

    if ( staff_can( 'email_template_manage_mail_logs' , 'email_template_manage' )  )
    {

        $CI = &get_instance();

        echo $CI->load->view( 'email_template_manage/inc_lead_tab', [ 'lead' => $lead ], true);

    }

}


/**
 * @Version 1.1.0
 * @note Mails send  from Perfex System Will be log
 */
hooks()->add_filter('after_parse_email_template_message', 'email_template_manage_email_tracking_inject_in_body');

function email_template_manage_email_tracking_inject_in_body($template)
{

    $table_name = db_prefix().'email_template_manage_system_templates';


    if ( !empty( $template->slug ) && total_rows( $table_name , [ 'template_slug' => $template->slug , 'status' => 1 ] ) )
    {

        $template->message .= '<img src="' . site_url('email_template_manage/check_email/track_temp/' . $template->tmp_id) . '" alt="" width="1" height="1" border="0" style="height:1px!important;width:1px!important;border-width:0!important;margin-top:0!important;margin-bottom:0!important;margin-right:0!important;margin-left:0!important;padding-top:0!important;padding-bottom:0!important;padding-right:0!important;padding-left:0!important">';

    }

    return $template;

}

hooks()->add_action('email_template_sent', 'email_template_manage_email_template_sent');

function email_template_manage_email_template_sent( $data )
{

    $CI = &get_instance();

    $table_name = db_prefix().'email_template_manage_system_templates';


    if ( !empty( $data['template']->slug ) && total_rows( $table_name , [ 'template_slug' => $data['template']->slug , 'status' => 1 ] ) )
    {

        try {


            $message = str_replace( 'check_emails/track' , '' , $data['template']->message );
            $message = str_replace( 'check_email/track' , '' , $message );


            $rel_type       = '' ;// customer  lead
            $rel_id         = 0 ;
            $check_staff    = false ;

            $to_email   = $data['email'];

            $template_rel_id    = $GLOBALS['SENDING_EMAIL_TEMPLATE_CLASS']->get_rel_id();
            $template_rel_type  = $GLOBALS['SENDING_EMAIL_TEMPLATE_CLASS']->get_rel_type();

            switch ( $template_rel_type )
            {

                case 'estimate_request' :
                case 'notifications' :
                case 'staff' :
                        $check_staff = true;
                    break;

                case 'proposal' :

                        $proposal_info = $CI->db->select('rel_id,rel_type,email')->from(db_prefix().'proposals')->where('id',$template_rel_id)->get()->row();

                        if ( !empty( $proposal_info )  )
                        {

                            if( $proposal_info->email == $to_email )
                            {

                                $rel_type   = $proposal_info->rel_type ;

                                $rel_id     = $proposal_info->rel_id ;

                            }
                            else
                                $check_staff = true;

                        }

                    break;

                case 'contract' :

                        $merge_field = $data["merge_fields"];

                        if ( !empty( $merge_field["{client_id}"] ) )
                        {

                            if( in_array( $data['template']->slug , [
                                'contract-expiration' ,
                                'send-contract' ,
                                'contract-comment-to-client' ,
                                'contract-sign-reminder' ,
                                ] ) )
                            {

                                $rel_type   = "customer";

                                $rel_id     = $merge_field["{client_id}"];

                                if ( !empty( $merge_field["{client_company}"] ) )
                                    $company_name = $merge_field["{client_company}"];

                            }
                            else
                                $check_staff = true;

                        }

                    break;

                case 'task' :

                        if( in_array( $data['template']->slug , [
                            'task-added-attachment-to-contacts' ,
                            'task-commented-to-contacts' ,
                            'task-status-change-to-contacts' ,
                        ] ) )
                        {

                            $task_data = $CI->db->select('rel_id , rel_type')->from(db_prefix().'tasks')->where('id',$template_rel_id)->get()->row();

                            if ( !empty( $task_data ) )
                            {

                                if ( $task_data->rel_type == 'customer' )
                                {

                                    $rel_type   = "customer";

                                    $rel_id     = $task_data->rel_id;

                                }
                                elseif ( $task_data->rel_type == 'project' )
                                {

                                    $project = $CI->db->select('name, clientid')
                                                    ->from(db_prefix() . 'projects')
                                                    ->where('id', $task_data->rel_id)
                                                    ->get()->row();

                                    if( !empty( $project->clientid ) )
                                    {

                                        $rel_type   = "customer";

                                        $rel_id     = $task_data->rel_id;

                                    }

                                }


                            }


                            $rel_type   = "customer";

                            $rel_id     = $merge_field["{client_id}"];

                        }
                        else
                            $check_staff = true;

                    break;

                case 'credit_note' :

                        $merge_field = $data["merge_fields"];

                        if ( !empty( $merge_field["{client_id}"] ) )
                        {

                            $rel_type   = "customer";

                            $rel_id     = $merge_field["{client_id}"];

                            if ( !empty( $merge_field["{client_company}"] ) )
                                $company_name = $merge_field["{client_company}"];

                        }

                    break;


                case 'estimate' :
                case 'client' :
                case 'invoice' :
                case 'ticket' :
                case 'subscriptions' :

                        $merge_field = $data["merge_fields"];

                        if ( !empty( $merge_field["{client_id}"] ) )
                        {

                            if( !empty( $merge_field["{contact_email}"] ) && $merge_field["{contact_email}"] == $to_email )
                            {

                                $rel_type   = "customer";

                                $rel_id     = $merge_field["{client_id}"];

                                if ( !empty( $merge_field["{client_company}"] ) )
                                    $company_name = $merge_field["{client_company}"];


                            }
                            else
                                $check_staff = true;

                        }

                    break;

                case 'lead':

                         $lead_data = $CI->db->select('id,company')->from(db_prefix().'leads')->where('email',$to_email)->get()->row();

                         if ( !empty( $lead_data->id ) )
                         {

                             $rel_type      = "lead";

                             $company_name  = $lead_data->company;

                             $rel_id        = $lead_data->id;

                         }

                    break;

            }


            if ( $check_staff )
            {

                $staff_data = $CI->db->select('staffid')->from(db_prefix().'staff')->where('email',$to_email)->get()->row();

                if ( !empty( $staff_data->staffid ) )
                {

                    $rel_type   = "staff";

                    $company_name   = $staff_data->firstname." ".$staff_data->lastname;

                    $rel_id     = $staff_data->staffid;

                }


            }


            if ( empty( $company_name ) )
            {

                if ( $rel_type == "customer" )
                {

                    $client_data = $CI->db->select('company')->from(db_prefix().'clients')->where('userid',$rel_id)->get()->row();

                    if ( !empty( $client_data->company ) )
                    {
                        $company_name = $client_data->company;
                    }

                }
                elseif ( $rel_type == "lead" )
                {

                    $lead_data = $CI->db->select('company')->from(db_prefix().'leads')->where('id',$rel_id)->get()->row();

                    if ( !empty( $lead_data->company ) )
                    {
                        $company_name  = $lead_data->company;
                    }

                }

            }



            $mail_data = [

                'rel_type'      => $rel_type ,
                'rel_id'        => $rel_id ,
                'template_id'   => 0 ,
                'company_name'  => $company_name ,
                'company_email' => $to_email ,
                'mail_subject'  => $data['template']->subject ,
                'content'       => $message ,
                'status'        => 1 ,
                'date'          => date('Y-m-d H:i:s') ,
                'system_template_id'    => $data['template']->tmp_id ,
                'system_template_slug'  => $data['template']->slug ,

            ];


            $CI->db->insert(db_prefix() . 'email_template_manage_mail_logs' , $mail_data );


        }
        catch (Exception $e) {

        }


    }

}


hooks()->add_filter('customers_profile_tab_badge',function ( $data ){


    if ( !empty( $data['feature'] ) && $data['feature'] == 'email_template_manage_menu' )
    {

        $customer_id = $data['customer_id'];

        $total_count = total_rows( db_prefix().'email_template_manage_mail_logs' , [ 'rel_id' => $customer_id , 'rel_type' => 'customer' ] );

        if( !empty( $total_count ) )
        {

            $data['badge'] = [
                'value' => $total_count ,
                'color' => '' ,
                'type' => 'bg-default'
            ];

        }

    }

    return $data;


},10,3);





/**
 * All hooks files
 */
require_once __DIR__ . '/includes/table_list_hooks.php';

require_once __DIR__ . '/includes/new_merge_fields.php';

require_once __DIR__ . '/includes/trigger_hooks.php';
