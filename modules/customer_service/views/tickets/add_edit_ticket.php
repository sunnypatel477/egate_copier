<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12" id="small-table">
				<div class="panel_s">
					<?php $id = ''; ?>
					<?php $id = isset($ticket) ? $ticket->id : ''; ?>
					<?php echo form_open_multipart(admin_url('customer_service/add_edit_ticket/'.$id), array('id'=>'add_ticket')); ?>
					<div class="panel-body">

						<div class="row">
							<div class="col-md-12">
								<h4 class="no-margin font-bold "><i class="fa fa-object-ungroup menu-icon" aria-hidden="true"></i> <?php echo new_html_entity_decode($title); ?></h4>
								<hr>
							</div>
						</div>

						<?php 
						
						$current_day = date("Y-m-d");
						$created_id = get_staff_user_id();
						$datecreated = date("Y-m-d H:i:s");
						$claim_information_detail_id = '';
						$item_description = '';

						if(isset($ticket)){
							$id = $ticket->id;
							$created_id = $ticket->created_id;
							$datecreated =  $ticket->datecreated ;
							$item_description =  $ticket->item_description ;
						}

						$invoice_id = isset($ticket) ? $ticket->invoice_id : '';
						$item_id = isset($ticket) ? $ticket->item_id : '';

						?>
						<input type="hidden" name="id" value="<?php echo new_html_entity_decode($id); ?>">
						<input type="hidden" name="created_type" value="staff">
						<input type="hidden" name="created_id" value="<?php echo new_html_entity_decode($created_id != null ? $created_id : 0); ?>">
						<input type="hidden" name="ticket_source" value="web">
						<input type="hidden" name="item_description" value="<?php echo new_html_entity_decode($item_description); ?>">

						<div class="row" >
							<div class="col-md-12">
								<div class="row">

									<div class="col-md-6">
										<?php $code = isset($ticket)? $ticket->code: $ticket_code; ?>
										<?php echo render_input('code', 'cs_code_label',$code,'',array('readonly' => 'true')) ?>
									</div>

									<div class="col-md-6">
										<?php echo render_datetime_input('datecreated','cs_date_created', _dt($datecreated)) ?>

									</div>

									<br>
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="client_id"><?php echo _l('client'); ?></label>
													<select name="client_id" id="client_id" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>"  >
														<option value=""></option>
														<?php foreach($clients as $s) { ?>
															<option value="<?php echo new_html_entity_decode($s['userid']); ?>" <?php if(isset($ticket) && $ticket->client_id == $s['userid']){ echo 'selected'; } ?>><?php echo new_html_entity_decode($s['company']); ?></option>
														<?php } ?>
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label for="invoice_id"><?php echo _l('invoice'); ?></label>
													<select name="invoice_id" id="invoice_id" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>"  >

														<?php if(isset($invoices)){ ?>
															<?php foreach($invoices as $invoice) { ?>
																<option value="<?php echo new_html_entity_decode($invoice['id']); ?>" <?php if($invoice_id == $invoice['id']){ echo 'selected'; } ?>><?php echo format_invoice_number($invoice['id']); ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</div>
											</div>

										</div>

									</div>

									<div class="col-md-6">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="item_id"><?php echo _l('cs_product_service_name'); ?></label>
													<select name="item_id" id="item_id" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>"  >
														<?php if(isset($item_tickets)){ ?>
															<?php foreach($item_tickets as $item_warranty) { ?>
																<option value="<?php echo new_html_entity_decode($item_warranty['item_id']); ?>" <?php if($item_id == $item_warranty['item_id']){ echo 'selected'; } ?>><?php echo cs_get_item_variatiom($item_warranty['item_id']); ?></option>
															<?php } ?>
														<?php } ?>

													</select>
												</div>
											</div>

										</div>
										
									</div>


								</div>


							</div>

						</div>

					</div>



					<div class="row">
						<div class="col-md-12 mtop15">
							<div class="panel-body bottom-transaction">

								<?php $issue_summary = (isset($ticket) ? $ticket->issue_summary : ''); ?>
								<?php 
								$category_id = isset($ticket) ? $ticket->category_id : '';
								$department_id = isset($ticket) ? $ticket->department_id : '';
								$assigned_id = isset($ticket) ? $ticket->assigned_id : '';
								$sla_id = isset($ticket) ? $ticket->sla_id : '';
								$internal_note = isset($ticket) ? $ticket->internal_note : '';
								$ticket_type = isset($ticket) ? $ticket->ticket_type : '';
								$priority_level = isset($ticket) ? $ticket->priority_level : '';
								?>

								<div class="row">
									<div class="col-md-3">
										<?php echo render_select('ticket_type', cs_ticket_type(), ['id', 'name'], 'cs_ticket_type', $ticket_type); ?>									
									</div>

									<div class="col-md-3 hide">
										<?php echo render_select('priority_level', cs_priority(), ['id', 'name'], 'cs_category_priority', $priority_level); ?>									
									</div>
									
								</div>
								<?php echo render_textarea('issue_summary','cs_issue_summary',$issue_summary,array(),array(),'mtop15'); ?>
								<div class="row">
									
									<div class="col-md-6">
										<?php echo render_select('category_id', $categories, ['id', array('code', 'category_name')], 'cs_category_name_label', $category_id); ?>
									</div>
									<div class="col-md-3">
										<?php echo render_select('department_id', $departments, ['departmentid', 'name'], 'department', $department_id); ?>
									</div>
									<div class="col-md-3">
										<?php echo render_select('assigned_id', $staffs, ['staffid', array('firstname', 'lastname')], 'cs_assigned_to', $assigned_id); ?>
									</div>
									
								</div>
								<?php echo render_textarea('internal_note','cs_internal_note',$internal_note,array(),array(),'mtop15'); ?>

								<div class="btn-bottom-toolbar text-right">
									<a href="<?php echo admin_url('customer_service/tickets'); ?>"class="btn btn-default text-right mright5"><?php echo _l('close'); ?></a>

									
									<?php if (is_admin() || has_permission('warranty_management', '', 'edit') || has_permission('warranty_management', '', 'create')) { ?>
										<button type="submit" class="btn btn-info"><?php echo _l('save'); ?></button>

									<?php } ?>

								</div>
							</div>
							<div class="btn-bottom-pusher"></div>
						</div>
					</div>

				</div>

			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<div id="modal_wrapper"></div>
<div id="change_serial_modal_wrapper"></div>

<?php init_tail(); ?>
<?php require 'modules/customer_service/assets/js/tickets/add_edit_ticket_js.php';?>
</body>
</html>



