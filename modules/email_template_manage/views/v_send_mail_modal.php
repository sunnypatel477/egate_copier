<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<?php echo form_open_multipart('email_template_manage/send_mail', ['id' => 'template-form-send-mail'] );?>

    <?php

    echo "<input type='hidden' name='rel_type' id='compose_rel_type' value='$rel_type' />";

    echo "<input type='hidden' name='rel_id' id='compose_rel_id' value='$rel_id' />";

    ?>

    <div class="modal-dialog modal-lg">

        <div class="modal-content ">

            <div class="modal-header" style="margin: 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color:black;">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('email_template_manage_compose_email'); ?></h4>
            </div>

            <div class="modal-body">

                <div class="row">


                    <?php if ( !empty( $smtp_settings ) ) : ?>

                        <div class="col-md-12">

                            <div class="form-group">

                                <label class="control-label"><?php echo _l('email_template_manage_use_smtp')?></label>
                                <select name="smtp_setting_id" id="smtp_setting_id" class="form-control selectpicker"
                                        data-live-search="true" data-width="100%"
                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

                                    <option value=""><?php echo _l('email_template_manage_use_system_smtp', get_option('smtp_email') )?></option>

                                    <?php foreach ( $smtp_settings as $smtp ) { ?>

                                        <option value="<?php echo $smtp->id?>"> <?php echo $smtp->company_name?> </option>

                                    <?php } ?>

                                </select>

                            </div>

                        </div>

                    <?php endif; ?>


                    <div class="col-md-12">

                        <div class="form-group">

                            <label class="control-label"><?php echo _l('email_template_manage_templates')?></label>
                            <select name="compose_template_id" id="compose_template_id" class="form-control selectpicker"
                                    data-live-search="true" data-width="100%"
                                    data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

                                <option value=""></option>

                                <?php if ( !empty( $templates ) ) : ?>

                                    <?php foreach ( $templates as $template ) { ?>

                                        <option value="<?php echo $template->id?>"> <?php echo $template->template_name?> </option>

                                    <?php } ?>

                                <?php endif; ?>

                            </select>

                        </div>

                    </div>

                    <?php if ( !empty( $emails ) ) { ?>

                        <div class="col-md-12">

                            <div class="form-group">

                                <label class="control-label"><?php echo _l('email_template_manage_to') ?></label>

                                <select name="mail_to_arr[]" data-live-search="true" data-width="100%"

                                        multiple="multiple" class="selectpicker" required

                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

                                    <?php

                                    foreach ( $emails as $email => $email_to )
                                    {

                                        echo "<option selected value='".$email."'> $email_to </option>";

                                    }
                                    ?>

                                </select>

                            </div>

                        </div>

                    <?php } else { ?>

                        <div class="col-md-12">
                            <?php echo render_input('mail_to',_l('email_template_manage_to'), "" , 'email' , [ 'required' => true ] ); ?>
                        </div>

                    <?php } ?>



                    <div class="col-md-12">
                        <?php echo render_input('mail_cc',_l('email_template_manage_cc') , '' , 'email'); ?>
                    </div>

                    <div class="col-md-12">
                        <?php echo render_input('subject',_l('email_template_manage_email_subject'),'','' , [ 'required' => true ] ); ?>
                    </div>

                    <div class="col-md-12">

                        <textarea class="tinymce tinymce-manual" id="template_content" name="template_content"></textarea>

                    </div>

                    <div class="col-md-12">
                        <br />
                        <div class="row attachments">
                            <div class="attachment">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="attachment" class="control-label"><?php echo _l('add_task_attachments'); ?></label>
                                        <div class="input-group">
                                            <input type="file" extension="<?php echo str_replace('.','',get_option('allowed_files')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="attachments[0]">
                                            <span class="input-group-btn">
												<button class="btn btn-success add_more_attachments p8" type="button"><i class="fa fa-plus"></i></button>
											</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="row">

                    <div class="col-md-12">

                        <div class="col-md-12" id="template_file_content">

                        </div>

                    </div>

                </div>


            </div>

            <div class="modal-footer">

                <div class="row">

                    <div class="col-md-6">
                        <div id="email_template_manage_loading_message"></div>
                    </div>

                    <div class="col-md-6">

                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>

                        <button type="button" class="btn btn-primary" onclick="email_compose_send_mail()" ><?php echo _l('send'); ?></button>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php echo form_close()?>


    <script>

        $(document).ready(function (){

            tinyMCE.remove(".tinymce-manual");

            init_editor('textarea[name="template_content"]', {
                urlconverter_callback: 'merge_field_format_url',
            });


            init_selectpicker();

            $('#compose_template_id').on('changed.bs.select', function () {

                email_template_manage_template_content();

            });


        })

        function email_compose_send_mail()
        {
            $('#email_template_manage_loading_message').html('');

            if ( !email_compose_validate_form() )
            {
                event.preventDefault();
                return false;
            }

            $('#email_template_manage_loading_message').html(' <div> <div class="email-template-loading-spinner"></div> <strong style="float: left;margin-left: 50px;margin-top: -30px;"> Sending please wait  </strong> </div>');

            $('#template-form-send-mail').submit();

        }

        function email_compose_validate_form() {

            var isValid = true;

            $('#template-form-send-mail [required]').each(function() {

                if (  $.trim( $(this).val() ) === '')
                {
                    alert_float( 'warning' , "<?php echo _l('email_template_manage_required_field')?>" );

                    isValid = false;
                    return false;
                }

            });

            return isValid;
        }

    </script>

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
<?php
