<div class="row">
    <div class="col-md-12">
        <?php
        if (has_permission('hrm_contract', '', 'create') && is_admin()) {
        ?>
            <div class="_buttons">
                <a href="#" onclick="new_issued_assets_bnefits(); return false;" class="btn btn-primary pull-left display-block">
                    <?php echo _l('hr_add_disciplinary_history'); ?>
                </a>

            </div>
        <?php } ?>
    </div>
</div>
<div class="clearfix"></div>
<br>
<div class="row">
    <div class="col-md-12">

        <?php
        $table_data = array(
            '#',

            _l('issued_date'),
            _l('hr_hr_staff_name'),
            _l('issued_assets_bnefits_name'),
            _l('returned'),
            _l('file'),
            _l('action')
        );
        render_datatable($table_data, 'table_issued_assets_bnefits');
        ?>
        <div id="contract_modal_wrapper"></div>

    </div>

    <div class="modal fade" id="issued_assets_bnefits" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <?php echo form_open_multipart(admin_url('hr_profile/issued_assets_bnefits'), ['id' => 'issued_assets_bnefits_form']); ?>

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="issued_assets_bnefits_title">

                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="disciplinary_history_id"></div>
                            <div class="form">

                                <?php echo form_hidden('staff_id', $staffid); ?>
                                <input type="hidden" name="issued_assets_bnefits_id" id="issued_assets_bnefits_id" />
                                <div id="dependent_person_id"></div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        echo render_date_input('issued_date', 'issued_date', _d(date('Y-m-d')));
                                        ?>
                                        <?php
                                        echo render_input('issued_assets_bnefits_name', 'Issued Assets Benefits Name', '', '', ['id' => 'issued_assets_bnefits_name']); ?>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="returned">Returned</label>
                                        <input style="height: 30px; width: 30px;" type="checkbox" id="returned" name="returned" value="1">
                                    </div>

                                    <div class="col-md-12">
                                        <?php echo render_input('file', 'File', '', 'file') ?>
                                    </div>
                                    <div class="col-md-12">
                                        <a href="" id="image_download_anc" target="_blank"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('hr_close'); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    function new_issued_assets_bnefits() {
        "use strict";
        $("#issued_assets_bnefits_name").val('');
        $('#issued_assets_bnefits_id').val('');
        $('#returned').attr('checked', false);
        $("#image_download_anc").html('');
        $("#issued_assets_bnefits").modal("show");
        $('#issued_assets_bnefits_id').val('');
        $('#').val();
        $("#issued_assets_bnefits_title").html('Add New Issued Assets Benefits')
    }
</script>