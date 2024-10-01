<?php


hooks()->add_filter('project_merge_fields', function( $fields , $args )
{

    $project_id = $args['id'];

    $project_status = '';

    if ( !empty( $args['project']->status ) )
    {

        $status_data = get_project_status_by_id( $args['project']->status );

        if ( !empty( $status_data[ 'name' ] ) )
            $project_status = $status_data[ 'name' ];

    }

    if ( empty( $project_status ) )
    {
        $project = get_instance()->db->select('status')->from(db_prefix().'projects')->where('id',$project_id)->get()->row();

        if ( !empty( $project->status ) )
        {

            $status_data = get_project_status_by_id( $project->status );

            if ( !empty( $status_data[ 'name' ] ) )
                $project_status = $status_data[ 'name' ];

        }

    }

    $fields['{project_id}']     = $project_id;
    $fields['{project_status}'] = $project_status;

    return $fields;

} , 10 , 2 );


hooks()->add_filter('proposal_merge_fields', function( $fields , $args )
{

    $proposal_id = $args['id'];

    $proposal_status = '';

    if ( !empty( $args['proposal']->status ) )
    {

        $proposal_status = format_proposal_status( $args['proposal']->status , '' , false );

    }

    $fields['{proposal_status}'] = $proposal_status;

    return $fields;

} , 10 , 2 );

