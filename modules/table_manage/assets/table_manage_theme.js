
(function($) {
    "use strict";

    var tm_dt_table_array = [];

    $(document).on('init.dt', function (e, settings) {

        if ($.inArray( settings.sTableId , tm_dt_table_array) === -1)
        {

            // table header menu button
            if( tm_dt_has_moule == 1 )
            {

                var tm_dt_table_button ='<button onclick="tm_dt_open_modal( \''+settings.sTableId+'\' )" class="btn btn-default buttons-collection btn-sm btn-default-dt-options"> ' +
                                            '<a class="fa fa-tasks"></a> ' +
                                        '</button> '
                                        +
                                        '<button onclick="tm_dt_open_modal_staff_role( \''+settings.sTableId+'\' )" class="btn btn-default buttons-collection btn-sm btn-default-dt-options"> ' +
                                            '<a class="fa fa-users"></a> ' +
                                        '</button> ';


                $('#'+settings.sTableId).parents('.dataTables_wrapper').find('.dt-buttons').append(tm_dt_table_button);

            }

            tm_dt_table_array.push( settings.sTableId );


            // Table header visible prepearing

            var tm_dt_set_data = {} ;

            tm_dt_set_data['page_url']      = window.location.href;
            tm_dt_set_data['table_class']   = $('#'+settings.sTableId).attr('class');


            $.post(admin_url + "table_manage/setting/get_info" , tm_dt_set_data )

                .done(function ( response ) {

                    response = JSON.parse(response);

                    if ( response.has_record )
                    {
                        $('#'+settings.sTableId).DataTable().columns().visible(true);
                    }


                    if ( response.headers_export )
                    {

                        // adding not-export class to header
                        $('#'+settings.sTableId+' thead th').removeClass('not-export');
                        for ( var table_index in response.headers_export )
                        {

                            // adding not-export class to header
                            $('#'+settings.sTableId+' thead th').eq(table_index).addClass('not-export');

                        }

                    }

                    if( response.headers )
                    {


                        // removing the not export class for all columns
                         //$('#'+settings.sTableId+' thead th').removeClass('not-export');


                        for ( var table_index in response.headers )
                        {

                            $('#'+settings.sTableId).DataTable().column(table_index).visible( false , false );

                        }

                    }


                }) ;


        }

    });

})(jQuery);



// table header setting modale
function tm_dt_open_modal ( dt_table_id )
{

    var tm_dt_table = $('#'+dt_table_id).DataTable();

    var tm_dt_table_hidden_columns =  $('#'+dt_table_id).DataTable().columns(':hidden').indexes();

    var tm_dt_column_names = tm_dt_table.columns().header().toArray().map(function(column) { return $(column).text().trim(); });

    var tm_dt_headers = "";
    var tm_dt_exports = "";

    if( tm_dt_column_names )
    {

        // table columns
        for (var i = 0; i < tm_dt_column_names.length; i++)
        {
            var tm_dt_header_check = 'checked';

            if( tm_dt_table_hidden_columns.length > 0 )
            {

                if ( $.inArray( i , tm_dt_table_hidden_columns ) !== -1 )
                    tm_dt_header_check = '';

            }

            tm_dt_headers += '<div> <label> <input type="checkbox" dt_table_id="'+dt_table_id+'" class="tb_dt_column_checkbox" value="' + i + '" '+tm_dt_header_check+' > ' + tm_dt_column_names[i] + ' </label> </div>';
        }

        // table export info not-export
        var  tm_th_elements = tm_dt_table.columns().header();

        for (var tm_exp_index = 0; tm_exp_index < tm_th_elements.length; tm_exp_index++)
        {

            var tm_dt_header_check = 'checked';

            if ( $(tm_th_elements[tm_exp_index]).hasClass('not-export') )
            {
                 tm_dt_header_check = '';
            }

            tm_dt_exports += '<div> <label> <input type="checkbox" dt_table_id="'+dt_table_id+'" class="tb_dt_column_checkbox_export" value="' + tm_exp_index + '" '+tm_dt_header_check+' > ' + tm_dt_column_names[tm_exp_index] + ' </label> </div>';

        }


    }


    tm_content_total = '<div class="row">';

        tm_content_total += '<div class="col-md-6"> <h3>Table</h3>  '+tm_dt_headers+'</div> ';

        tm_content_total += '<div class="col-md-6"> <h3>Export</h3>  '+tm_dt_exports+'</div>  ';

    tm_content_total += ' </div> ';


    $('#table_manage_modal_body').html(tm_content_total).promise().done(function (){

        // Table
        $('.tb_dt_column_checkbox').unbind('click').bind('click',function (){

            var dt_table_id = $(this).attr('dt_table_id');
            var tm_dt_column_index = $(this).val();
            var tm_dt_column_checked = $(this).prop('checked') ? 1 : 2 ;

            $('#'+dt_table_id).DataTable().column(tm_dt_column_index).visible($(this).prop('checked'));

            var tm_dt_set_data = {} ;

            tm_dt_set_data['page_url']      = window.location.href;
            tm_dt_set_data['table_class']   = $('#'+dt_table_id).attr('class');
            tm_dt_set_data['index']         = tm_dt_column_index;
            tm_dt_set_data['checked']       = tm_dt_column_checked;

            $.post(admin_url + "table_manage/setting/set_info" , tm_dt_set_data ) ;

        })

        // Export
        $('.tb_dt_column_checkbox_export').unbind('click').bind('click',function (){

            var dt_table_id = $(this).attr('dt_table_id');
            var tm_dt_column_index = $(this).val();
            var tm_dt_column_checked = $(this).prop('checked') ? 1 : 2 ;

            var tm_dt_set_data = {} ;

            tm_dt_set_data['page_url']      = window.location.href;
            tm_dt_set_data['table_class']   = $('#'+dt_table_id).attr('class');
            tm_dt_set_data['index']         = tm_dt_column_index;
            tm_dt_set_data['checked']       = tm_dt_column_checked;

            $.post(admin_url + "table_manage/setting/set_info_export" , tm_dt_set_data ) ;

        })

    });

    $('#table_manage_modal').modal();

}


