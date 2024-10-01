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
                        <button onclick="new_flexforum_model_list(); return false;" class="btn btn-primary pull-right display-block">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            <?php echo flexforum_lang('new_modal_list'); ?>
                        </button>
                        <a href="<?php echo flexforum_admin_url() ?>" class="btn btn-link">
                            <?php echo flexforum_lang(); ?>
                        </a>
                    </div>
                    <div class="panel-body">
                        <table class="table dt-table">
                            <thead>
                                <th>
                                    <?php echo flexforum_lang('code'); ?>
                                </th>
                                <th>
                                    <?php echo flexforum_lang('model_name'); ?>
                                </th>
                                <th>
                                    <?php echo flexforum_lang('brand_name'); ?>
                                </th>
                                <th>
                                    <?php echo flexforum_lang('options'); ?>
                                </th>
                            </thead>
                            <?php foreach ($model_lists as $model_list) { ?>
                                <td>
                                    <?php echo $model_list['code']; ?>
                                </td>
                                <td>
                                    <?php echo $model_list['model_name']; ?>
                                </td>
                                <td>
                                    <?php echo $model_list['brand']; ?>
                                </td>
                                <td>
                                    <div class="tw-flex tw-items-center tw-space-x-3">
                                        <?php if (has_permission(FLEXFORUM_MODULE_NAME, '', 'edit')) { ?>
                                            <a href="#" onclick="edit_flexforum_model_list(this,<?php echo $model_list['id']; ?>); return false" data-code="<?php echo $model_list['code']; ?> " data-model_name="<?php echo $model_list['model_name']; ?> " data-brand="<?php echo $model_list['brand']; ?>" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if (has_permission(FLEXFORUM_MODULE_NAME, '', 'delete')) { ?>
                                            <a href="<?php echo flexforum_admin_url('delete_model_list/' . $model_list['id']); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                                <i class="fa-regular fa-trash-can fa-lg"></i>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('partials/modal-list'); ?>
<script>
    window.addEventListener('load', function() {

        appValidateForm($('#flexforum_model_list_form'), {
            model_name: 'required',
            brand: 'required',
            code: 'required',
        });

        // On hidden modal reset the values
        $('#flexforum_model_list_modal').on("hidden.bs.modal", function(event) {
            $('#additional').html('');
            $('#parent_id').val('');
            $('#name').val('');
            $('#flexforum_model_list_modal input').not('[type="hidden"]').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });

        $('#flexforum_model_list_modal').on("shown.bs.modal", function(event) {
            $('#flexforum_model_list_modal select[name="parent_id"]').selectpicker('render');
        });
    });

    function new_flexforum_model_list() {
        $('#flexforum_model_list_modal').modal('show');
        $('.edit-title').addClass('hide');
    }

    function edit_flexforum_model_list(invoker, id) {
        $('#additional').append(hidden_input('id', id));
        $('#flexforum_model_list_modal input[name="code"]').val($(invoker).data('code'));
        $('#flexforum_model_list_modal input[name="brand"]').val($(invoker).data('brand'));
        $('#flexforum_model_list_modal input[name="model_name"]').val($(invoker).data('model_name'));
        $('#flexforum_model_list_modal').modal('show');
        $('.add-title').addClass('hide');
    }
</script>

<?php init_tail(); ?>
</body>

</html>