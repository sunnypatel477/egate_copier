

    <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title">

            <span class="edit-title"><?php echo _l('email_template_manage_special')?></span>

        </h4>

    </div>

    <?php echo form_open('email_template_manage/special_save' ); ?>

        <input type="hidden" name="id" id="id" value="<?php echo empty( $duplicate ) ? $record_id : 0 ?>">

        <div class="modal-body">

            <div class="row">

                <div class="col-md-12" >
                    <?php $value = !empty( $data->special_name ) ? $data->special_name : '' ; ?>
                    <?php echo render_input('special_name', 'email_template_manage_special_title' , $value , 'input' , [ 'required' => true ] ); ?>
                </div>

                <div class="col-md-12">
                    <?php $value = !empty( $data->template_id ) ? $data->template_id : '' ; ?>
                    <?php echo render_select( 'template_id' , $templates , [ 'id' , [ 'template_name' ] ] , 'email_template_manage' , $value ,  [ 'required' => true ] );?>
                </div>

            </div>

            <div class="clearfix"></div>

            <div class="row">

                <div class="col-md-6">

                    <?php $rel_type_value = !empty( $data->rel_type ) ? $data->rel_type : 'staff' ; ?>

                    <div class="form-group">
                        <label class="control-label"><?php echo _l('email_template_manage_related_type')?></label>
                        <select name="rel_type" id="rel_type" data-width="100%" onchange="email_template_special_rel_type_changed()"
                                data-live-search="true" class="selectpicker" required="true"
                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

                            <?php
                            foreach ( $special_rel_types as $option )
                            {

                                $selected = $rel_type_value == $option['value'] ? "selected" : "" ;

                                echo "<option $selected value='".$option['value']."'> ".$option['text']." </option>";

                            }
                            ?>
                        </select>
                    </div>

                </div>

                <div class="col-md-6">
                    <?php $value = !empty( $data->sending_hour ) ? $data->sending_hour : 0 ; ?>

                    <div class="form-group">
                        <label class="control-label"><?php echo _l('email_template_manage_send_hour')?></label>
                        <select name="sending_hour" id="sending_hour" data-width="100%"
                                data-live-search="true" class="selectpicker"
                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

                            <?php

                            for ( $hour = 0 ; $hour < 25 ; $hour++ )
                            {

                                $selected = $value == $hour ? "selected" : "" ;

                                echo "<option $selected value='$hour'> $hour </option>";

                            }
                            ?>
                        </select>
                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group staff_option_group <?php echo $rel_type_value != 'staff' ? 'hide' : '' ?>">
                        <label class="control-label"><?php echo _l('email_template_manage_periodic_date')?></label>

                        <select name="staff_option" id="staff_option" data-width="100%"
                                data-live-search="false" class="selectpicker"
                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

                            <?php
                            $value = !empty($data->date_field_name) ? $data->date_field_name : '';

                            foreach ( $staff_options as $option )
                            {

                                $selected = $value == $option['field'] ? "selected" : "" ;

                                echo "<option $selected value='".$option['field']."'> ".$option['text']." </option>";

                            }
                            ?>
                        </select>

                    </div>

                    <div class="form-group contact_option_group <?php echo $rel_type_value != 'contact' ? 'hide' : '' ?>">
                        <label class="control-label"><?php echo _l('email_template_manage_periodic_date')?></label>

                        <select name="contact_option" id="contact_option" data-width="100%"
                                data-live-search="false" class="selectpicker"
                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

                            <?php
                            foreach ( $contact_options as $option )
                            {

                                $selected = $value == $option['field'] ? "selected" : "" ;

                                echo "<option $selected value='".$option['field']."'> ".$option['text']." </option>";

                            }
                            ?>
                        </select>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="form-group">
                        <label class="control-label"><?php echo _l('expense_repeat_every')?></label>
                        <select name="repeat_every" id="sending_hour" data-width="100%"
                                data-live-search="true" class="selectpicker"
                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

                            <?php $value = !empty($data->repeat_every) ? $data->repeat_every : ''; ?>

                            <?php foreach ( $reply_data as $reply ) { ?>

                                <option <?php echo $value == $reply['value'] ? 'selected' : '' ?> value="<?php echo $reply['value']?>"> <?php echo $reply['text']?> </option>

                            <?php } ?>

                        </select>
                    </div>

                </div>


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


