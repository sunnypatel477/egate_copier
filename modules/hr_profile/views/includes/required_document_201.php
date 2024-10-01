<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div>
    <div class="_buttons">
        <h3 class="pull-left"><?php echo _l('required_document_201'); ?></h3>
        <a href="#" onclick="new_required_document(); return false;" class="btn btn-info pull-right display-block">
            <?php echo _l('Add'); ?>
        </a>
    </div>
    <div class="clearfix"></div>
    <hr class="hr-panel-heading" />
    <div class="clearfix"></div>
    <table class="table dt-table">
        <thead>
            <th><?php echo _l('document_type'); ?></th>
            <th><?php echo _l('expiration_date_setting'); ?></th>
            <th><?php echo _l('options'); ?></th>
        </thead>
        <tbody>
            <?php foreach ($required_document_list as $required_document) { ?>
                <tr>
                    <td><?php echo html_entity_decode($required_document['document_type']); ?></td>
                    <td><?php echo html_entity_decode($required_document['expiration_date']); ?></td>
                    <td>
                        <a href="#" onclick="edit_required_document(this,<?php echo html_entity_decode($required_document['id']); ?>); return false" data-name="<?php echo html_entity_decode($required_document['document_type']); ?>" data-date="<?php echo html_entity_decode($required_document['expiration_date']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square"></i></a>
                        <a href="<?php echo admin_url('hr_profile/delete_required_document/' . $required_document['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="modal fade" id="required_document" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <?php echo form_open(admin_url('hr_profile/required_document_filed')); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <span class="edit-title"><?php echo _l('edit_required_document'); ?></span>
                        <span class="add-title"><?php echo _l('new_required_document'); ?></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="additional_required_document"></div>
                            <div class="form">
                                <?php echo render_input('document_type', 'required_document_name'); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="additional_required_document"></div>
                            <div class="form">
                                <?php echo render_input('expiration_date', 'expiration_date_setting', '', 'number'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div><br>
</div>
</body>

</html>