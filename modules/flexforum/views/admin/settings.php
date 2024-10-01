<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
init_head();
?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-heading">
                        <span class="tw-font-bold">
                            <?php echo $title ?>
                        </span>

                        <a href="<?php echo flexforum_admin_url() ?>" class="btn btn-link">
                            <?php echo flexforum_lang(); ?>
                        </a>
                    </div>
                    <div class="panel-body">
                        <?php echo form_open(flexforum_admin_url('settings'), ['id' => 'flexforum_settings_form']); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo render_yes_no_option(FLEXFORUM_SEND_EMAIL_NOTIFICATION_OPTION, flexforum_lang('send_email_notification')); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo flexforum_lang('submit'); ?>
                                </button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('partials/ban-modal'); ?>
<script>
    window.addEventListener('load', function () {

        // Validating the knowledge group form
        appValidateForm($('#flexforum_ban_form'), {
            name: 'required'
        }, manage_flexforum_categories);

        // On hidden modal reset the values
        $('#flexforum_ban_modal').on("hidden.bs.modal", function (event) {
            $('#additional').html('');
            $('#flexforum_ban_modal input').not('[type="hidden"]').val('');
            $('.add-title').removeClass('hide');
        });
    });

    function manage_flexforum_bans(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function (response) {
            window.location.reload();
        });
        return false;
    }

    function new_flexforum_ban() {
        $('#flexforum_ban_modal').modal('show');
    }
</script>

<?php init_tail(); ?>
</body>

</html>