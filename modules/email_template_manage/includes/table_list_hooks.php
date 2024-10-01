<?php



function email_template_manage_list_mail_index( $row , $row_id = 0 )
{


    for ( $row_ind = 0 ; $row_ind < count( $row ) ; $row_ind++ )
    {

        if ( !empty( $row[$row_ind] ) )
        {

            $check_content = str_replace( ' ' , '' , $row[$row_ind] );

            if( str_contains( $check_content , 'class="row-options"') )
            {

                return $row_ind;

            }

        }

    }

    return $row_id;

}


/**
 * Client tables list send email option added.
 */
hooks()->add_filter('customers_table_row_data', 'email_template_manage_client_lists', 10, 2);

function email_template_manage_client_lists( $row = null , $aRow = null )
{

    if ( staff_can( 'email_template_manage' , 'email_template_manage' ) && !empty( $row ) && !empty( $aRow ) )
    {

        if ( !empty( $aRow['userid'] ) && !empty( $row ) )
        {

            $user_id    = $aRow['userid'] ;

            $send_email_option = ' | <a href="#" class="text-success bold" onclick="email_template_manage_send_mail( \'customer\' , '.$user_id.' ); return false;" > <i class="fa fa-envelope"></i> '._l('email_template_manage_send_mail').' </a>';


            $row_ind = email_template_manage_list_mail_index( $row , 2 );

            $row[ $row_ind ] = $row[ $row_ind ].'<div class="row-options">'.$send_email_option.'</div>';


        }

    }

    return $row;

}


/**
 * Leads tables list send email option added.
 */
hooks()->add_filter('leads_table_row_data', 'email_template_manage_lead_lists', 10, 2);

function email_template_manage_lead_lists( $row = null , $aRow = null )
{

    if ( staff_can( 'email_template_manage' , 'email_template_manage' ) && !empty( $row ) && !empty( $aRow ) )
    {


        if ( !empty( $aRow['id'] ) )
        {

            $lead_id    = $aRow['id'] ;

            $send_email_option = ' | <a href="#" class="text-success bold" onclick="email_template_manage_send_mail( \'lead\' , '.$lead_id.' ); return false;" > <i class="fa fa-envelope"></i> '._l('email_template_manage_send_mail').' </a>';

            $row_ind = email_template_manage_list_mail_index( $row , 2 );

            $row[ $row_ind ] = $row[ $row_ind ].'<div class="row-options">'.$send_email_option.'</div>';

        }

    }

    return $row;

}


/**
 * Invoice tables list send email option added.
 */
hooks()->add_filter('invoices_table_row_data', 'email_template_manage_invoice_lists', 10, 2);

function email_template_manage_invoice_lists( $row = null , $aRow = null )
{

    if ( staff_can( 'email_template_manage' , 'email_template_manage' ) && !empty( $row ) && !empty( $aRow ) )
    {

        if ( !empty( $aRow['id'] ) && !empty( $row ) )
        {

            $invoice_id    = $aRow['id'] ;

            $send_email_option = ' | <a href="#" class="text-success bold" onclick="email_template_manage_send_mail( \'invoice\' , '.$invoice_id.' ); return false;" > <i class="fa fa-envelope"></i> '._l('email_template_manage_send_mail').' </a>';

            $row_ind = email_template_manage_list_mail_index( $row , 1 );

            $row[ $row_ind ] = $row[ $row_ind ].'<div class="row-options">'.$send_email_option.'</div>';

        }

    }

    return $row;

}


hooks()->add_action('after_invoice_view_as_client_link','email_template_manage_invoice_view');

function email_template_manage_invoice_view( $invoice )
{

    // echo ' <a href="#" class="text-success bold" onclick="email_template_manage_send_mail( \'invoice\' , '.$invoice->id.' ); return false;" > '._l('email_template_manage_send_mail').' </a> <br />';

}

/**
 * Project tables list send email option added.
 */
hooks()->add_filter('projects_table_row_data', 'email_template_manage_project_lists', 10, 2);

function email_template_manage_project_lists( $row = null , $aRow = null )
{

    if ( staff_can( 'email_template_manage' , 'email_template_manage' ) && !empty( $row ) && !empty( $aRow ) )
    {

        if ( !empty( $aRow['id'] ) && !empty( $row ) )
        {

            $project_id    = $aRow['id'] ;

            $send_email_option = ' | <a href="#" class="text-success bold" onclick="email_template_manage_send_mail( \'project\' , '.$project_id.' ); return false;" > <i class="fa fa-envelope"></i> '._l('email_template_manage_send_mail').' </a>';

            $row_ind = email_template_manage_list_mail_index( $row , 1 );

            $row[ $row_ind ] = $row[ $row_ind ].'<div class="row-options">'.$send_email_option.'</div>';

        }

    }

    return $row;

}

/**
 * Task tables list send email option added.
 */
hooks()->add_filter('tasks_table_row_data', 'email_template_manage_task_lists', 10, 2);

