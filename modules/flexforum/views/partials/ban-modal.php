<div class="modal fade" id="flexforum_ban_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(flexforum_admin_url('bans'), ['id' => 'flexforum_ban_form']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="add-title"><?php echo flexforum_lang('new_ban'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_select('user_id', $contacts, ['id', 'firstname', 'lastname'], flexforum_lang('user'), '', [], [], '',  '', false) ?>
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