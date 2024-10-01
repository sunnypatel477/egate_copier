
<div class="col-md-6">

    <div class="form-group">

        <div class="checkbox checkbox-primary">

            <input type="checkbox" id="send_to_staff" name="send_to_staff" <?php echo !empty( $record_data->staff_active ) ? 'checked' : '' ?> value="1">

            <label for="send_to_staff"><?php echo _l('email_template_manage_send_to_sale_agent') ?></label>

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

        <label class="control-label"><?php echo _l('email_template_manage_estimate_status_select')?></label>

        <select class="selectpicker" required="required" multiple="multiple" id="status" name="status[]"
                data-width="100%" data-show-subtext="true" data-live-search="true"
                data-none-selected-text="<?php echo _l('email_template_manage_estimate_status_select')?>" >

            <?php

            foreach ( $status as $statu )
            {

                $status_text    = format_estimate_status( $statu , '', false);
                $statu_id       = $statu;

                $selected = '';
                if( in_array( $statu_id , $record_status ) )
                    $selected = 'selected';

                echo "<option $selected value='$statu_id'>$status_text</option>";

            }

            ?>
        </select>
    </div>

</div>

<div class="col-md-6">
    <select class="selectpicker" required="required" id="record_date" name="record_date" data-width="100%" data-show-subtext="true"
            data-none-selected-text="<?php echo _l('email_template_manage_date_field_select')?>" >

        <option></option>
        <option <?php echo $record_date == 'date'       ? 'selected' : '' ?>    value="date"><?php echo _l('estimate_add_edit_date')?></option>
        <option <?php echo $record_date == 'expirydate' ? 'selected' : '' ?>    value="expirydate"><?php echo _l('estimate_add_edit_expirydate') ?></option>

    </select>
</div>


<div class="col-md-6">

    <select class="selectpicker" id="record_day" name="record_day" data-width="100%" data-show-subtext="true" >

        <?php foreach ( $date_option_days as $date_option_day ) {
            echo "<option ".( $record_day == '+ '.$date_option_day ? 'selected' : ''  )." value='+ $date_option_day'>"._l('email_template_manage_day_plus_'.$date_option_day)."</option>";
        } ?>

        <option <?php echo ( $record_day == '0' || empty( $options->record_day ) ? 'selected' : '' ) ?> value="0"><?php echo _l('email_template_manage_day_equal')?></option>

        <?php foreach ( array_reverse($date_option_days) as $date_option_day ) {
            echo "<option ".( $record_day == '- '.$date_option_day ? 'selected' : ''  )." value='- $date_option_day'>"._l('email_template_manage_day_minus_'.$date_option_day)."</option>";
        } ?>

    </select>
</div>

