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

                        <button onclick="new_flexforum_ban(); return false;"
                            class="btn btn-primary pull-right display-block">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            <?php echo flexforum_lang('ban_user'); ?>
                        </button>

                        <a href="<?php echo flexforum_admin_url() ?>" class="btn btn-link">
                            <?php echo flexforum_lang(); ?>
                        </a>
                    </div>
                    <div class="panel-body">
                        <div class="panel-table-full">
                            <table class="table dt-table">
                                <thead>
                                    <th>
                                        <?php echo flexforum_lang('name'); ?>
                                    </th>
                                    <th>
                                        <?php echo flexforum_lang('options'); ?>
                                    </th>
                                </thead>
                                <tbody>
                                    <?php foreach ($bans as $ban) { ?>
                                        <tr>
                                            <td>
                                                <?php echo $ban['name']; ?>
                                            </td>
                                            <td>
                                                <div class="tw-flex tw-items-center tw-space-x-3">
                                                    <?php if (has_permission(FLEXFORUM_MODULE_NAME, '', 'delete')) { ?>
                                                        <a href="<?php echo flexforum_admin_url('delete_ban/' . $ban['id']); ?>"
                                                            class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                                            <i class="fa-regular fa-trash-can fa-lg"></i>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
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