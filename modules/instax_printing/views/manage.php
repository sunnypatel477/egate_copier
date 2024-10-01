<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">

                        <hr class="hr-panel-heading">
                        <a href="#" data-toggle="modal" data-target="#instax_printing_admin_bulk_actions" class="hide bulk-actions-btn table-btn" data-table=".table-instax_printing_admin"><?php echo _l('bulk_actions'); ?></a>
                        <?php $this->load->view('_bulk_actions'); ?>
                        <?php $this->load->view('payment'); ?>
                        <?php
                        $table_data = array(
                            _l('id'),
                            _l('status'),
                            'Frame Type',
                            _l('name'),
                            _l('contact'),
                            _l('email'),
                            '<i class="fa fa-camera" aria-hidden="true"></i>',
                            _l('order_from'),
                            _l('order_number'),
                           
                            _l('address'),
                            '<i class="fa fa-truck" aria-hidden="true"></i>',
                            _l('Amount'),
                            _l('Date'),
                            _l('File'),
                            _l('created_date'),


                        );
                        array_unshift($table_data, [
                            'name'     => '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="instax_printing_admin"><label></label></div>',

                        ]);
                        array_push($table_data, _l('actions'));
                        render_datatable($table_data, 'instax_printing_admin', ['number-index-1'], [
                            'data-last-order-identifier' => 'instax_printing_admin',
                        ])
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php init_tail(); ?>
<script>
    $(function() {
        initDataTable('.table-instax_printing_admin', window.location.href, [0], [0], '', [1, 'DESC']);
        // $('.table-job_reports').addClass('table-responsive');

        // $("body").on('click', '.job_reports_send_mail_open', function (e) {
        //     e.preventDefault();
        //     $('#send_to').val($(this).attr('data-email_address'));
        //     $('#job_id').val($(this).attr('data-job_id'));
        //     $('#job_reports_send_to_client_modal').modal('show');
        // });

        $('body').on("click",".btn-payment", function() {
            var id = $(this).attr("data-id");
            $("#instax_printing_admin_payment input[name='inquiry_id']").val(id);
            $("#instax_printing_admin_payment").modal("show");
            
        });
        $('body').on("click",".btn-payment-edit", function() {
            var id = $(this).attr("data-id");
            var amount = $(this).attr("data-amount");
            var paymentdate = $(this).attr("data-paymentdate");
            var attachment_url = $(this).attr("data-attachment_url");
            
var parts = attachment_url.split("/");
var fileName = parts[parts.length - 1];
            var container = $(".attachment_url");
            container.empty();
    var newParagraph = $('<a href="'+attachment_url+'" target="_blank" class="input-group-addon attachment_url">'+fileName+'<i class="fa fa-download"></i></a>');
    
    container.append(newParagraph);
            $("#instax_printing_admin_payment input[name='inquiry_id']").val(id);
            $("#instax_printing_admin_payment input[name='amount']").val(amount);
            $("#instax_printing_admin_payment input[name='paymentdate']").val(paymentdate);
            
            $("#instax_printing_admin_payment").modal("show");
            
        });
        $('#paymentModalForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: admin_url + "instax_printing/payment",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                
                if (response.success == true) {
                    alert_float('success', response.message);
                    window.location.reload();
                } else {
                    alert_float('danger', response.message);
                }

                $('#paymentModalForm').modal('hide');
                $('#paymentModalForm')[0].reset();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    });
    function instax_printing_admin_bulk_action(event) {
            if (confirm_delete()) {
                var ids = [],
                    data = {},
                    mass_delete = $("#mass_delete").prop("checked");
                if (mass_delete === true) {
                    data.mass_delete = true;
                }
                var rows = $('.table-instax_printing_admin').find("tbody tr");
                $.each(rows, function() {
                    var checkbox = $($(this).find("td").eq(0)).find("input");
                    if (checkbox.prop("checked") === true) {
                        ids.push(checkbox.val());
                    }
                });
                data.ids = ids;
                $(event).addClass("disabled");
                setTimeout(function() {
                    $.post(admin_url + "instax_printing/bulk_action", data).done(function() {
                        window.location.reload();
                    });
                }, 200);
            }
        }
</script>
</body>

</html>