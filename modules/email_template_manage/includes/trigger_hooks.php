<?php

function email_template_manage_available_webhooks_data( $webhook_trigger , $status = 0 , $source = 0 , $priority = 0 )
{

    $CI        = &get_instance();

    $webhook_data = $CI->db->select('id,options')
                            ->from(db_prefix().'email_template_manage_webhooks')
                            ->where('webhook_trigger',$webhook_trigger)
                            ->where('status',1)
                            ->get()
                            ->result();


    /**
     * Will send trigger data
     */
    $trigger_data = [];

    if ( !empty( $webhook_data ) )
    {

        foreach ( $webhook_data as $webhook )
        {

            $add_trigger_data_status    = false;
            $add_trigger_data_sources   = false;
            $add_trigger_data_priority   = false;

            if ( $webhook->options )
            {

                $options   = json_decode( $webhook->options );


                /**
                 * checking status
                 */
                if ( !empty( $options->status ) )
                {

                    $record_status = (array)$options->status;

                    if ( !empty( $status ) && !empty( $record_status ) )
                    {

                        if ( in_array( $status , $record_status ) )
                        {
                            $add_trigger_data_status = true;
                        }

                    }

                }
                else
                    $add_trigger_data_status = true;


                /**
                 * checking sources
                 */
                if ( !empty( $options->sources ) )
                {

                    $record_sources = (array)$options->sources;

                    if ( !empty( $source ) && $record_sources )
                    {

                        if ( in_array( $source , $record_sources ) )
                        {

                            $add_trigger_data_sources = true;

                        }

                    }

                }
                else
                    $add_trigger_data_sources = true;


                /**
                 * checking priority
                 */
                if ( !empty( $options->priority ) )
                {

                    $record_priority = (array)$options->priority;

                    if ( !empty( $priority ) && $record_priority )
                    {

                        if ( in_array( $priority , $record_priority ) )
                        {

                            $add_trigger_data_priority = true;

                        }

                    }

                }
                else
                    $add_trigger_data_priority = true;


            }// end if webhook option control
            else
            {

                $add_trigger_data_status = true;
                $add_trigger_data_sources = true;
                $add_trigger_data_priority = true;

            }


            /**
             * all conditions are met
             */
            if ( $add_trigger_data_status && $add_trigger_data_sources && $add_trigger_data_priority )
                $trigger_data[] = $webhook->id;


        }// end foreach webhooks

    }// end if webhook data



    return $trigger_data;


}


hooks()->add_action("project_status_changed", function( $data ) {

    if ( empty( $data['status'] ) || empty( $data['project_id'] ) )
        return true;

    $status = $data['status'];
    $project_id = $data['project_id'];

    $webhook_data = email_template_manage_available_webhooks_data( 'project_status_changed' , $status );


    if ( !empty( $webhook_data ) )
    {

        $CI        = &get_instance();

        $CI->load->model('email_template_manage/email_template_manage_model');

        foreach ( $webhook_data as $webhook_id )
        {

            $CI->email_template_manage_model->send_webhook( $webhook_id , 'project' , $project_id );

        }

    }

} );


hooks()->add_action("task_status_changed", function( $data ) {

    if ( empty( $data['status'] ) || empty( $data['task_id'] ) )
        return true;

    $status     = $data['status'];
    $task_id    = $data['task_id'];
    $task_data  = get_instance()->db->select('priority')->from(db_prefix().'tasks')->where('id',$task_id)->get()->row();

    $priority = 0;

    if ( !empty( $task_data->priority ) )
        $priority     = $task_data->priority;

    $webhook_data = email_template_manage_available_webhooks_data( 'task_status_changed' , $status , 0 , $priority );


    if ( !empty( $webhook_data ) )
    {

        $CI        = &get_instance();

        $CI->load->model('email_template_manage/email_template_manage_model');

        foreach ( $webhook_data as $webhook_id )
        {

            $CI->email_template_manage_model->send_webhook( $webhook_id , 'task' , $task_id );

        }

    }

} );


hooks()->add_action("invoice_status_changed", function( $data ) {

    if ( empty( $data['status'] ) || empty( $data['invoice_id'] ) )
        return true;

    $status = $data['status'];
    $invoice_id = $data['invoice_id'];

    $webhook_data = email_template_manage_available_webhooks_data( 'invoice_status_changed' , $status );


    if ( !empty( $webhook_data ) )
    {

        $CI        = &get_instance();

        $CI->load->model('email_template_manage/email_template_manage_model');

        foreach ( $webhook_data as $webhook_id )
        {

            $CI->email_template_manage_model->send_webhook( $webhook_id , 'invoice' , $invoice_id );

        }

    }

} );


hooks()->add_action("lead_status_changed", function( $data ) {

    if ( empty( $data['new_status'] ) || empty( $data['lead_id'] ) )
        return true;

    $lead_id    = $data['lead_id'];

    $lead_data  = get_instance()->db->select('status')->from(db_prefix().'leads')->where('id',$lead_id)->get()->row();

    if ( !empty( $lead_data->status ) )
        $status     = $lead_data->status;
    else
        return true;

    $webhook_data = email_template_manage_available_webhooks_data( 'lead_status_changed' , $status );


    if ( !empty( $webhook_data ) )
    {

        $CI        = &get_instance();

        $CI->load->model('email_template_manage/email_template_manage_model');

        foreach ( $webhook_data as $webhook_id )
        {

            $CI->email_template_manage_model->send_webhook( $webhook_id , 'lead' , $lead_id );

        }

    }

} );


hooks()->add_action("lead_created", function( $lead_id ) {

    if ( empty( $lead_id ) )
        return true;

    $lead_data  = get_instance()->db->select('status,source')->from(db_prefix().'leads')->where('id',$lead_id)->get()->row();

    $status = 0;
    $source = 0;

    if ( !empty( $lead_data->status ) )
        $status     = $lead_data->status;

    if ( !empty( $lead_data->source ) )
        $source     = $lead_data->source;

    $webhook_data = email_template_manage_available_webhooks_data( 'lead_created' , $status , $source );


    if ( !empty( $webhook_data ) )
    {

        $CI        = &get_instance();

        $CI->load->model('email_template_manage/email_template_manage_model');

        foreach ( $webhook_data as $webhook_id )
        {

            $CI->email_template_manage_model->send_webhook( $webhook_id , 'lead' , $lead_id );

        }

    }

} );


hooks()->add_action("after_add_task", function( $task_id ) {

    if ( empty( $task_id ) )
        return true;

    $task_data  = get_instance()->db->select('priority')->from(db_prefix().'tasks')->where('id',$task_id)->get()->row();

    $priority = 0;

    if ( !empty( $task_data->priority ) )
        $priority     = $task_data->priority;

    $webhook_data = email_template_manage_available_webhooks_data( 'after_add_task' , 0 , 0  , $priority );


    if ( !empty( $webhook_data ) )
    {

        $CI        = &get_instance();

        $CI->load->model('email_template_manage/email_template_manage_model');

        foreach ( $webhook_data as $webhook_id )
        {

            $CI->email_template_manage_model->send_webhook( $webhook_id , 'task' , $task_id );

        }

    }

} );

