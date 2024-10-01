<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_open_multipart(admin_url('checkwriter/checkwriterpdfmarginsetting')); ?>
<div class="col-md-12">
    <div class="col-md-4">
        <label style="font-size: 18px;" for="right_side_pdf_margin"><?php echo _l('right_side_pdf_margin'); ?></label>
    </div>
    <div class="col-md-4">
        <input type="number" class="form-control" id="right_side_pdf_margin" name="right_side_pdf_margin" value="<?php echo get_option('right_side_pdf_margin'); ?>">
    </div>
</div>

<div class="col-md-12 mb-2">
    <div class="col-md-4">
        <label style="font-size: 18px;" for="left_side_pdf_margin"><?php echo _l('left_side_pdf_margin'); ?></label>
    </div>
    <div class="col-md-4">
        <input type="number" class="form-control" id="left_side_pdf_margin" name="left_side_pdf_margin" value="<?php echo get_option('left_side_pdf_margin'); ?>">
    </div>
</div>
<div class="col-md-12">
    <button type="submit" class="btn btn-primary pull-right">
        <?php echo _l('submit'); ?>
    </button>
</div>
<?php echo form_close(); ?>