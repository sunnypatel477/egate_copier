<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head();?>

<div id="wrapper" >

    <div class="content">

        <div class="row">

            <div class="panel_s">

                <div class="panel-heading">

                    <div>

                        <strong style="font-size: 20px"> <?php echo _l('email_template_manage_webhook')?> </strong>

                        <a style="float: right" href="#" onclick="fnc_template_dlg( 0 , 0 ) " class="btn btn-primary" > <i class="fa fa-add"> </i> <?php echo _l('email_template_manage_add_new_webhook')?> </a>

                    </div>

                </div>


                <div class="panel-body">

                    <div class="table-responsive ">

                        <table class="table table-templates">
                            <thead>
                                <th><?php echo _l('id')?></th>
                                <th><?php echo _l('email_template_manage_webhook_name')?></th>
                                <th><?php echo _l('email_template_manage_template_name')?></th>
                                <th><?php echo _l('email_template_manage_webhook_format')?></th>
                                <th><?php echo _l('email_template_manage_status')?></th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<div class="modal fade" id="template_definition" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content" id="template_definition_content">


        </div>

    </div>

</div>


<?php init_tail(); ?>


<style>

    .email-template-loading-spinner {
        border: 4px solid #3498db; /* Spinner color */
        border-radius: 50%;
        border-top: 4px solid #fff;
        width: 35px;
        height: 35px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

</style>


<script>


    $(document).ready(function (){

        initDataTable('.table-templates', admin_url + 'email_template_manage/webhook_list', [], [], [] , [0,"desc"] );

    })

    function fnc_template_dlg( record_id , is_duplicate = 0 )
    {

        $('#template_definition_content').html('<div style="margin: 25px"> <div class="email-template-loading-spinner"></div> </div>');
        $('#template_definition').modal();


        requestGet(admin_url+"email_template_manage/webhook_detail/"+record_id+"/"+is_duplicate ).done(function ( response ){

            $('#template_definition_content').html(response).promise().done(function (){

                init_selectpicker();

            });


        });

    }


</script>

