

    <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title">

            <span class="edit-title"><?php echo _l('email_template_manage_webhook')?></span>

        </h4>

    </div>

    <?php echo form_open('email_template_manage/webhook_save' ); ?>

        <input type="hidden" name="id" id="id" value="<?php echo empty( $duplicate ) ? $record_id : 0 ?>">

        <div class="modal-body">

            <div class="row">

                <div class="col-md-12" >
                    <?php $value = !empty( $data->webhook_name ) ? $data->webhook_name : '' ; ?>
                    <?php echo render_input('webhook_name', 'email_template_manage_webhook_name' , $value , 'input' , [ 'required' => true ] ); ?>
                </div>

                <div class="col-md-12">
                    <?php $value = !empty( $data->template_id ) ? $data->template_id : '' ; ?>
                    <?php echo render_select( 'template_id' , $templates , [ 'id' , [ 'template_name' ] ] , 'email_template_manage' , $value ,  [ 'required' => true ] );?>
                </div>

            </div>

            <div class="clearfix"></div>

            <div class="row">

                <div class="col-md-12">

                    <?php $value = !empty( $data->webhook_trigger ) ? $data->webhook_trigger : '' ; ?>

                    <?php echo render_select( 'webhook_trigger' , $webhooks , [ 'value' , [ 'text' ] ] , 'email_template_manage_webhook_format' , $value , [ 'required' => true ]  )?>


                </div>

            </div>

            <div class="row" id="email-template-rel-type-content">



            </div>


        </div>

        <div class="modal-footer">

            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>

            <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>

        </div>

    <?php echo form_close(); ?>


<script>

    $(document).ready(function (){

        $('#webhook_trigger').change(function (){

            email_template_rel_type_content();

        })

        <?php if ( !empty( $data->webhook_trigger ) ) { ?>

            email_template_rel_type_content();

        <?php } ?>

    })

    function email_template_rel_type_content()
    {

        $('#email-template-rel-type-content').html('<div class="col-md-12"><div class="email-template-loading-spinner"></div></div>');

        requestGet("email_template_manage/webhook_type_content/<?php echo $record_id?>/" + $('#webhook_trigger').val() ).done(function (response) {

            $('#email-template-rel-type-content').html( response ).promise().done(function (){

                init_selectpicker();

            });

        });

    }

</script>