function tm_dt_open_modal_staff_role ( dt_table_id )
{

    $('#tb_dt_staff_role').selectpicker( "val",'' );
    $('#tb_dt_staff_role').selectpicker('refresh');

    var tm_dt_table = $('#'+dt_table_id).DataTable();

    var tm_dt_column_names = tm_dt_table.columns().header().toArray().map(function(column) { return $(column).text().trim(); });

    var tm_dt_headers = "<input type='hidden' id='dt_table_id_for_role' value='"+dt_table_id+"' />";
    var tm_dt_exports = '';

    if( tm_dt_column_names )
    {

        for (var i = 0; i < tm_dt_column_names.length; i++)
        {
            var tm_dt_header_check = 'checked';

            tm_dt_headers += '<div> <label> <input type="checkbox" dt_table_id="'+dt_table_id+'" class="tb_dt_column_checkbox_staff_role tb_dt_column_checkbox_staff_role_'+i+'" value="' + i + '" '+tm_dt_header_check+' > ' + tm_dt_column_names[i] + ' </label> </div>';
        }


        var  tm_th_elements = tm_dt_table.columns().header();

        for (var tm_exp_index = 0; tm_exp_index < tm_th_elements.length; tm_exp_index++)
        {

            var tm_dt_header_check = 'checked';

            if ( $(tm_th_elements[tm_exp_index]).hasClass('not-export') )
            {
                tm_dt_header_check = '';
            }

            tm_dt_exports += '<div> <label> <input type="checkbox" dt_table_id="'+dt_table_id+'" class="tb_dt_column_checkbox_staff_role_export tb_dt_column_checkbox_staff_role_export_'+tm_exp_index+'" value="' + tm_exp_index + '" '+tm_dt_header_check+' > ' + tm_dt_column_names[tm_exp_index] + ' </label> </div>';


        }

    }




    tm_content_total = '<div class="row">';

        tm_content_total += '<div class="col-md-6"> <h3>Table</h3>  '+tm_dt_headers+'</div> ';

        tm_content_total += '<div class="col-md-6"> <h3>Export</h3>  '+tm_dt_exports+'</div>  ';

    tm_content_total += ' </div> ';


    $('#table_manage_modal_body_staff_role').html(tm_content_total);

    $('#table_manage_modal_staff_role').modal();

}


/**
 * loading table headers for staff role
 */
function tm_dt_staff_role_change()
{

    $('.tb_dt_column_checkbox_staff_role').prop('checked',true);
    $('.tb_dt_column_checkbox_staff_role_export').prop('checked',true);

    if( $('#tb_dt_staff_role').val() > 0 )
    {

        var tm_dt_set_data = {} ;

        var tm_dt_table_id = $('#dt_table_id_for_role').val();

        tm_dt_set_data['page_url']      = window.location.href;
        tm_dt_set_data['table_class']   = $('#'+tm_dt_table_id).attr('class');
        tm_dt_set_data['role_id']       = $('#tb_dt_staff_role').val();


        $.post(admin_url + "table_manage/setting/get_role_info" , tm_dt_set_data )

            .done(function ( response ) {

                response = JSON.parse(response);

                if( response.headers )
                {

                    // staff role hidden index
                    for ( var table_index in response.headers )
                    {

                        $('.tb_dt_column_checkbox_staff_role_'+table_index).prop('checked',false);

                    }

                }

                if( response.headers_export )
                {

                    // staff role hidden index
                    for ( var table_index in response.headers_export )
                    {

                        $('.tb_dt_column_checkbox_staff_role_export_'+table_index).prop('checked',false);

                    }

                }


            }) ;

    }

}


/**
 * saving table headers for staff role.
 */
function tm_dt_staff_role_save()
{
    var tm_dt_hiddens = [];
    var tm_dt_hiddens_export = [];

    $('.tb_dt_column_checkbox_staff_role:not(:checked)').each(function(){

        tm_dt_hiddens.push( $(this).val() );

    })

    $('.tb_dt_column_checkbox_staff_role_export:not(:checked)').each(function(){

        tm_dt_hiddens_export.push( $(this).val() );

    })

    if( $('#tb_dt_staff_role').val() > 0 )
    {
        var tm_dt_set_data = {} ;

        var tm_dt_table_id = $('#dt_table_id_for_role').val();

        tm_dt_set_data['page_url']      = window.location.href;
        tm_dt_set_data['table_class']   = $('#'+tm_dt_table_id).attr('class');
        tm_dt_set_data['role_id']       = $('#tb_dt_staff_role').val();
        tm_dt_set_data['hiddens']       = tm_dt_hiddens;
        tm_dt_set_data['hiddens_export']= tm_dt_hiddens_export;


        $.post(admin_url + "table_manage/setting/set_role_info" , tm_dt_set_data )
            .done(function ( response ) {
                alert_float( "success" , "Added Successfully" );
            });

    }
    else
        $('#tb_dt_staff_role').focus();

}

