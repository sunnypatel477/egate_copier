
(function($) {
    "use strict";

    $(document).ready(function (){



    });


})(jQuery);


function email_template_manage_send_mail( rel_type , rel_id  )
{

    if ( rel_type || rel_id )
    {

        tinymce.remove();
        requestGet('email_template_manage/send_mail_modal/' + rel_type+'/'+rel_id).done(function(response) {

            $('#email_template_manage_modal').html(response);

            $('#email_template_manage_modal').modal({
                show: true,
                backdrop: 'static',
                keyboard: false
            });

            tinymce.init({
                selector: "textarea"
            });

        }).fail(function(data) {

            alert_float('danger', data.responseText);

        });

    }

}



function email_template_manage_template_content()
{

    var compose_template_id = $('#compose_template_id').val();

    var compose_rel_type    = $('#compose_rel_type').val();

    var compose_rel_id      = $('#compose_rel_id').val();

    $.post( admin_url+"email_template_manage/template_content" , { template_id : compose_template_id , rel_type : compose_rel_type , rel_id : compose_rel_id } ).done(function(response) {

        response = JSON.parse(response);

        if( response.success )
        {

            $('#subject').val( response.template_data.template_subject );

            $('#template_file_content').html( response.template_data.attachment_html );

            tinymce.get('template_content').setContent( response.template_data.template_content );

        }
        else
        {

            $('#template_file_content').html( "" );

            $('#subject').val( "" );

            tinymce.get('template_content').setContent( "" );

        }


    });


}


function fnc_mail_log_detail( record_id , all_list = 0 )
{

    $.post(admin_url+"email_template_manage/mail_log_detail" , { record_id : record_id , all_list : all_list } )
        .done(function ( response ){

            response = JSON.parse(response);

            $('#email_template_manage_mail_content').html(response.content_html).promise().done(function (){});

            $('#email_template_manage_mail_modal').modal();

        });

}