function email_template_manage_task_lists( $row = null , $aRow = null )
{

    if ( staff_can( 'email_template_manage' , 'email_template_manage' ) && !empty( $row ) && !empty( $aRow ) )
    {

        if ( !empty( $aRow['id'] ) && !empty( $row ) )
        {

            $project_id    = $aRow['id'] ;

            $send_email_option = ' | <a href="#" class="text-success bold" onclick="email_template_manage_send_mail( \'task\' , '.$project_id.' ); return false;" > <i class="fa fa-envelope"></i> '._l('email_template_manage_send_mail').' </a>';

            $row_ind = email_template_manage_list_mail_index( $row , 2 );

            $row[ $row_ind ] = $row[ $row_ind ].'<div class="row-options">'.$send_email_option.'</div>';

        }

    }

    return $row;

}


/**
 * Proposal tables list send email option added.
 */
hooks()->add_filter('proposals_table_row_data', 'email_template_manage_proposal_lists', 10, 2);

function email_template_manage_proposal_lists( $row = null , $aRow = null )
{

    if ( staff_can( 'email_template_manage' , 'email_template_manage' ) && !empty( $row ) && !empty( $aRow ) )
    {

        if ( !empty( $aRow[db_prefix() . 'proposals.id'] ) && !empty( $row ) )
        {

            $proposal_id    = $aRow[db_prefix() . 'proposals.id'];

            $send_email_option = ' | <a href="#" class="text-success bold" onclick="email_template_manage_send_mail( \'proposal\' , '.$proposal_id.' ); return false;" > <i class="fa fa-envelope"></i> '._l('email_template_manage_send_mail').' </a>';

            $row_ind = email_template_manage_list_mail_index( $row , 1 );

            $row[ $row_ind ] = $row[ $row_ind ].'<div class="row-options">'.$send_email_option.'</div>';

        }

    }

    return $row;

}

/**
 * Estimate tables list send email option added.
 */
hooks()->add_filter('estimates_table_row_data', 'email_template_manage_estimate_lists', 10, 2);

function email_template_manage_estimate_lists( $row = null , $aRow = null )
{

    if ( staff_can( 'email_template_manage' , 'email_template_manage' ) && !empty( $row ) && !empty( $aRow ) )
    {

        if ( !empty( $aRow['id'] ) && !empty( $row ) )
        {

            $estimate_id    = $aRow['id'];

            $send_email_option = ' | <a href="#" class="text-success bold" onclick="email_template_manage_send_mail( \'estimate\' , '.$estimate_id.' ); return false;" > <i class="fa fa-envelope"></i> '._l('email_template_manage_send_mail').' </a>';

            $row_ind = email_template_manage_list_mail_index( $row , 1 );

            $row[ $row_ind ] = $row[ $row_ind ].'<div class="row-options">'.$send_email_option.'</div>';

        }

    }

    return $row;

}

/**
 * Estimate Request tables list send email option added.
 */
hooks()->add_filter('estimate_request_table_row_data', 'email_template_manage_estimate_request_lists', 10, 2);

function email_template_manage_estimate_request_lists( $row = null , $aRow = null )
{

    if ( staff_can( 'email_template_manage' , 'email_template_manage' ) && !empty( $row ) && !empty( $aRow ) )
    {

        if ( !empty( $aRow['id'] ) && !empty( $row ) )
        {

            $estimate_id    = $aRow['id'];

            $send_email_option = ' | <a href="#" class="text-success bold" onclick="email_template_manage_send_mail( \'estimate_request\' , '.$estimate_id.' ); return false;" > <i class="fa fa-envelope"></i> '._l('email_template_manage_send_mail').' </a>';

            $row_ind = email_template_manage_list_mail_index( $row , 1 );

            $row[ $row_ind ] = $row[ $row_ind ].'<div class="row-options">'.$send_email_option.'</div>';

        }

    }

    return $row;

}

/**
 * Contract tables list send email option added.
 */
hooks()->add_filter('contracts_table_row_data', 'email_template_manage_contract_lists', 10, 2);

function email_template_manage_contract_lists( $row = null , $aRow = null )
{

    if ( staff_can( 'email_template_manage' , 'email_template_manage' ) && !empty( $row ) && !empty( $aRow ) )
    {

        if ( !empty( $aRow['id'] ) && !empty( $row ) )
        {

            $estimate_id    = $aRow['id'];

            $send_email_option = ' | <a href="#" class="text-success bold" onclick="email_template_manage_send_mail( \'contract\' , '.$estimate_id.' ); return false;" > <i class="fa fa-envelope"></i> '._l('email_template_manage_send_mail').' </a>';

            $row_ind = email_template_manage_list_mail_index( $row , 1 );

            $row[ $row_ind ] = $row[ $row_ind ].'<div class="row-options">'.$send_email_option.'</div>';

        }

    }

    return $row;

}

/**
 * Contract tables list send email option added.
 */
hooks()->add_filter('staff_table_row', function( $row = null , $aRow = null ) {

    if ( staff_can( 'email_template_manage' , 'email_template_manage' ) && !empty( $row ) && !empty( $aRow ) )
    {

        if ( !empty( $aRow['staffid'] ) && !empty( $row ) )
        {

            $staff_id    = $aRow['staffid'];

            $send_email_option = ' | <a href="#" class="text-success bold" onclick="email_template_manage_send_mail( \'staff\' , '.$staff_id.' ); return false;" > <i class="fa fa-envelope"></i> '._l('email_template_manage_send_mail').' </a>';

            $row_ind = email_template_manage_list_mail_index( $row , 1 );

            $row[ $row_ind ] = $row[ $row_ind ].'<div class="row-options">'.$send_email_option.'</div>';

        }

    }

    return $row;

}  , 10, 2);
