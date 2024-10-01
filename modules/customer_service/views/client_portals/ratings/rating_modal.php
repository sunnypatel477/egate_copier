<div class="modal fade" id="appointmentModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
				
				<h4 class="modal-title"><?php echo new_html_entity_decode($title); ?></h4>
			</div>
			<?php echo form_open_multipart(site_url('customer_service/customer_service_client/ticket_rating'), array('id' => 'add_edit_category')); ?>
			<input type="hidden" name="ticket_id" value="<?php echo new_html_entity_decode($ticket_id); ?>">

			<div class="modal-body">
				<div class="tab-content">
					<div class="row">

						<div class="row">
							<div class="col-md-12">
								
							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default close_btn" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
			</div>

		</div>

		<?php echo form_close(); ?>
	</div>
</div>
</div>

