<?php hooks()->do_action('head_element_client'); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">	
				<?php $id = ''; ?>
				<?php $id = isset($ticket) ? $ticket->id : ''; ?>

				<?php echo form_open_multipart(site_url('customer_service/customer_service_client/add_edit_ticket/'.$id), array('id'=>'add_ticket')); ?>

				<div class="panel_s">
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
						<input type="hidden" name="created_type" value="client">
						<input type="hidden" name="created_id" value="<?php echo new_html_entity_decode(get_contact_user_id()); ?>">
						<input type="hidden" name="ticket_source" value="client_portal">
						<input type="hidden" name="item_description" value="<?php echo new_html_entity_decode($item_description); ?>">

						<div class="row" >
							<div class="col-md-12">
								<div class="row">

									<div class="col-md-6">
										<?php $code = isset($ticket)? $ticket->code: $ticket_code; ?>
										<?php echo render_input('code', 'cs_code_label',$code,'',array('readonly' => 'true')) ?>
									</div>

									<div class="col-md-6 hide">
										<?php echo render_datetime_input('datecreated','cs_date_created', _dt($datecreated)) ?>

									</div>


									<div class="col-md-3 hide">
										<div class="form-group">
											<label for="client_id"><?php echo _l('client'); ?></label>
											<select name="client_id" id="client_id" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>"  >
												<option value=""></option>
												<?php foreach($clients as $s) { ?>
													<option value="<?php echo new_html_entity_decode($s['userid']); ?>" <?php if(get_client_user_id() == $s['userid']){ echo 'selected'; } ?>><?php echo new_html_entity_decode($s['company']); ?></option>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="invoice_id"><?php echo _l('invoice'); ?></label>
											<select name="invoice_id" id="invoice_id" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>"  >
												<option value=""></option>
												<?php if(isset($invoices)){ ?>
													<?php foreach($invoices as $invoice) { ?>
														<option value="<?php echo new_html_entity_decode($invoice['id']); ?>" <?php if($invoice_id == $invoice['id']){ echo 'selected'; } ?>><?php echo format_invoice_number($invoice['id']); ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="col-md-3">
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
								$cs_ticket_types = cs_ticket_type();
								$cs_priorities = cs_priority();
								?>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="ticket_type"><?php echo _l('cs_ticket_type'); ?></label>
											<select name="ticket_type" id="ticket_type" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>"  >
											
													<?php foreach($cs_ticket_types as $cs_ticket_type) { ?>
														<option value="<?php echo new_html_entity_decode($cs_ticket_type['id']); ?>" <?php if($ticket_type == $cs_ticket_type['id']){ echo 'selected'; } ?>><?php echo html_entity_decode($cs_ticket_type['name']); ?></option>
													<?php } ?>
											</select>
										</div>
									</div>

									<div class="col-md-3 hide">
										<div class="form-group">
											<label for="priority_level"><?php echo _l('cs_category_priority'); ?></label>
											<select name="priority_level" id="priority_level" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>"  >
											
													<?php foreach($cs_priorities as $cs_priority) { ?>
														<option value="<?php echo new_html_entity_decode($cs_priority['id']); ?>" <?php if($priority_level == $cs_priority['id']){ echo 'selected'; } ?>><?php echo html_entity_decode($cs_priority['name']); ?></option>
													<?php } ?>
											</select>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="category_id"><?php echo _l('cs_category_name_label'); ?></label>
											<select name="category_id" id="category_id" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>"  >
											
													<?php foreach($categories as $category) { ?>
														<option value="<?php echo new_html_entity_decode($category['id']); ?>" <?php if($category_id == $category['id']){ echo 'selected'; } ?>><?php echo html_entity_decode($category['category_name']); ?></option>
													<?php } ?>
											</select>
										</div>

									</div>
									
								</div>
								<?php echo render_textarea('issue_summary','(*)'._l('cs_issue_summary'),$issue_summary,array(),array(),'mtop15'); ?>
								<div class="row hide">
									<div class="col-md-3">
										<div class="form-group">
											<label for="department_id"><?php echo _l('department'); ?></label>
											<select name="department_id" id="department_id" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>"  >
											
													<?php foreach($departments as $department) { ?>
														<option value="<?php echo new_html_entity_decode($department['departmentid']); ?>" <?php if($department_id == $department['departmentid']){ echo 'selected'; } ?>><?php echo html_entity_decode($department['name']); ?></option>
													<?php } ?>
											</select>
										</div>

									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="assigned_id"><?php echo _l('cs_assigned_to'); ?></label>
											<select name="assigned_id" id="assigned_id" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>"  >
											
													<?php foreach($staffs as $staff) { ?>
														<option value="<?php echo new_html_entity_decode($staffs['staffid']); ?>" <?php if($assigned_id == $staffs['staffid']){ echo 'selected'; } ?>><?php echo html_entity_decode($staffs['firstname'].' '.$staffs['lastname']); ?></option>
													<?php } ?>
											</select>
										</div>

									</div>
								</div>
								<?php echo render_textarea('internal_note','cs_internal_note',$internal_note,array(),array(),'mtop15'); ?>

								<div class="btn-bottom-toolbar text-right">
									<a href="<?php echo site_url('customer_service/customer_service_client/tickets'); ?>"class="btn btn-default text-right mright5"><?php echo _l('close'); ?></a>
									
									<button type="submit" class="btn btn-info"><?php echo _l('save'); ?></button>

								</div>
							</div>
							<div class="btn-bottom-pusher"></div>
						</div>
					</div>


				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>

<?php hooks()->do_action('app_customers_portal_footer'); ?>

<?php require 'modules/customer_service/assets/js/client_portals/tickets/add_edit_ticket_js.php';?>



