<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="instax_printing_admin_payment" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('Payment Details'); ?></h4>
            </div>
            <div class="modal-body">
                <form id="paymentModalForm" enctype="multipart/form-data">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="inquiry_id" id="inquiry_id">
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" class="form-control" id="paymentdate" name="paymentdate" required>
                    </div>
                    <div class="form-group">
                        <label for="attachment">Attachment:</label>
                        <input type="file" class="form-control" id="attachment" name="attachment">
                        <div class="attachment_url">
                        
                        </div>
                                                 
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

            </div>
            
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->