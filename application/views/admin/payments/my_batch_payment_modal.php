<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="batch-payment-modal">
    <div class="modal-dialog modal-xxl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('add_batch_payments') ?></h4>
            </div>
            <?php echo form_open('admin/payments/add_batch_payment', ['id' => 'batch-payment-form']); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group select-placeholder">
                            <label for="batch-payment-filter"><?php echo _l('customer') ?></label>
                            <select id="batch-payment-filter" class="selectpicker" name="client_filter" data-width="100%" data-none-selected-text="<?php echo _l('batch_payment_filter_by_customer') ?>">
                                <option value=""></option>
                                <?php foreach ($customers as $customer) { ?>
                                    <option value="<?php echo e($customer->userid); ?>"><?php echo e($customer->company); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-sm-2">
                        <label style="color: red;" for="received_payment"><?php echo _l('received_payment') ?></label>
                        <input name="received_payment" class="form-control" type="number" id="received_payment_total" value="">
                    </div>
                    <div class="col-sm-2">
                        <label style="color: red;" for="received_amount"><?php echo _l('received_amount') ?></label>
                        <input name="received_amount" class="form-control" type="text" id="receive_amount_total" value="" readonly>
                    </div>
                    <div class="col-sm-2">
                        <label style="color: red;" for="remaining_balance"><?php echo _l('remaining_balance') ?></label>
                        <input name="remaining_balance" class="form-control" type="text" id="remaining_balance_total" value="" readonly>
                    </div>
                    <div class="col-sm-2">
                        <label style="color: red;" for="bank_all"><?php echo _l('bank') ?></label>
                        <input name="bank_all" class="form-control" type="text" id="bank_all" value="">
                    </div>
                    <div class="col-sm-2">
                        <label style="color: red;" for="payment_mode"><?php echo _l('payment_mode') ?></label>

                        <!-- <input name="paymentmode" class="form-control" type="text" id="payment_mode_all" value="" readonly> -->


                        <?php
                        $CI = get_instance();
                        $payment_modes = $CI->payment_modes_model->get(); ?>

                        <select class="selectpicker" name="paymentmode" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" name="payment_mode" id="payment_mode_all">
                            <option value=""></option>
                            <?php foreach ($payment_modes as $mode) { ?>
                                <option value="<?php echo $mode['id']; ?>"><?php echo $mode['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label style="color: red;" for="transaction_id"><?php echo _l('transaction_id') ?></label>
                        <input name="transaction_id" class="form-control" type="text" id="transaction_id_all" value="">
                    </div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><strong><?php echo _l('batch_payments_table_invoice_number_heading'); ?>
                                                #</strong></th>
                                        <th><strong><?php echo _l('batch_payments_table_payment_date_heading'); ?></strong>
                                        </th>
                                        <th><strong><?php echo _l('batch_payments_table_payment_mode_heading'); ?></strong>
                                        </th>
                                        <th><strong><?php echo _l('batch_payments_table_bank_heading'); ?></strong>
                                        </th>
                                        <th><strong><?php echo _l('batch_payments_table_transaction_id_heading'); ?></strong>
                                        </th>
                                        <th><strong><?php echo _l('batch_payments_table_amount_received_heading'); ?></strong>
                                        </th>
                                        <th><strong><?php echo _l('batch_payments_table_invoice_balance_due'); ?></strong>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($invoices as $index => $invoice) { ?>
                                        <tr class="batch_payment_item" data-clientid="<?php echo e($invoice->clientid); ?>" data-invoiceId="<?php echo $invoice->id ?>">
                                            <td>
                                                <input class="checkbox" name="check_batch_invoice[]" type="checkbox" id="check_<?php echo $index; ?>" value=<?php echo $index ?>">
                                                <label for="check_batch_invoice"></label>

                                            </td>
                                            <td>
                                                <a href="<?php echo admin_url('invoices/list_invoices/' . $invoice->id); ?>" target="_blank">
                                                    <?php echo format_invoice_number($invoice->id) ?>
                                                </a><br>
                                                <a class="text-dark" href="<?php echo admin_url('clients/client/' . $invoice->clientid); ?>" target="_blank">
                                                    <?php echo $invoice->company ?>
                                                </a><br>
                                                <?php ?>
                                                <a class="text-dark" target="_blank">
                                                    <?php echo delivery_note_invoice_reference_number($invoice->id) ?>
                                                </a>

                                                <input type="hidden" name="invoice[<?php echo $index ?>][invoiceid]" value="<?php echo $invoice->id ?>">
                                            </td>
                                            <td class="tw-w-48">
                                                <?php echo render_date_input('invoice[' . $index . '][date]', '', date(get_current_date_format(true))) ?>
                                            </td>
                                            <td class="tw-w-56">
                                                <div class="form-group">
                                                    <select class="selectpicker" name="invoice[<?php echo $index ?>][paymentmode]" data-width="100%" data-none-selected-text="-">
                                                        <option></option>
                                                        <?php foreach ($invoice->allowed_payment_modes as $mode) { ?>
                                                            <option value="<?php echo e($mode->id); ?>"><?php echo e($mode->name); ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </td>
                                            <td><?php echo render_input('invoice[' . $index . '][bank]') ?></td>
                                            <td><?php echo render_input('invoice[' . $index . '][transactionid]') ?></td>
                                            <td><?php echo render_input('invoice[' . $index . '][amount]', '', '', 'number', ['max' => $invoice->total_left_to_pay]) ?>
                                            </td>
                                            <td><?php echo app_format_money($invoice->total_left_to_pay, $invoice->currency) ?>

                                                <input type="hidden" id="invoice_<?php echo $index; ?>_balance" value="<?php echo $invoice->total_left_to_pay; ?>">

                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12 row">
                            <div class="checkbox">
                                <input type="checkbox" name="do_not_send_invoice_payment_recorded" value="1" id="do_not_send_invoice_payment_recorded">
                                <label for="do_not_send_invoice_payment_recorded"><?php echo _l('batch_payments_send_invoice_payment_recorded'); ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default close_btn" data-dismiss="modal"><?php echo _l('close'); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo _l('apply'); ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- <script>
    $(document).ready(function() {

        function updateTransactionDetails(transactionIdAll, selectedPaymentModes, bankselectedvalue) {
            var checkedCheckboxes = $('input[name="check_batch_invoice[]"]:checked');
            var totalAmount = 0;

            checkedCheckboxes.each(function(index) {
                var checkboxId = $(this).attr('id');
                var idx = checkboxId.split('_')[1];
                var transactionId = transactionIdAll[index % transactionIdAll.length] || '';
                var bankvalue = bankselectedvalue[index % bankselectedvalue.length] || '';

                // Get the received amount from the balance input for the specific invoice index
                var receivedAmount = parseFloat($('#invoice_' + idx + '_balance').val()) || 0;

                $('input[name="invoice[' + idx + '][transactionid]"]').val(transactionId);
                $('input[name="invoice[' + idx + '][bank]"]').val(bankvalue);

                // Check if amount field is already set; if not, set default value
                var currentAmount = parseFloat($('input[name="invoice[' + idx + '][amount]"]').val());
                if (isNaN(currentAmount)) {
                    $('input[name="invoice[' + idx + '][amount]"]').val(receivedAmount.toFixed(2));
                }

                totalAmount += receivedAmount;
                var $paymentModeSelect = $('select[name="invoice[' + idx + '][paymentmode]"]');
                if (selectedPaymentModes.length > 0) {
                    $paymentModeSelect.val(selectedPaymentModes[0]).selectpicker('refresh');
                }
            });

            $('#receive_amount_total').val(totalAmount.toFixed(2));

            // Reset values for unchecked checkboxes
            $('input[name="check_batch_invoice[]"]:not(:checked)').each(function() {
                var checkboxId = $(this).attr('id');
                var idx = checkboxId.split('_')[1];
                $('input[name="invoice[' + idx + '][transactionid]"]').val('');
                $('input[name="invoice[' + idx + '][bank]"]').val('');
                $('input[name="invoice[' + idx + '][amount]"]').val('');
                var $paymentModeSelect = $('select[name="invoice[' + idx + '][paymentmode]"]');
                $paymentModeSelect.val('').selectpicker('refresh');
            });

            updateRemainingBalanceTotal();
        }

        function updateRemainingBalanceTotal() {
            var totalAmount = 0;

            $('input[name="check_batch_invoice[]"]:checked').each(function() {
                var checkboxId = $(this).attr('id');
                var idx = checkboxId.split('_')[1];
                var balanceAmount = parseFloat($('input[name="invoice[' + idx + '][amount]"]').val()) || 0;

                totalAmount += balanceAmount;
            });

            $('#receive_amount_total').val(totalAmount.toFixed(2));

            // Calculate the remaining balance as the difference between received payment and total received amount
            var receivedPayment = parseFloat($('#received_payment_total').val()) || 0;
            var remainingBalance = receivedPayment - totalAmount;
            $('#remaining_balance_total').val(remainingBalance.toFixed(2));
        }

        function handleInputChange() {
            var transactionIdAll = $('#transaction_id_all').val().split(', ').map(item => item.trim()).filter(Boolean);
            var selectedPaymentModes = $('#payment_mode_all').val().split(', ').map(item => item.trim()).filter(Boolean);
            var bankselectedvalue = $('#bank_all').val().split(', ').map(item => item.trim()).filter(Boolean);

            updateTransactionDetails(transactionIdAll, selectedPaymentModes, bankselectedvalue);
        }

        $('#transaction_id_all, #payment_mode_all, #bank_all').on('change', function() {
            handleInputChange();
        });

        $('input[name="check_batch_invoice[]"]').on('change', function() {
            handleInputChange();
        });

        $(document).on('change', 'input[name^="invoice"][name$="[amount]"]', function() {
            updateRemainingBalanceTotal();
        });

        $('#received_payment_total').on('input', function() {
            updateRemainingBalanceTotal();
        });

        $('.selectpicker').selectpicker();

        handleInputChange();
    });

    $(function() {
    $('#batch-payment-form').on('submit', function(event) {
        var remainingBalance = parseFloat($('#remaining_balance_total').val()) || 0;
        if (remainingBalance < 0) {
            event.preventDefault();
            alert_float('warning', 'The balance cannot be negative. Please check the received payment and Received Amount to be received.')
        }
    });
});
</script> -->

<script>
    $(document).ready(function() {
        function updateTransactionDetails(transactionIdAll, selectedPaymentModes, bankselectedvalue) {
            var checkedCheckboxes = $('input[name="check_batch_invoice[]"]:checked');
            var totalAmount = 0;
            checkedCheckboxes.each(function(index) {
                var idx = $(this).attr('id').split('_')[1];
                var transactionId = transactionIdAll[index % transactionIdAll.length] || '';
                var bankvalue = bankselectedvalue[index % bankselectedvalue.length] || '';
                var receivedAmount = parseFloat($('#invoice_' + idx + '_balance').val()) || 0;
                $('input[name="invoice[' + idx + '][transactionid]"]').val(transactionId);
                $('input[name="invoice[' + idx + '][bank]"]').val(bankvalue);
                var currentAmount = parseFloat($('input[name="invoice[' + idx + '][amount]"]').val());
                if (isNaN(currentAmount)) {
                    $('input[name="invoice[' + idx + '][amount]"]').val(receivedAmount.toFixed(2));
                }
                totalAmount += receivedAmount;
                var $paymentModeSelect = $('select[name="invoice[' + idx + '][paymentmode]"]');
                if (selectedPaymentModes.length > 0) {
                    $paymentModeSelect.val(selectedPaymentModes[0]).selectpicker('refresh');
                }
            });
            $('#receive_amount_total').val(totalAmount.toFixed(2));
            $('input[name="check_batch_invoice[]"]:not(:checked)').each(function() {
                var idx = $(this).attr('id').split('_')[1];
                $('input[name="invoice[' + idx + '][transactionid]"]').val('');
                $('input[name="invoice[' + idx + '][bank]"]').val('');
                $('input[name="invoice[' + idx + '][amount]"]').val('');
                var $paymentModeSelect = $('select[name="invoice[' + idx + '][paymentmode]"]');
                $paymentModeSelect.val('').selectpicker('refresh');
            });
            updateRemainingBalanceTotal();
        }

        function updateRemainingBalanceTotal() {
            var totalAmount = 0;
            $('input[name="check_batch_invoice[]"]:checked').each(function() {
                var idx = $(this).attr('id').split('_')[1];
                var balanceAmount = parseFloat($('input[name="invoice[' + idx + '][amount]"]').val()) || 0;
                totalAmount += balanceAmount;
            });
            $('#receive_amount_total').val(totalAmount.toFixed(2));
            var receivedPayment = parseFloat($('#received_payment_total').val()) || 0;
            var remainingBalance = receivedPayment - totalAmount;
            $('#remaining_balance_total').val(remainingBalance.toFixed(2));
        }

        function handleInputChange() {
            var transactionIdAll = $('#transaction_id_all').val().split(', ').map(item => item.trim()).filter(Boolean);
            var selectedPaymentModes = $('#payment_mode_all').val().split(', ').map(item => item.trim()).filter(Boolean);
            var bankselectedvalue = $('#bank_all').val().split(', ').map(item => item.trim()).filter(Boolean);
            updateTransactionDetails(transactionIdAll, selectedPaymentModes, bankselectedvalue);
        }

        $('#transaction_id_all, #payment_mode_all, #bank_all').on('change', handleInputChange);
        $('input[name="check_batch_invoice[]"]').on('change', handleInputChange);
        $(document).on('change', 'input[name^="invoice"][name$="[amount]"]', updateRemainingBalanceTotal);
        $('#received_payment_total').on('input', updateRemainingBalanceTotal);

        // Event handler for batch-payment-filter change
        $('#batch-payment-filter').on('change', function() {
            // Deselect all checkboxes
            $('input[name="check_batch_invoice[]"]').prop('checked', false);
            handleInputChange();
        });

        $('.selectpicker').selectpicker();
        handleInputChange();

        $('#batch-payment-form').on('submit', function(event) {
            var remainingBalance = parseFloat($('#remaining_balance_total').val()) || 0;
            if (remainingBalance < 0) {
                event.preventDefault();
                alert_float('warning', 'The balance cannot be negative. Please check the received payment and Received Amount to be received.');
            }
        });
    });
</script>