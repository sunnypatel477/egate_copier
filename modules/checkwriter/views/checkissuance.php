<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <div class="col-md-3 pull-right">
                            <?php
                            $all_bank = get_bank_details();
                            echo render_select('bank_filter', $all_bank, ['id', 'bank_name'], '', '', ['data-actions-box' => true], [], '', '', true); ?>
                        </div>
                        <?php hooks()->do_action('forms_table_start'); ?>
                        <?php render_datatable([
                            _l('date'),
                            _l('payee_to'),
                            _l('bank_name'),
                            _l('check_no'),
                            _l('check_date'),
                            _l('amount'),
                            _l('check_note'),
                            _l('note'),
                            _l('action'),
                        ], 'checkIssuancelist');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addcheckwriterModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open_multipart(admin_url('checkwriter/update_check_historys'), array('id' => 'update_history-form')); ?>
        <div class="modal-content width-100">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span id="history_label"><?php echo _l('edit_history'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php echo form_hidden('history_id', ''); ?>
                    <div class="col-md-6">
                        <?php echo render_input('payee_to', 'payee_to', '', 'text', array('readonly' => true)); ?>
                    </div>

                    <div class="col-md-6">
                        <?php
                        $all_bank = get_bank_details();
                        echo render_select('bank', $all_bank, ['id', 'bank_name'], 'bank', '', ['data-actions-box' => true], [], '', '', true); ?>
                    </div>

                    <div class="col-md-6">
                        <?php echo render_date_input('date', 'date', '', array('readonly' => true)); ?>
                    </div>

                    <div class="col-md-6">
                        <?php echo render_date_input('check_date', 'check_date', ''); ?>
                    </div>

                    <div class="col-md-6">
                        <?php echo render_input('check_no', 'check_no', '', 'text'); ?>
                    </div>

                    <div class="col-md-6">
                        <?php echo render_input('amount', 'amount', '', 'text', array('readonly' => true)); ?>
                    </div>

                    <div class="col-md-6">
                        <?php echo render_input('note', 'note', '', 'text', array('readonly' => true)); ?>
                    </div>

                    <div class="col-md-6">
                        <?php echo render_input('check_note', 'check_note', ''); ?>
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
        var check_ServerParams = {}
        check_ServerParams['bank_filter'] = '[name="bank_filter"]';
           
        var table =     initDataTable('.table-checkIssuancelist',
                admin_url + 'checkwriter/checkIssuancelisttable/', undefined, undefined,
                check_ServerParams, [0, 'desc']);

        $('#bank_filter').change(function() {
            table.ajax.reload();
        });
    });

    appValidateForm($('#update_history-form'), {
        bank: 'required',
        check_date: 'required',
        
        check_no: {
            required : true,
            remote: {
                url: admin_url + "checkwriter/check_no_exists",
                type: 'post',
                data: {
                    check_no: function() {
                        return $('input[name="check_no"]').val();
                    },
                    id: function() {
                        return $('input[name="history_id"]').val();
                    },
                }
            },
        }
      
    },checkhistorysubmithandler,{check_no :{
        remote : 'Check No must be unique.'
    }});

    function checkhistorysubmithandler(form){
        form.submit();
        // $(form).submit();
    }
    $(function() {
        $(document).on("click", "#edit_check_history", function() {
            var id = $(this).attr("data-id");
            $.ajax({
                type: "post",
                url: admin_url + "checkwriter/edit_check_historys/" + id,
                success: function(response) {
                    $('#addcheckwriterModal').modal('show');
                    var data = JSON.parse(response);
                    $("input[name='history_id']").val(data.id);
                    $("input[name='payee_to']").val(data.vendor_payee);
                    $("select[name='bank']").selectpicker('destroy');
                    $("select[name='bank']").val(data.bank);
                    $("select[name='bank']").selectpicker('refresh');
                    $("#date").val(data.date);
                    $("#check_date").val(data.check_date);
                    $("#check_no").val(data.check_no);
                    $("#check_note").val(data.check_note);
                    $("#amount").val(data.amount);
                    $("#note").val(data.note);
                },
            });
        });

        $(document).on("click", "#delete_check_history", function() {
            var id = $(this).attr("data-id");
            if (confirm('Are you sure you want to delete this item?')) {
                $.ajax({
                    type: "post",
                    url: admin_url + "checkwriter/deletecheckhistory/" + id,
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