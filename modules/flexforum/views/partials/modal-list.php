<div class="modal fade" id="flexforum_model_list_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(flexforum_admin_url('model_list'), ['id' => 'flexforum_model_list_form']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo flexforum_lang('edit_model_list'); ?></span>
                    <span class="add-title"><?php echo flexforum_lang('new_model_list'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_input('model_name',flexforum_lang('model_name'),''); ?>
                    </div>
                    <div class="col-md-12">
                        <?php echo render_input('brand', flexforum_lang('brand', '', false)); ?>
                    </div>
                    <div class="col-md-12">
                        <?php echo render_input('code', flexforum_lang('code', '', false)); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo flexforum_lang('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo flexforum_lang('submit'); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div>
    <!-- /.modal-dialog -->
</div>