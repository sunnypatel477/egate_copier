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
								<a href="#" onclick="new_kpi(); return false;" class="btn btn-info pull-left display-block">
									<?php echo _l('cs_add'); ?>
								</a>
							</div>
							
						<?php } ?>
						<br>
						<br>

						<?php 
						render_datatable(
							array(
								_l('id'),
								_l('cs_name_label'),
								_l('first_response_time_label'),
								_l('average_resolution_time_label'),
								_l('average_handle_time_label'),
								_l('number_of_tickets_label'),
								_l('number_of_resolved_tickets_label'),
								_l('number_of_tickets_by_medium_label'),
								_l('escalation_rate_label'),
								_l('customer_satisfaction_score_label'),
								_l('cs_status'),
								_l('cs_date_created'),
								_l('staffid'),
								_l('options'),
							),'kpi_table'
						);
						?>

						<div class="modal fade" id="spam_filter" tabindex="-1" role="dialog">
							<div class="modal-dialog">
								<?php echo form_open_multipart(admin_url('customer_service/kpi'), array('id'=>'add_edit_kpi')); ?>

								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">
											<span class="edit-title"><?php echo _l('kpi_edit_heading'); ?></span>
											<span class="add-title"><?php echo _l('kpi_add_heading'); ?></span>
										</h4>
									</div>
									<div class="modal-body">
										<div class="row">
											<div id="spam_filter_additional"></div>

											<div class="col-md-6">
												<?php echo render_input('code', 'cs_code_label', '', 'text', ['readonly' => true]) ?>
											</div>
											<div class="col-md-6">
												<?php echo render_input('name', 'cs_name_label', '', 'text') ?>
											</div>
											<div class="col-md-6">
												<?php echo render_input('first_response_time', 'first_response_time_label', '', 'number') ?>
											</div>
											
											<div class="col-md-6">
												<div class="form-group">
													<label for="first_response_time_measure"><?php echo _l('first_response_time_measure_label'); ?></label>
													<select name="first_response_time_measure" id="first_response_time_measure" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
														<option value=""></option>
														<option value="seconds"><?php echo _l('cs_seconds'); ?></option>
														<option value="minutes"><?php echo _l('cs_minutes'); ?></option>
														<option value="hours"><?php echo _l('cs_hours'); ?></option>
														<option value="days"><?php echo _l('cs_days'); ?></option>
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<?php echo render_input('average_resolution_time', 'average_resolution_time_label', '', 'number') ?>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="average_resolution_time_measure"><?php echo _l('average_resolution_time_measure_label'); ?></label>
													<select name="average_resolution_time_measure" id="average_resolution_time_measure" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
														<option value=""></option>
														<option value="seconds"><?php echo _l('cs_seconds'); ?></option>
														<option value="minutes"><?php echo _l('cs_minutes'); ?></option>
														<option value="hours"><?php echo _l('cs_hours'); ?></option>
														<option value="days"><?php echo _l('cs_days'); ?></option>
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<?php echo render_input('average_handle_time', 'average_handle_time_label', '', 'number') ?>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="average_handle_time_measure"><?php echo _l('average_handle_time_measure_label'); ?></label>
													<select name="average_handle_time_measure" id="average_handle_time_measure" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
														<option value=""></option>
														<option value="seconds"><?php echo _l('cs_seconds'); ?></option>
														<option value="minutes"><?php echo _l('cs_minutes'); ?></option>
														<option value="hours"><?php echo _l('cs_hours'); ?></option>
														<option value="days"><?php echo _l('cs_days'); ?></option>
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<?php echo render_input('number_of_tickets', 'number_of_tickets_label', '', 'number') ?>
											</div>
											<div class="col-md-6">
												<?php echo render_input('number_of_resolved_tickets', 'number_of_resolved_tickets_label', '', 'number') ?>
											</div>
											<div class="col-md-6">
												<?php echo render_input('number_of_tickets_by_medium', 'number_of_tickets_by_medium_label', '', 'number') ?>
											</div>
											<div class="col-md-6">
												<?php echo render_input('escalation_rate', 'escalation_rate_label', '', 'number') ?>
											</div>
											<div class="col-md-6">
												<?php echo render_input('customer_satisfaction_score', 'customer_satisfaction_score_label', '', 'number') ?>
											</div>
											
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
										<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
									</div>
								</div><!-- /.modal-content -->
								<?php echo form_close(); ?>
							</div><!-- /.modal-dialog -->
						</div><!-- /.modal -->

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
require('modules/customer_service/assets/js/settings/kpis/kpi_js.php');
?>
</body>
</html>
