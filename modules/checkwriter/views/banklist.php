<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary pull-left" data-toggle="modal" data-target="#addbankinfoModal"><?php echo _l('new_bank_list'); ?></button><br>
            </div><br><br>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php hooks()->do_action('forms_table_start'); ?>
                        <?php render_datatable([
                            _l('id'),
                            _l('bank_name'),
                            _l('account_no'),
                            _l('account_notes'),
                            _l('action'),
                        ], 'bank_list'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addbankinfoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open_multipart(admin_url('checkwriter/add_update_bank'), array('id' => 'add_update_bank-form')); ?>
        <div class="modal-content width-100">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span id="bank_label"><?php echo _l('new_bank_list'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php echo form_hidden('bank_id', ''); ?>
                    <div class="col-md-6">
                        <?php echo render_input('bank_name', 'bank_name', ''); ?>
                    </div>

                    <div class="col-md-6">
                        <?php echo render_input('account_no', 'account_no', ''); ?>
                    </div>

                    <div class="col-md-6">
                        <?php echo render_input('account_notes', 'account_notes', ''); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button id="sm_btn" type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div>
<?php init_tail(); ?>
<script>
    $(function() {
        initDataTable('.table-bank_list',
            admin_url + 'checkwriter/table/', undefined, undefined,
            'undefined', [0, 'desc']);
    });

    $(function() {
        $(document).on("click", "#edit_bank_detail", function() {
            var id = $(this).attr("data-id");
            $.ajax({
                type: "post",
                url: admin_url + "checkwriter/edit_bank_details/" + id,
                success: function(response) {
                    $('#addbankinfoModal').modal('show');
                    var data = JSON.parse(response);
                    console.log("data",data);
                    $("input[name='bank_id']").val(data.id);
                    $("input[name='bank_name']").val(data.bank_name);
                    $("#account_no").val(data.account_no);
                    $("#account_notes").val(data.account_notes);
                    $("#bank_label").text("Edit Bank Details");
                },
            });
        });

        $(document).on("click", "#delete_bank_detail", function() {
            var id = $(this).attr("data-id");
            if (confirm('Are you sure you want to delete this item?')) {
                $.ajax({
                    type: "post",
                    url: admin_url + "checkwriter/delete_bank_details/" + id,
                    success: function(response) {
                       window.location.reload();
                    },
                });
            }
        });
    });
</script>
</body>

</html>