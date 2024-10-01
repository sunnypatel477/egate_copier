<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <?php echo form_open_multipart($this->uri->uri_string(), ['id' => 'set-up-form']); ?>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-mb-2">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                        <span class="tw-text-lg"><?php echo $title; ?></span>

                    </h4>

                </div>

                <div class="panel_s">
                    <div class="panel-body">

                        <?php $attrs =  ['autofocus' => true]; ?>
                        <?php echo render_input('instax_printing_email_inquiry', 'instax_printing_email_inquiry', get_option('instax_printing_email_inquiry'), 'email', $attrs); ?>

                        <div class="checkbox checkbox-primary checkbox-inline">
                            <input type="checkbox" name="instax_printing_email_inquiry_active" id="instax_printing_email_inquiry_active" <?php echo (get_option('instax_printing_email_inquiry_active') == 1) ? 'checked' : ''; ?>>
                            <label for="showtostaff"><?php echo _l('instax_printing_email_inquiry_active'); ?></label>
                        </div>
                        <hr>
                        <h4>Print Button Count : <span style="color:red"><?php echo get_option('print_btn_click_count') ?></span></h4>
                        <h4>Order Button Count : <span style="color:red"><?php echo get_option('order_print_btn_click_count') ?></span></h4>
                        <div class="form-group">
                            <label class="col-md-3">Print Button Display :</label>
                            <div class="form-check form-check-inline col-md-1">
                                <input class="form-check-input " type="radio" name="print_button_display" id="print_button_display_all" value="all" <?php echo (get_option('print_button_display') == 'all') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="print_button_display_all">All</label>
                            </div>
                            <div class="form-check form-check-inline col-md-3">
                                <input class="form-check-input" type="radio" name="print_button_display" id="print_button_display_logged_in" value="logged_in" <?php echo (get_option('print_button_display') == 'logged_in') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="print_button_display_logged_in">Logged In User</label>
                            </div>
                            <div class="form-check form-check-inline col-md-3">
                                <input class="form-check-input" type="radio" name="print_button_display" id="print_button_display_hide" value="hide" <?php echo (get_option('print_button_display') == 'hide') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="print_button_display_hide">Hide</label>
                            </div>
                        </div>

                    </div>

                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            <?php echo _l('submit'); ?>
                        </button>
                    </div>

                </div>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
<script>
    $(function() {

        appValidateForm($('#set-up-form'));
    });
</script>
</body>

</html>