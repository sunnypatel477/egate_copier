<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">

						<?php if(has_permission('customer_service', '', 'create')){ ?>
							<div class="_buttons">
								<a href="<?php echo admin_url('customer_service/add_edit_ticket') ?>" class="btn btn-info mbot10"><?php echo _l('cs_add'); ?></a>

								<a href="<?php echo admin_url('customer_service/run_ticket_manually') ?>" class="btn btn-success mbot10 "><?php echo _l('cs_run_ticket_manually'); ?></a>
							</div>
							<br>
						<?php } ?>

						<div class="row">
							<div  class="col-md-3 leads-filter-column">
								<div class="form-group">
									<select name="client_filter[]" id="client_filter" data-live-search="true" class="selectpicker" multiple="true" data-actions-box="true" data-width="100%" data-none-selected-text="<?php echo _l('client'); ?>">
										<?php foreach($clients as $client) { ?>
											<option value="<?php echo new_html_entity_decode($client['userid']); ?>"><?php echo new_html_entity_decode($client['company']); ?></option>
										<?php } ?>
									</select>
								</div>
							</div> 
							<div  class="col-md-3 leads-filter-column">
								<div class="form-group">
									<select name="category_filter[]" id="category_filter" data-live-search="true" class="selectpicker" multiple="true" data-actions-box="true" data-width="100%" data-none-selected-text="<?php echo _l('cs_category'); ?>">
										<?php foreach($categories as $category) { ?>
											<option value="<?php echo new_html_entity_decode($category['id']); ?>"><?php echo new_html_entity_decode($category['code'].' '. $category['category_name']); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							 <div  class="col-md-3 leads-filter-column">
								<div class="form-group">
									<select name="priority_filter[]" id="priority_filter" data-live-search="true" class="selectpicker" multiple="true" data-actions-box="true" data-width="100%" data-none-selected-text="<?php echo _l('cs_category_priority'); ?>">
										<?php foreach(cs_priority() as $priority) { ?>
											<option value="<?php echo new_html_entity_decode($priority['id']); ?>"><?php echo new_html_entity_decode($priority['name']); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							 <div  class="col-md-3 leads-filter-column">
								<div class="form-group">
									<select name="ticket_status_filter[]" id="ticket_status_filter" data-live-search="true" class="selectpicker" multiple="true" data-actions-box="true" data-width="100%" data-none-selected-text="<?php echo _l('cs_status'); ?>">
										<?php foreach(cs_ticket_status() as $ticket_status) { ?>
											<option value="<?php echo new_html_entity_decode($ticket_status['id']); ?>"><?php echo new_html_entity_decode($ticket_status['name']); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							 

						</div>

						<?php render_datatable(array(
							_l('id'),
							_l('cs_code_label'),
							_l('cs_created_id'),
							_l('cs_created_type'),
							_l('client'),
							_l('cs_ticket_source'),
							_l('cs_category'),
							_l('department'),
							_l('cs_assigned_to'),
							_l('cs_sla'),
							_l('cs_time_spent'),
							_l('cs_due_date'),
							_l('cs_issue_summary'),
							_l('cs_category_priority'),
							_l('cs_ticket_type'),
							_l('cs_last_message_time'),
							_l('cs_last_response_time'),
							_l('cs_first_reply_time'),
							_l('cs_last_update_time'),
							_l('cs_status'),
							_l('cs_date_created'),
						),'ticket_table'); ?>

					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<?php echo form_close(); ?>
		<div class="btn-bottom-pusher"></div>
	</div>
</div>
<div id="modal_wrapper"></div>

<?php init_tail(); ?>

<?php 
require 'modules/customer_service/assets/js/tickets/ticket_manage_js.php';
require 'modules/customer_service/assets/js/customer_service_js.php';
?>
</body>
</html>
