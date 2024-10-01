<div class="modal fade" id="note_form_lead" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<?php echo form_open('admin/zillapage/leads/note_to_lead',array('id'=>'form_data_note')); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">
					<?php echo _l('Note to lead'); ?>
				</h4>
			</div>
			<div class="modal-body">
				<?php echo form_hidden('formdata_id',$lead->id); ?>
				<div class="row">
					<div class="col-md-12">
						<?php echo render_textarea('note','note', $lead->note); ?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" data-form="#form_data_note" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>"
					class="btn btn-info"><?php echo _l('submit'); ?></button>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>