

    <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title">

            <span class="edit-title"><?php echo _l('email_template_manage_reminder')?></span>

        </h4>

    </div>

    <?php echo form_open('email_template_manage/save_trigger' ); ?>

        <input type="hidden" name="id" id="id" value="<?php echo empty( $duplicate ) ? $record_id : 0 ?>">

        <div class="modal-body">

            <div class="row">

                <div class="col-md-12" >
                    <?php $value = !empty( $data->trigger_name ) ? $data->trigger_name : '' ; ?>
                    <?php echo render_input('trigger_name', 'email_template_manage_reminder_name' , $value , 'input' , [ 'required' => true ] ); ?>
                </div>

                <div class="col-md-6">
                    <?php $value = !empty( $data->template_id ) ? $data->template_id : '' ; ?>
                    <?php echo render_select( 'template_id' , $templates , [ 'id' , [ 'template_name' ] ] , 'email_template_manage' , $value ,  [ 'required' => true ] );?>
                </div>

                <div class="col-md-3">
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

            <div class="clearfix"></div>

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group">

                        <label for="rel_type" class="control-label"><?php echo _l('task_related_to'); ?></label>

                        <select name="rel_type" required="required" class="selectpicker" id="rel_type" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

                            <option value=""></option>

                            <?php $rel_type = !empty( $data->rel_type ) ? $data->rel_type : '' ; ?>

                            <option <?php echo $rel_type == "invoice"   ? 'selected' : '' ?> value="invoice"><?php echo _l('invoice') ?></option>
                            <option <?php echo $rel_type == "project"   ? 'selected' : '' ?> value="project"><?php echo _l('project') ?></option>
                            <option <?php echo $rel_type == "task"      ? 'selected' : '' ?> value="task"><?php echo _l('task') ?></option>
                            <option <?php echo $rel_type == "proposal"  ? 'selected' : '' ?> value="proposal"><?php echo _l('proposal') ?></option>
                            <option <?php echo $rel_type == "estimate"  ? 'selected' : '' ?> value="estimate"><?php echo _l('estimate') ?></option>
                            <option <?php echo $rel_type == "contract"  ? 'selected' : '' ?> value="contract"><?php echo _l('contract') ?></option>

                        </select>

                    </div>

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

        $('#rel_type').change(function (){

            email_template_rel_type_content();

        })

        <?php if ( !empty( $rel_type ) ) { ?>

            email_template_rel_type_content();

        <?php } ?>

    })

    function email_template_rel_type_content()
    {

        $('#email-template-rel-type-content').html('<div class="col-md-12"><div class="email-template-loading-spinner"></div></div>');

        requestGet("email_template_manage/rel_type_content/<?php echo $record_id?>/" + $('#rel_type').val() ).done(function (response) {

            $('#email-template-rel-type-content').html( response ).promise().done(function (){

                init_selectpicker();

            });

        });

    }

</script>


