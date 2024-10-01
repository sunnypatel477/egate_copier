<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_customers_portal_head'); ?>

<div class="panel_s">
	<div class="panel-body">
		<div class="row">

			<div class="col-md-12">

				<div class="card">
					<div class="row">
						<div class="col-md-12">
							<div class="card-header no-padding-top">
								<div class="row">
									<?php 
									$reporter = '';
									if($ticket->created_type == 'staff'){
										$reporter = get_staff_full_name($ticket->created_id);
									}else{
										$reporter = get_contact_full_name($ticket->created_id);
									}
									?>
									<div class="col-md-6">
										<?php echo render_customer_status_html($ticket->id, 'ticket_status', $ticket->status); ?>
										<h4><strong><?php  echo new_html_entity_decode($ticket->issue_summary.' '.$ticket->code); ?></strong></h4>
										<h6><?php echo _l('cs_reporter'); ?>: <?php  echo new_html_entity_decode($reporter != null ? $reporter : ''); ?></h6>
									</div>

									<div class="col-md-6 ">
										<div class="pull-right">

											<?php if($ticket->status == 'open'){ ?>
												<a href="<?php echo site_url('customer_service/customer_service_client/add_edit_ticket/'.$ticket->id); ?>" class="btn btn-sm btn-primary mleft5" ><span class="fa-regular fa-pen-to-square"></span> <?php echo _l('edit'); ?></a>

												<a href="<?php echo site_url('customer_service/customer_service_client/delete_ticket/'.$ticket->id); ?>" class="btn btn-sm btn-primary mleft5 _delete" ><span class="fa fa-trash"></span> <?php echo _l('delete'); ?></a>
											<?php } ?>

											<div class="btn-group mleft5 hide">
												<a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo _l('cs_more') . ' '; ?><span class="caret"></span></a>
												<ul class="dropdown-menu dropdown-menu-right">
													<li class="hidden-xs">
														<a href="#" onclick="new_job_p(); return false;" >
															<?php echo _l('cs_change_ticket_owner'); ?>
														</a>
													</li>

													<li class="hidden-xs">
														<a href="<?php echo admin_url('hr_profile/job_position_manage'); ?>">
															<?php echo _l('cs_create_KB_entry'); ?>
														</a>
													</li>
												</ul>
											</div>

										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
					<hr class="no-margin">

				</div>

			</div>
		</div>

	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="panel_s mtop15">
				<div class="panel-body">
					<h4><strong><?php echo _l('cs_ticket_information'); ?></strong></h4>

					<div class="row">
						<div class="col-md-6">
							<table class="table border table-striped no-mtop">
								<tbody>

									<tr class="project-overview">
										<td class="bold" width="30%"><?php echo _l('cs_category_priority'); ?></td>
										<td><?php echo _l('cs_'.$ticket->priority_level) ; ?></td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo _l('department'); ?></td>
										<td><?php echo cs_get_department_name($ticket->department_id) ; ?></td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo _l('cs_date_created'); ?></td>
										<td><?php echo _dt($ticket->datecreated) ; ?></td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo _l('cs_device_name'); ?></td>
										<td>
											<?php 
											if(!is_null($ticket->item_description)){
												echo new_html_entity_decode($ticket->item_description) ;
											}elseif($ticket->item_id != null){
												$this->load->model('invoice_items_model');
												$item = $this->invoice_items_model->get($ticket->item_id);
												if($item){
													echo new_html_entity_decode($item->description) ;
												}
											}
											?>
										</td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo _l('cs_assigned_to'); ?></td>
										<td><?php echo get_staff_full_name($ticket->assigned_id) ; ?></td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo _l('cs_SLA_plan'); ?></td>
										<td><?php echo cs_get_sla_name($ticket->sla_id) ; ?></td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo _l('cs_due_date'); ?></td>
										<td><?php echo _dt($ticket->due_date);  ?></td>
									</tr> 
									<tr class="project-overview">
										<td class="bold"><?php echo _l('cs_first_reply_time'); ?></td>
										<td><?php echo _dt($ticket->first_reply_time);  ?></td>
									</tr> 
									<tr class="project-overview">
										<td class="bold"><?php echo _l('cs_last_update_time'); ?></td>
										<td><?php echo _dt($ticket->last_update_time);   ?></td>
									</tr> 

								</tbody>
							</table>
						</div>
						<div class="col-md-6">
							<table class="table border table-striped no-mtop">
								<tbody>

									<tr class="project-overview">
										<td class="bold" width="30%"><?php echo _l('staff'); ?></td>
										<td><?php echo get_staff_full_name($ticket->staffid) ; ?></td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo _l('client_email'); ?></td>
										<td><?php echo cs_get_staff_email($ticket->staffid) ; ?></td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo _l('client'); ?></td>
										<td><?php echo get_company_name($ticket->client_id) ; ?></td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo _l('clients_phone'); ?></td>
										<td><?php
										$client = get_client($ticket->client_id);
										if($client){
											echo new_html_entity_decode($client->phonenumber) ;
										}
									?></td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo _l('cs_ticket_source'); ?></td>
										<td><?php echo _l($ticket->ticket_source) ; ?></td>
									</tr>
									<tr class="project-overview hide">
										<td class="bold"><?php echo _l('cs_last_message_time'); ?></td>
										<td><?php echo _dt($ticket->last_message_time) ; ?></td>
									</tr>
									<tr class="project-overview">
										<td class="bold"><?php echo _l('cs_last_response_time'); ?></td>
										<td><?php echo _dt($ticket->last_response_time);  ?></td>
									</tr> 
									<tr class="project-overview">
										<td class="bold"><?php echo _l('cs_category_name_label'); ?></td>
										<td><?php echo cs_get_category_name($ticket->category_id);  ?></td>
									</tr> 

								</tbody>
							</table>
						</div>
						<div class="col-md-12">
							<label><strong><?php echo _l('cs_internal_note'); ?></strong></label><br>
							<html><?php echo new_html_entity_decode($ticket->internal_note != null ? $ticket->internal_note : ''); ?></html>
						</div>
								
					</div>
					<?php if( $ticket->resolution != null && new_strlen($ticket->resolution) > 0){ ?>
						<?php echo _l('cs_result') ?>: <h5 class="text-success"><?php echo new_html_entity_decode($ticket->resolution != null ? $ticket->resolution : ''); ?></h5>
					<?php }else{ ?>
						<h5 class="text-danger"><?php echo _l('cs_dont_result'); ?></h5>
						<br>
					<?php } ?>

					<h4><strong><?php echo _l('cs_ticket_detail'); ?></strong></h4>

					<div class="row">
						<div class="horizontal-scrollable-tabs preview-tabs-top">
							
							<div class="horizontal-tabs">
								<ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
									<li role="presentation" class="active">
										<a href="#stages" aria-controls="stages"  class="stages" role="tab" data-toggle="tab">
											<span class="fa-brands fa-usps"></span>&nbsp;<?php echo _l('cs_stages'); ?>
										</a>
									</li>

									<li role="presentation">
										<a href="#ticket_actions" aria-controls="ticket_actions" role="tab" data-toggle="tab">
											<span class="fa-solid fa-bolt"></span>&nbsp;<?php echo _l('cs_post_reply'); ?>
										</a>
									</li>
									<li role="presentation">
										<a href="#ticket_history" aria-controls="ticket_history" role="tab" data-toggle="tab">
											<span class="fa fa-history"></span>&nbsp;<?php echo _l('cs_ticket_history'); ?>
										</a>
									</li>


								</ul>
							</div>
						</div>
						<br>


						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="stages">
								<div class="col-md-12">
									<div class="table-responsive">

										<?php if(isset($ticket_detail_data['stages'])){ ?>
											<table class="table items table-bordered no-mtop">
												<thead>
													<tr>
														<th align="left"><strong><?php echo _l('cs_name') ?></strong></th>
														<th align="left"><strong><?php echo _l('cs_staff') ?></strong></th>
														<th align="left"><strong><?php echo _l('cs_status') ?></strong></th>
														<th align="left"><strong><?php echo _l('cs_action') ?></strong></th>
													</tr>
													<?php 
													$ticket_workflows = $ticket_detail_data['stages'];
													?>
												</thead>
												<tbody>
													<?php foreach ($ticket_workflows as $key => $ticket_workflow) { ?>
														<tr>
															<td ><?php echo new_html_entity_decode($ticket_workflow['name'] != null ? $ticket_workflow['name'] : '') ?><br><span><?php echo new_html_entity_decode($ticket_workflow['stage_description'] != null ? $ticket_workflow['stage_description'] : '') ?></span></td>
															<td >
																<?php echo get_staff_full_name($ticket_workflow['staff_id']) ?>
															</td>

															<td >
																<span class="tag label label-tag text-danger"> <?php echo _l('cs_'.$ticket_workflow['status']) ?></span>
															</td>
															<td >

																<?php if($key == 0){ ?>
																	<?php 
																	echo render_customer_status_html($ticket_workflow['id'], 'stage_status', $ticket_workflow['status']);
																	?>

																<?php }elseif($ticket_workflow['status'] != 'cs_not_started'){ ?>
																	<?php 
																	echo render_customer_status_html($ticket_workflow['id'], 'stage_status', $ticket_workflow['status']);
																	?>

																<?php } ?>
															</td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										<?php }else{ ?>
											<h5><?php echo _l('cs_There_is_no_workflow_for_this_ticket'); ?></h5>
										<?php } ?>

									</div>
								</div>
							</div>

							<div role="tabpanel" class="tab-pane " id="ticket_actions">
								<div class="col-md-12">
									<?php echo form_open_multipart(site_url('customer_service/customer_service_client/ticket_post_internal_reply'),array('class'=>'post_internal_reply','autocomplete'=>'off')); ?>
									<div class="col-md-5 col-md-offset-1">
										<input type="hidden" name="ticket_id" value="<?php echo new_html_entity_decode($ticket->id) ?>">
										
										<?php echo render_input('note_title', 'cs_note_title') ?>
										<?php echo render_textarea('note_details', 'cs_note_details') ?>

									<button type="submit" class="btn btn-info"><?php echo _l('cs_post_note'); ?></button>

								</div>
								<?php echo form_close(); ?>

								<div class="col-md-5 ">
									<div class="activity-feed">
										<?php if(count($ticket_post_internal_histories) > 0){ ?>
											<?php foreach($ticket_post_internal_histories as $post_internal_history){ ?>
												<?php 
												$id = $post_internal_history['id'];
												$date_create = '';
												$staff_id = '';
												$description = '';
												$rel_type = '';

												if(isset($post_internal_history['note_title'])){
													$date_create = $post_internal_history['datecreated'];

													$created_label = '';
													if($post_internal_history['created_type'] == 'staff'){
														$staff_id = $post_internal_history['staffid'];
														$created_label = '<strong>'._l('cs_staff').'</strong> - '.get_staff_full_name($post_internal_history['staffid']);
													}else{
														$created_label = '<strong>'._l('cs_client').'</strong> - '.get_contact_full_name($post_internal_history['staffid']);
													}

													$description = $created_label .' '._l('cs_post_internal_reply').': '.$post_internal_history['note_title'].' <strong>'. $post_internal_history['note_details'].'</strong><br>'. _l('cs_reason');
													$rel_type = 'post_internal';


												}

												?>
												<div class="feed-item">
													<div class="date">
														<span class="text-has-action" data-toggle="tooltip" data-title="<?php echo _dt($date_create); ?>">
															<?php echo time_ago($date_create); ?>
														</span>
														<?php if(is_admin()){ ?>
															<a href="#" class="pull-right text-danger" onclick="delete_ticket_history(this,<?php echo new_html_entity_decode($id); ?>, '<?php echo new_html_entity_decode($rel_type); ?>');return false;"><i class="fa fa fa-times"></i></a>
														<?php } ?>
													</div>

													<div class="text">
														<?php if(isset($staff_id)){ ?>
															<?php if($staff_id != 0){ ?>
																<a href="<?php echo admin_url('profile/'.$staff_id); ?>">
																	<?php echo staff_profile_image($staff_id,array('staff-profile-xs-image pull-left mright5'));
																	?>
																</a>
																<?php
															}
														}
														echo new_html_entity_decode($description);

														?>
													</div>

												</div>
											<?php } ?>
										<?php }else{ ?>
											<h5><?php echo _l('cs_there_is_no_post_history_for_this_ticket'); ?></h5>
										<?php } ?>
									</div>
								</div>

					</div>
				</div>
				<div role="tabpanel" class="tab-pane " id="ticket_history">
					<div class="col-md-12">
						<div class="activity-feed">
							<?php if(count($ticket_histories) > 0){ ?>
								<?php foreach($ticket_histories as $ticket_history){ ?>
									<?php 
									$id = $ticket_history['id'];
									$date_create = '';
									$staff_id = '';
									$description = '';
									$rel_type = '';

									if(isset($ticket_history['assignee_id'])){
										$date_create = $ticket_history['datecreated'];
										$staff_id = $ticket_history['staffid'];
										$description = get_staff_full_name($ticket_history['staffid']) .' '._l('cs_assigned_this_ticket_for').': <strong>'. get_staff_full_name($ticket_history['assignee_id']).'</strong><br>'. _l('cs_reason'). ': '. $ticket_history['comment'];
										$rel_type = 'assign_ticket';


									}elseif(isset($ticket_history['department_id'])){

										$date_create = $ticket_history['datecreated'];
										$staff_id = $ticket_history['staffid'];
										$description = get_staff_full_name($ticket_history['staffid']) .' '._l('cs_transfer_this_ticket_for_department').': <strong>'. cs_get_department_name($ticket_history['department_id']).'</strong><br>'. _l('cs_reason'). ': '. $ticket_history['comment'];
										$rel_type = 'transfer_department';


									}elseif(isset($ticket_history['note_title'])){
										$date_create = $ticket_history['datecreated'];

										$created_label = '';
										if($ticket_history['created_type'] == 'staff'){
											$staff_id = $ticket_history['staffid'];
											$created_label = '<strong>'._l('cs_staff').'</strong> - '.get_staff_full_name($ticket_history['staffid']);
										}else{
											$created_label = '<strong>'._l('cs_client').'</strong> - '.get_contact_full_name($ticket_history['staffid']);
										}

										$description = $created_label .' '._l('cs_post_internal_reply').': '.$ticket_history['note_title'].' <strong>'. $ticket_history['note_details'].'</strong><br>'. _l('cs_reason');
										$rel_type = 'post_internal';


									}elseif(isset($ticket_history['response'])){
										$date_create = $ticket_history['datecreated'];
										$staff_id = $ticket_history['staffid'];
										$description = get_staff_full_name($ticket_history['staffid']) .' '._l('cs_post_reply'). _l('cs_to').': <strong>'.' '.get_staff_full_name($ticket_history['to_staff_id']).'</strong> '.$ticket_history['response'].'<br>';
										$rel_type = 'post_reply';
									}elseif(isset($ticket_history['created_type'])){
										$date_create = $ticket_history['date'];

										if($ticket_history['created_type'] == 'staff'){
											$staff_id = $ticket_history['staffid'];
											$full_name = $ticket_history['full_name'];

										}elseif($ticket_history['created_type'] == 'client'){
											$full_name = $ticket_history['full_name'];
										}else{
											$full_name = $ticket_history['created_type'];
										}

										$description = $full_name .': '.$ticket_history['description'];

										$rel_type = 'ticket_timeline_log';
									}


									?>
									<div class="feed-item">
										<div class="date">
											<span class="text-has-action" data-toggle="tooltip" data-title="<?php echo _dt($date_create); ?>">
												<?php echo time_ago($date_create); ?>
											</span>
											<?php if(is_admin()){ ?>
												<a href="#" class="pull-right text-danger" onclick="delete_ticket_history(this,<?php echo new_html_entity_decode($id); ?>, '<?php echo new_html_entity_decode($rel_type); ?>');return false;"><i class="fa fa fa-times"></i></a>
											<?php } ?>
										</div>

										<div class="text">
											<?php if(isset($staff_id)){ ?>
												<?php if($staff_id != 0){ ?>
													<a href="<?php echo admin_url('profile/'.$staff_id); ?>">
														<?php echo staff_profile_image($staff_id,array('staff-profile-xs-image pull-left mright5'));
														?>
													</a>
													<?php
												}
											}
											echo new_html_entity_decode($description);

											?>
										</div>

									</div>
								<?php } ?>
							<?php }else{ ?>
								<h5><?php echo _l('cs_there_is_no_history_for_this_ticket'); ?></h5>
							<?php } ?>
						</div>
					</div>
				</div>

			</div>

		</div>
	</div>
</div>


</div>
</div>


<div class="row">
	<div class="col-md-12 ">
		<div class="panel-body bottom-transaction">
			<div class="btn-bottom-toolbar text-right">
				<a href="<?php echo site_url('customer_service/customer_service_client/tickets'); ?>"class="btn btn-info text-right"><?php echo _l('cs_close'); ?></a>
			</div>
		</div>
		<div class="btn-bottom-pusher"></div>
	</div>
</div>

</div>
<div id="modal_wrapper"></div>

<?php hooks()->do_action('app_customers_portal_footer'); ?>


