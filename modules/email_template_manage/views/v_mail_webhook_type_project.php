<div class="col-md-6">

    <div class="form-group">

        <div class="checkbox checkbox-primary">

            <input type="checkbox" id="send_to_staff" name="send_to_staff" <?php echo !empty( $record_data->staff_active ) ? 'checked' : '' ?> value="1">

            <label for="send_to_staff"><?php echo _l('email_template_manage_send_to_project_members') ?></label>

        </div>

    </div>

</div>

<div class="col-md-6">

    <div class="form-group">

        <div class="checkbox checkbox-primary">

            <input type="checkbox" id="send_to_client" name="send_to_client" <?php echo !empty( $record_data->client_active ) ? 'checked' : '' ?> value="1">

            <label for="send_to_client"><?php echo _l('email_template_manage_send_to_client') ?></label>

        </div>

    </div>

</div>

<?php

include __DIR__.'/v_mail_trigger_rel_type_option_inc.php';

?>

<div class="col-md-12">

    <div class="form-group">

        <label class="control-label"><?php echo _l('email_template_manage_webhook_trigger_status_select')?></label>

        <select class="selectpicker" required="required" multiple="multiple" id="status" name="status[]"
                data-width="100%" data-show-subtext="true" data-live-search="true"
                data-none-selected-text="<?php echo _l('email_template_manage_webhook_trigger_status_select')?>" >

            <?php

            foreach ( $status as $statu )
            {

                $status_text    = $statu["name"];
                $statu_id       = $statu["id"];

                $selected = '';
                if( in_array( $statu_id , $record_status ) )
                    $selected = 'selected';

                echo "<option $selected value='$statu_id'>$status_text</option>";
            }

            ?>
        </select>
    </div>

</div>

