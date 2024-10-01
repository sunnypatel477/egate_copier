<div class="row">
<div class="col-md-12">
    <p class="bold other_infor-style pull-left"><?php echo _l('required_document_201'); ?></p>
</div>
<hr class="other_infor-hr" />
<?php if (count($required_document_201) > 0) { ?>
    <table class="table dt-table margin-top-0">
        <thead>
            <th><?php echo _l('document_type'); ?></th>
            <th><?php echo _l('licence_no'); ?></th>
            <th><?php echo _l('acquisition_date'); ?></th>
            <th><?php echo _l('expiry_date'); ?></th>
            <th><?php echo _l('issue_authority'); ?></th>
            <th><?php echo _l('attach_file'); ?></th>
            <th><?php echo _l('remark'); ?></th>
            <th><?php echo _l('action'); ?></th>
        </thead>
        <tbody>
            <?php foreach ($required_document_201 as $wetdocument) {
            ?>
                <tr class="project-overview">
                    <?php
                    $expiration = $wetdocument['expiration_date'];
                    $daysToAdd = (int)$expiration;
                    $today = new DateTime();
                    $futureDate = $today->modify("+{$daysToAdd} days");
                    $expiryDate = DateTime::createFromFormat('Y-m-d', $wetdocument['expiry_date']);
                    if ($expiryDate != '') {
                        $expiry_document_status = ($expiryDate < $futureDate) ? "color: red;" : '';
                    } else {
                        $expiry_document_status = '';
                    }
                    ?>
                    <td style="<?php echo $expiry_document_status; ?>"><?php echo html_entity_decode($wetdocument['document_type']); ?></td>
                    <td style="<?php echo $expiry_document_status; ?>"><?php echo $wetdocument['licence_no']; ?></td>
                    <td style="<?php echo $expiry_document_status; ?>"><?php echo _d($wetdocument['acquisition_date']); ?></td>
                    <td style="<?php echo $expiry_document_status; ?>"><?php echo _d($wetdocument['expiry_date']); ?></td>
                    <td style="<?php echo $expiry_document_status; ?>"><?php echo html_entity_decode($wetdocument['issue_authority']); ?></td>
                    <?php
                    $required_document_201_fileDir = module_dir_url('hr_profile', 'uploads/required_document_201/');
                    $document_filename = $required_document_201_fileDir . $wetdocument['attach_file'];
                    ?>
                    <td>
                        <a href="<?php echo html_entity_decode($document_filename); ?>" target="_blank"><?php echo html_entity_decode($wetdocument['attach_file']); ?></a>
                    </td>
                    <td style="<?php echo $expiry_document_status; ?>"><?php echo html_entity_decode($wetdocument['remark']); ?></td>
                    <td class="text-center">
                        <a style="color: gray; font-weight: 700;"><span>(<span><?php echo $wetdocument['expiration_date']; ?><span>)<span></a>&nbsp;&nbsp;
                        <a href="Javascript:void(0);" class="edit_document_info_detail" data-staff_id="<?php echo $wetdocument['staff_id']; ?>" data-setting_id="<?php echo $wetdocument['id']; ?>"><i class="fa fa-edit"></i></a>&nbsp;
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <p><?php echo _l('no_result'); ?></p>
<?php } ?> <br>
</div>

<div class="modal fade" id="addDocumentinfoModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<?php echo form_open_multipart(admin_url('hr_profile/add_update_document_detail_staff'), array('id' => 'add_update_document_detail_staff-form')); ?>
		<div class="modal-content width-100">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">
					<span id="document_label"><?php echo _l('edit_required_document'); ?></span>
				</h4>
			</div>
            
			<div class="modal-body">
				<div class="row">
					<?php echo form_hidden('add_document_staff_id',$staff_id); ?>
					<?php echo form_hidden('document_id', ''); ?>
					<?php echo form_hidden('document_type', ''); ?>

					<div class="col-md-6">
						<?php echo render_input('licence_no', 'licence_no', ''); ?>
					</div>

					<div class="col-md-6">
						<?php echo render_date_input('acquisition_date', 'acquisition_date', ''); ?>
					</div>

					<div class="col-md-6">
						<?php echo render_date_input('expiry_date', 'expiry_date', ''); ?>
					</div>

					<div class="col-md-6">
						<?php echo render_input('issue_authority', 'issue_authority', ''); ?>
					</div>

					<div class="col-md-6">
						<?php echo render_input('attach_file', 'attach_file', '', 'file'); ?>
					</div>

					<div class="col-md-6">
						<?php echo render_input('remark', 'remark', ''); ?>
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
    $(document).ready(function() {
        $(".edit_document_info_detail").on("click", function() {
            var staff_id = $(this).data("staff_id") || 0;
            var setting_id = $(this).data("setting_id");

            $.ajax({
                type: "post",
                url: admin_url + "hr_profile/edit_document_detail_staff/" + staff_id + '/' + setting_id,
                success: function(response) {
                    console.log(response); // For debugging, check what is returned
                    var data = JSON.parse(response);
                    $('#addDocumentinfoModal').modal('show');
                    $("input[name='document_id']").val(data.tr_id);
                    $("input[name='document_type']").val(data.id);
                    $("input[name='licence_no']").val(data.licence_no);
                    $("input[name='expiry_date']").val(data.expiry_date);
                    $("input[name='acquisition_date']").val(data.acquisition_date);
                    $("#expiration_date").val(data.expiration_date);
                    $("input[name='issue_authority']").val(data.issue_authority);
                    $("input[name='remark']").val(data.remark);
                },
            });
        });
    });
</script>