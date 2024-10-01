<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
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
												<h6><?php echo _l('cs_reporter'); ?>: <?php  echo new_html_entity_decode($reporter); ?></h6>
											</div>
											
											<div class="col-md-6 ">
												<div class="pull-right">

													<?php if($ticket->status == 'open'){ ?>
														<a href="<?php echo admin_url('customer_service/add_edit_ticket/'.$ticket->id); ?>" class="btn btn-sm btn-primary mleft5" ><span class="fa-regular fa-pen-to-square"></span> <?php echo _l('edit'); ?></a>

														<a href="<?php echo admin_url('customer_service/delete_ticket/'.$ticket->id); ?>" class="btn btn-sm btn-primary mleft5 _delete" ><span class="fa fa-trash"></span> <?php echo _l('delete'); ?></a>
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
												<td>
													<?php 
													if(strtotime($ticket->due_date) < strtotime(date('Y-m-d H:i:s'))){
														echo '<span class="text-danger">'._dt($ticket->due_date).'</span>';
													}else{
														echo _dt($ticket->due_date);
													}
													?>
												</td>
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
												<td><a href="<?php echo admin_url('clients/client/'.$ticket->client_id); ?>" target="_blank"><?php echo get_company_name($ticket->client_id) ; ?></a></td>
											</tr>
											<tr class="project-overview">
												<td class="bold"><?php echo _l('invoice'); ?></td>
												<td>
													<?php if(is_numeric($ticket->invoice_id) && $ticket->invoice_id != 0){ ?>
														<a href="<?php echo site_url('invoice/' . $ticket->invoice_id.'/'.cs_get_invoice_hash($ticket->invoice_id) ) ?>" ><?php echo format_invoice_number($ticket->invoice_id); ?></a>
													<?php } ?>

												</td>
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
											<tr class="project-overview">
												<td class="bold"><?php echo _l('cs_resolution'); ?></td>
												<td><?php echo new_html_entity_decode($ticket->resolution != null ? $ticket->resolution : '');   ?></td>
											</tr> 

										</tbody>
									</table>
								</div>
								<div class="col-md-12">
									<label><strong><?php echo _l('cs_internal_note'); ?></strong></label><br>
									<html><?php echo new_html_entity_decode($ticket->internal_note); ?></html>
								</div>
							</div>

							<h4><strong><?php echo _l('cs_ticket_detail'); ?></strong></h4>

							<div class="row">
								<div class="horizontal-scrollable-tabs preview-tabs-top">
									<div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
									<div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
									<div class="horizontal-tabs">
										<ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
											<li role="presentation" class="">
												<a href="#stages" aria-controls="stages"  class="stages" role="tab" data-toggle="tab">
													<span class="fa-brands fa-usps"></span>&nbsp;<?php echo _l('cs_stages'); ?>
												</a>
											</li>
											<li role="presentation" class="active">
												<a href="#ticket_workflow" aria-controls="ticket_workflow"  class="ticket_workflow" role="tab" data-toggle="tab">
													<span class="fa-solid fa-arrow-down-wide-short"></span>&nbsp;<?php echo _l('cs_ticket_workflow'); ?>
												</a>
											</li>

											<li role="presentation">
												<a href="#ticket_actions" aria-controls="ticket_actions" role="tab" data-toggle="tab">
													<span class="fa-solid fa-bolt"></span>&nbsp;<?php echo _l('cs_ticket_actions'); ?>
												</a>
											</li>
											<li role="presentation">
												<a href="#ticket_history" aria-controls="ticket_history" role="tab" data-toggle="tab">
													<span class="fa fa-history"></span>&nbsp;<?php echo _l('cs_ticket_history'); ?>
												</a>
											</li>
											<?php if(1==2){ ?>
												<li role="presentation ">
													<a href="#time_spent" aria-controls="time_spent" role="tab" data-toggle="tab">
														<span class="fa fa-hourglass-1"></span>&nbsp;<?php echo _l('cs_time_spent'); ?>
													</a>
												</li>
											<?php } ?>
											<li role="presentation">
												<a href="#customer_related_information" aria-controls="customer_related_information" role="tab" data-toggle="tab">
													<span class="fa fa-info-circle"></span>&nbsp;<?php echo _l('cs_customer_related_information'); ?>
												</a>
											</li>
											

										</ul>
									</div>
								</div>
								<br>


								<div class="tab-content">
									<div role="tabpanel" class="tab-pane" id="stages">
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
															<?php foreach ($ticket_workflows as $key => $ticket_workflow) { 
																?>
																<tr>
																	<td ><?php echo new_html_entity_decode($ticket_workflow['name']) ?><br><span><?php echo new_html_entity_decode($ticket_workflow['stage_description']) ?></span></td>
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

									<div role="tabpanel" class="tab-pane active" id="ticket_workflow">
										<div class="wrapper">
											<div class="col-md-12">
												<div id="drawflow" ondrop="drop(event)" ondragover="allowDrop(event)">
												</div>
											</div>
										</div>
									</div>

									<div role="tabpanel" class="tab-pane " id="ticket_actions">
										<div class="col-md-12">
											<ul class="nav nav-tabs" id="myTab" role="tablist">
												<li class="nav-item">
													<a class="nav-link active in" id="post_reply-tab" data-toggle="tab" href="#post_reply" role="tab" aria-controls="post_reply" aria-selected="true"><?php echo _l('cs_post_reply'); ?></a>
												</li>
												<li class="nav-item">
													<a class="nav-link" id="post_internal_reply-tab" data-toggle="tab" href="#post_internal_reply" role="tab" aria-controls="post_internal_reply" aria-selected="false"><?php echo _l('cs_post_internal_reply'); ?></a>
												</li>
												<li class="nav-item">
													<a class="nav-link" id="dept_transfer-tab" data-toggle="tab" href="#dept_transfer" role="tab" aria-controls="dept_transfer" aria-selected="false"><?php echo _l('cs_dept_transfer'); ?></a>
												</li>
												<li class="nav-item">
													<a class="nav-link" id="assign_ticket-tab" data-toggle="tab" href="#assign_ticket" role="tab" aria-controls="contact" aria-selected="false"><?php echo _l('cs_assign_ticket'); ?></a>
												</li>
												
											</ul>
											<div class="tab-content" id="myTabContent">
												<div class="tab-pane fade active in" id="post_reply" role="tabpanel" aria-labelledby="post_reply-tab">
													<?php echo form_open_multipart(admin_url('customer_service/ticket_post_reply'),array('class'=>'post_reply','autocomplete'=>'off')); ?>
													<div class="col-md-8 col-md-offset-2">
														
														<?php echo render_select('to_staff_id', $staffs, array('staffid', array('firstname', 'lastname') ), 'cs_to', '') ?>

														<?php echo render_textarea('response', 'cs_response') ?>

														<div class="row">
															<div class="col-md-12">
																<label><?php echo _l('cs_attachments') ?></label>
																<div id="dropzoneDragArea" class="dz-default dz-message">
																	<span><?php echo _l('drag_drop_file_here'); ?></span>
																</div>
																<div class="dropzone-previews"></div>
															</div>
															<div id="images_old_preview"></div>

														</div>

														<div class="form-group">
															<label><?php echo _l('cs_ticket_status'); ?></label>
															<div class="checkbox checkbox-primary">
																<input type="checkbox" id="ticket_status" name="ticket_status" value="ticket_status">
																<label for="ticket_status"><?php echo _l('cs_close_on_reply'); ?>
																<a href="#" class="pull-right display-block input_method"><i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('cs_close_on_reply_tooltip'); ?>"></i></a>
															</label>
														</div>
													</div>
													<div class="form-group">
															<label for="resolution"><?php echo _l('cs_resolution'); ?></label>

															<div class="checkbox checkbox-primary">
																<input type="checkbox" id="resolution" name="resolution" value="resolution">
																<label for="resolution"><?php echo _l('cs_set_reply_as_resolution'); ?>
																<a href="#" class="pull-right display-block input_method"><i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('cs_resolution_tooltip'); ?>"></i></a>
															</label>
														</div>
													</div>

													<button type="submit" class="btn btn-info"><?php echo _l('cs_post_reply'); ?></button>

													</div>
													<?php echo form_close(); ?>
												</div>

												<div class="tab-pane fade" id="post_internal_reply" role="tabpanel" aria-labelledby="post_internal_reply-tab">
													<?php echo form_open_multipart(admin_url('customer_service/ticket_post_internal_reply'),array('class'=>'post_internal_reply','autocomplete'=>'off')); ?>
													<div class="col-md-5 col-md-offset-1">
														<input type="hidden" name="ticket_id" value="<?php echo new_html_entity_decode($ticket->id) ?>">
														
														<?php echo render_input('note_title', 'cs_note_title') ?>
														<?php echo render_textarea('note_details', 'cs_note_details') ?>

														<?php echo render_select('cs_ticket_status', cs_ticket_status(), array('id', 'name'), 'cs_ticket_status', '', ['title' => 'cs_post_internal_ticket_status_title'], [], '', '', true) ?>
														<div class="form-group">
															<label><?php echo _l('cs_resolution'); ?></label>

															<div class="checkbox checkbox-primary">
																<input type="checkbox" id="internal_resolution" name="internal_resolution"value="resolution">
																<label for="internal_resolution"><?php echo _l('cs_set_reply_as_resolution'); ?>
																<a href="#" class="pull-right display-block input_method"><i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('cs_resolution_tooltip'); ?>"></i></a>
															</label>
														</div>
													</div>

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
															<h5><?php echo _l('cs_there_is_no_history_for_this_ticket'); ?></h5>
														<?php } ?>
													</div>
												</div>

											</div>
												
												<div class="tab-pane fade" id="dept_transfer" role="tabpanel" aria-labelledby="dept_transfer-tab">
													<?php echo form_open_multipart(admin_url('customer_service/ticket_department_transfer'),array('class'=>'department_transfer','autocomplete'=>'off')); ?>

													<input type="hidden" name="ticket_id" value="<?php echo new_html_entity_decode($ticket->id) ?>">
													<div class="col-md-8 col-md-offset-2">
														<?php echo render_textarea('comment', 'cs_comments', '', ['title' => 'cs_enter_reasons_for_the_transfer', 'placeholder' => _l('cs_enter_reasons_for_the_transfer')]) ?>

														<?php echo render_select('department_id', $departments, array('departmentid', 'name'), 'department_name', '', ['title' => 'cs_department_transfer'], [], '', '', true) ?>
														<span><?php echo _l('cs_ticket_is_currently_in').' <strong>'.cs_get_department_name($ticket->department_id).'</strong> '._l('cs_department_label') ?></span><br>

														<button type="submit" class="btn btn-info"><?php echo _l('cs_transfer'); ?></button>

													</div>
													<?php echo form_close(); ?>

												</div>
												
												<div class="tab-pane fade" id="assign_ticket" role="tabpanel" aria-labelledby="assign_ticket-tab">

													<?php echo form_open_multipart(admin_url('customer_service/ticket_reassign'),array('class'=>'reassign_ticket','autocomplete'=>'off')); ?>
													<input type="hidden" name="ticket_id" value="<?php echo new_html_entity_decode($ticket->id) ?>">

													<div class="col-md-8 col-md-offset-2">
														
														<?php echo render_textarea('re_comment', 'cs_comments', '', ['title' => 'cs_enter_reasons_for_the_assignment_or_instruction_for_assignee', 'placeholder' => _l('cs_enter_reasons_for_the_assignment_or_instruction_for_assignee')]) ?>

														<?php echo render_select('assignee_id', $staffs, array('staffid', array('firstname', 'lastname') ), 'cs_assigned_to', '', ['title' => 'cs_select_staff_member'], [], '', '', true) ?>

														<span><?php echo _l('cs_ticket_is_currently_assigned_to').' <strong>'.get_staff_full_name($ticket->assigned_id).'</strong> '._l('cs_department_label') ?></span><br>
														<button type="submit" class="btn btn-info"><?php echo _l('cs_reassign'); ?></button>

													</div>
													<?php echo form_close(); ?>
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
									<div role="tabpanel" class="tab-pane " id="time_spent">
										<div class="col-md-12">
											<?php if(isset($time_spents) && count($time_spents) > 0){ ?>

											<?php }else{ ?>
												<h5><?php echo _l('cs_there_is_no_time_logs_for_this_ticket'); ?></h5>
											<?php } ?>
										</div>
									</div>
									<div role="tabpanel" class="tab-pane " id="customer_related_information">
										<div class="col-md-12">
											<!-- data for customer in 1 month: related: invoice, payment, project, contract, expenses, warranties, service, inventory, purchase, commission, file-sharing -->

											<!-- button for query data -->
											<div class="hide">
											<a href="<?php echo admin_url('customer_service/add_edit_ticket/'.$ticket->id); ?>" class="btn btn-sm btn-primary mleft5" ><span class="fa fa-eye"></span> <?php echo _l('invoices'); ?></a>
											<a href="<?php echo admin_url('customer_service/add_edit_ticket/'.$ticket->id); ?>" class="btn btn-sm btn-primary mleft5" ><span class="fa fa-eye"></span> <?php echo _l('payments'); ?></a>
											<a href="<?php echo admin_url('customer_service/add_edit_ticket/'.$ticket->id); ?>" class="btn btn-sm btn-primary mleft5" ><span class="fa fa-eye"></span> <?php echo _l('expenses'); ?></a>
											<a href="<?php echo admin_url('customer_service/add_edit_ticket/'.$ticket->id); ?>" class="btn btn-sm btn-primary mleft5" ><span class="fa fa-eye"></span> <?php echo _l('projects'); ?></a>
											<a href="<?php echo admin_url('customer_service/add_edit_ticket/'.$ticket->id); ?>" class="btn btn-sm btn-primary mleft5" ><span class="fa fa-eye"></span> <?php echo _l('contracts'); ?></a>
											<?php if(cs_get_status_modules('warranty_management')){ ?>
												<a href="<?php echo admin_url('customer_service/add_edit_ticket/'.$ticket->id); ?>" class="btn btn-sm btn-primary mleft5" ><span class="fa fa-eye"></span> <?php echo _l('warranties_management_name'); ?></a>
											<?php } ?>

											<?php if(cs_get_status_modules('service_management')){ ?>
												<a href="<?php echo admin_url('customer_service/add_edit_ticket/'.$ticket->id); ?>" class="btn btn-sm btn-primary mleft5" ><span class="fa fa-eye"></span> <?php echo _l('service_management_name'); ?></a>
											<?php } ?>

											<?php if(cs_get_status_modules('warehouse')){ ?>
												<a href="<?php echo admin_url('customer_service/add_edit_ticket/'.$ticket->id); ?>" class="btn btn-sm btn-primary mleft5" ><span class="fa fa-eye"></span> <?php echo _l('warehouse'); ?></a>
											<?php } ?>

											<?php if(cs_get_status_modules('purchase')){ ?>
												<a href="<?php echo admin_url('customer_service/add_edit_ticket/'.$ticket->id); ?>" class="btn btn-sm btn-primary mleft5" ><span class="fa fa-eye"></span> <?php echo _l('purchase'); ?></a>
											<?php } ?>
											</div>

											<!-- show data related this problem -->
											<?php if(count($ticket_the_sames) > 0){ ?>
												<br>
												<h4 class="text-danger"><?php echo _l('These_are_tickets_with_similar_content_to_the_current_ticket'); ?></h4>
												<table class="table items table-bordered">
													<thead>
														<tr>
															<th align="left"><strong><?php echo _l('cs_issue_summary') ?></strong></th>
															<th align="left"><strong><?php echo _l('cs_internal_note') ?></strong></th>
															<th align="left"><strong><?php echo _l('cs_resolution') ?></strong></th>
															<th align="left"><strong><?php echo _l('cs_date_created') ?></strong></th>
														</tr>

													</thead>
													<tbody>
														<?php foreach ($ticket_the_sames as $key => $ticket_the_same) { ?>
															<tr>
																<td ><strong><?php echo ($ticket_the_same['ticket']['issue_summary']) ?></strong></td>
																<td>
																	<?php echo ($ticket_the_same['ticket']['internal_note']) ?>
																</td>
																<td>
																	<?php echo ($ticket_the_same['ticket']['resolution']) ?>
																</td>
																<td>
																	<?php echo _dt($ticket_the_same['ticket']['datecreated']) ?>
																</td>
															</tr>
														<?php } ?>
													</tbody>
												</table>
											<?php } ?>

											<!-- show data in  10 days -->
											<h4 class="text-danger"><?php echo _l('information_related_to_customers_in_the_last_10_days'); ?></h4>

											<!-- invoices -->
											<?php if(isset($invoices) && count($invoices) > 0){ ?>
												<h4 class="text-danger"><?php echo _l('cs_invoices'); ?></h4>
												<table class="table items table-bordered">
													<thead>
														<tr>
															<th align="left"><strong><?php echo _l('invoice_dt_table_heading_number') ?></strong></th>
															<th align="left"><strong><?php echo _l('invoice_dt_table_heading_amount') ?></strong></th>
															<th align="left"><strong><?php echo _l('invoice_total_tax') ?></strong></th>
															<th align="left"><strong><?php echo _l('invoice_dt_table_heading_date') ?></strong></th>
															<th align="left"><strong><?php echo _l('invoice_dt_table_heading_duedate') ?></strong></th>
															<th align="left"><strong><?php echo _l('invoice_dt_table_heading_status') ?></strong></th>
														</tr>

													</thead>

													<tbody>
														<?php foreach ($invoices as $key => $invoice) { ?>
															<tr>
																<td >
																	<a href="<?php echo site_url('invoice/' . $invoice['id'] . '/' . $invoice['hash']) ?>" target="_blank"><?php echo format_invoice_number($invoice['id']) ?></a>
																</td>
																<td>
																	<?php echo app_format_money($invoice['total'], $invoice['currency_name']) ?>
																</td>
																<td>
																	<?php echo app_format_money($invoice['total_tax'], $invoice['currency_name']) ?>
																</td>
																<td>
																	<?php echo _d($invoice['date']) ?>
																</td>
																<td>
																	<?php echo _d($invoice['duedate']) ?>
																</td>

																<td>
																	<?php echo format_invoice_status($invoice['status']) ?>
																</td>
																
															</tr>
														<?php } ?>
													</tbody>
												</table>
											<?php } ?>
											<!-- expenses -->
											<?php if(isset($expenses) && count($expenses) > 0){ ?>
												<h4 class="text-danger"><?php echo _l('cs_expenses'); ?></h4>
												<table class="table items table-bordered">
													<thead>
														<tr>
															<th align="left"><strong><?php echo _l('expense_dt_table_heading_amount') ?></strong></th>
															<th align="left"><strong><?php echo _l('expense_name') ?></strong></th>
															<th align="left"><strong><?php echo _l('expense_dt_table_heading_date') ?></strong></th>
														</tr>

													</thead>

													<tbody>
														<?php foreach ($expenses as $key => $expense) { ?>
															<tr>
																<?php 
																$total    = $expense['amount'];
																$tmpTotal = $total;

																if ($expense['tax'] != 0) {
																	$tax = get_tax_by_id($expense['tax']);
																	$total += ($total / 100 * $tax->taxrate);
																}
																if ($expense['tax2'] != 0) {
																	$tax = get_tax_by_id($expense['tax2']);
																	$total += ($tmpTotal / 100 * $tax->taxrate);
																}
																?>
																<td>
																	<?php echo app_format_number($total) ?>
																</td>
																<td>
																	<a href="<?php echo admin_url('expenses/list_expenses#' . $expense['id'])  ?>" target="_blank"><?php echo new_html_entity_decode($expense['expense_name']); ?></a>

																</td>
																<td>
																	<?php echo _d($expense['date']) ?>
																</td>
																
															</tr>
														<?php } ?>
													</tbody>
												</table>
											<?php } ?>

											<!-- projects -->
											<?php if(isset($projects) && count($projects) > 0){ ?>
												<h4 class="text-danger"><?php echo _l('cs_projects'); ?></h4>
												<table class="table items table-bordered">
													<thead>
														<tr>
															<th align="left"><strong><?php echo _l('project_name') ?></strong></th>
															<th align="left"><strong><?php echo _l('project_start_date') ?></strong></th>
															<th align="left"><strong><?php echo _l('project_deadline') ?></strong></th>
															<th align="left"><strong><?php echo _l('project_status') ?></strong></th>
														</tr>

													</thead>

													<tbody>
														<?php foreach ($projects as $key => $project) { ?>
															<tr>
																<td>
																	<a href="<?php echo admin_url('projects/view/' . $project['id'])  ?>" target="_blank"><?php echo new_html_entity_decode($project['name']); ?></a>
																</td>
																<td>
																	<?php echo _d($project['start_date']) ?>
																</td>
																<td>
																	<?php echo _d($project['deadline']) ?>
																</td>
																<?php 

																$status = get_project_status_by_id($project['status']);
																?>

																<td>
																	<span class="label label inline-block project-status-<?php echo new_html_entity_decode($project['status']); ?>" style="color:<?php echo new_html_entity_decode($status['color']) ?>;border:1px solid <?php echo new_html_entity_decode($status['color']) ?>"><?php echo new_html_entity_decode($status['name']); ?></span>
																</td>
															</tr>
														<?php } ?>
													</tbody>
												</table>
											<?php } ?>

											<!-- contracts -->
											<?php if(isset($contracts) && count($contracts) > 0){ ?>

												<h4 class="text-danger"><?php echo _l('cs_contracts'); ?></h4>
												<table class="table items table-bordered">
													<thead>
														<tr>
															<th align="left"><strong><?php echo _l('contract_list_subject') ?></strong></th>
															<th align="left"><strong><?php echo _l('contract_value') ?></strong></th>
															<th align="left"><strong><?php echo _l('contract_list_start_date') ?></strong></th>
															<th align="left"><strong><?php echo _l('contract_list_end_date') ?></strong></th>
															<th align="left"><strong><?php echo _l('signature') ?></strong></th>
														</tr>
													</thead>

													<tbody>
														<?php 
														$base_currency = get_base_currency();
														 ?>
														<?php foreach ($contracts as $key => $contract) { ?>
															<tr>
																<td>
																	<a href="<?php echo admin_url('contracts/contract/' . $contract['id'])  ?>" target="_blank"><?php echo new_html_entity_decode($contract['subject']); ?></a>
																</td>
																<td>
																	<?php echo app_format_money($contract['contract_value'], $base_currency); ?>
																</td>
																<td>
																	<?php echo _d($contract['datestart']) ?>
																</td>
																<td>
																	<?php echo _d($contract['dateend']) ?>
																</td>

																<td>
																	<?php 
																	if ($contract['marked_as_signed'] == 1) {
																		echo '<span class="text-success">' . _l('marked_as_signed') . '</span>';
																	} elseif (!empty($contract['signature'])) {
																		echo '<span class="text-success">' . _l('is_signed') . '</span>';
																	} else {
																		echo '<span class="text-muted">' . _l('is_not_signed') . '</span>';
																	}
																	?>
																</td>
															</tr>
														<?php } ?>
													</tbody>
												</table>
											<?php } ?>

											<!-- warranties_managements -->
											<?php if(isset($warranties_managements) && count($warranties_managements) > 0){ ?>
												<?php 
												$get_base_currency =  get_base_currency();
												if($get_base_currency){
													$base_currency_id = $get_base_currency->id;
												}else{
													$base_currency_id = 0;
												}
												?>
												<h4 class="text-danger"><?php echo _l('cs_warranties_managements'); ?></h4>
												<table class="table items table-bordered">
													<thead>
														<tr>
															<th align="left"><strong><?php echo _l('wm_order_number_delivery_note') ?></strong></th>
															<th align="left"><strong><?php echo _l('wm_invoice') ?></strong></th>
															<th align="left"><strong><?php echo _l('cs_product_service_name') ?></strong></th>
															<th align="left"><strong><?php echo _l('wm_rate') ?></strong></th>
															<th align="left"><strong><?php echo _l('wm_quantity') ?></strong></th>
															<th align="left"><strong><?php echo _l('wm_expriry_date') ?></strong></th>
															<th align="left"><strong><?php echo _l('wm_lot_number') ?></strong></th>
															<th align="left"><strong><?php echo _l('wm_serial_number') ?></strong></th>
															<th align="left"><strong><?php echo _l('wm_warranty_period') ?></strong></th>
														</tr>
													</thead>

													<tbody>
														
														<?php foreach ($warranties_managements as $key => $warranties_management) { ?>
															<tr>
																<td>
																	<?php 
																	if(!isset($warranties_management['start_date'])){
																		$value = get_goods_delivery_code($warranties_management['order_id']) != null ? get_goods_delivery_code($warranties_management['order_id'])->goods_delivery_code : '';
																		echo '<a href="' . admin_url('warehouse/manage_delivery/' . $warranties_management['order_id']) . '" >'. $value.'</a>';
																	}else{
																		echo '<a href="' . admin_url('service_management/order_detail/' . $warranties_management['order_id'] ).'" >' . sm_order_code($warranties_management['order_id']) . '</a>';
																	}

																	?>
																</td>
																<td>
																	<?php 
																	echo '<a href="' . admin_url('invoices#' . $warranties_management['invoice_id'] ).'" >' . format_invoice_number($warranties_management['invoice_id']) . '</a>';
																	 ?>
																</td>
																<td>
																	<?php 
																	if(!isset($warranties_management['commodity_code'])){
																		echo new_html_entity_decode($warranties_management['item_name']);
																	}else{
																		if(new_strlen($warranties_management['item_name']) > 0){
																			echo new_html_entity_decode($warranties_management['item_name']);
																		}else{
																			echo cs_get_item_variatiom($warranties_management['commodity_code']);
																		}
																	}
																	?>
																</td>
																<td>

																	<?php 
																	if(!isset($warranties_management['billing_plan_rate'])){
																		echo app_format_money((float)$warranties_management['rate'], $base_currency_id);
																	}else{
																		echo app_format_money((float)$warranties_management['billing_plan_rate'], $base_currency_id).' ('. $warranties_management['billing_plan_value'].' '. _l($warranties_management['billing_plan_type']) . ')';
																	}
																	?>
																</td>
																<td><?php echo new_html_entity_decode($warranties_management['quantity']); ?></td>
																<td>
																	<?php 
																	if(isset($warranties_management['expiry_date'])){
																		echo new_html_entity_decode($warranties_management['expiry_date']);
																	}else{
																		echo '...';
																	}
																	?>
																</td>
																<td>
																	<?php 
																	if(isset($warranties_management['expiry_date'])){
																		echo new_html_entity_decode($warranties_management['lot_number']);
																	}else{
																		echo '...';
																	}

																	?>
																</td>
																<td>
																	<?php 

																	if(isset($warranties_management['expiry_date'])){
																		echo new_html_entity_decode($warranties_management['serial_number']);
																	}else{
																		echo '...';
																	}
																	?>
																</td>
																<td>
																	<?php 
																	if(isset($warranties_management['start_date'])){
																		echo _dt($warranties_management['start_date']) .' - '. _dt($warranties_management['expiration_date']);
																	}else{
																		if($warranties_management['expiration_date'] != null && new_strlen($warranties_management['expiration_date']) > 0){
																			echo _d($warranties_management['date_add']) .' - '. _d($warranties_management['expiration_date']);
																		}else{
																			echo _d($warranties_management['date_add']) .' - ...';
																		}
																	}
																	?>
																</td>

																
															</tr>
														<?php } ?>
													</tbody>
												</table>
											<?php } ?>

											<!-- warranty_claims -->
											<?php if(isset($warranty_claims) && count($warranty_claims) > 0){ ?>

												<h4 class="text-danger"><?php echo _l('cs_warranty_claims'); ?></h4>
												<table class="table items table-bordered">
													<thead>
														<tr>
															<th align="left"><strong><?php echo _l('wm_claim_code') ?></strong></th>
															<th align="left"><strong><?php echo _l('wm_created_by') ?></strong></th>
															<th align="left"><strong><?php echo _l('wm_created_type') ?></strong></th>
															<th align="left"><strong><?php echo _l('wm_description') ?></strong></th>
															<th align="left"><strong><?php echo _l('cs_date_created') ?></strong></th>
															<th align="left"><strong><?php echo _l('cs_status') ?></strong></th>
														</tr>
													</thead>

													<tbody>
														<?php 
														$base_currency = get_base_currency();
														?>
														<?php foreach ($warranty_claims as $key => $warranty_claim) { ?>
															<tr>
																<td>
																	<a href="<?php echo admin_url('warranty_management/warranty_claim_detail/' . $warranty_claim['id'])  ?>" target="_blank"><?php echo new_html_entity_decode($warranty_claim['claim_code']); ?></a>
																</td>
																<td>
																	<?php 
																	if($warranty_claim['created_type'] == 'staff'){
																		echo get_staff_full_name($warranty_claim['created_id']);
																	}else{
																		echo get_contact_full_name($warranty_claim['created_id']);
																	}
																	?>
																</td>
																<td>
																	<?php echo new_html_entity_decode($warranty_claim['created_type']) ?>
																</td>
																<td>
																	<?php echo new_html_entity_decode($warranty_claim['description']) ?>
																</td>
																<td>
																	<?php echo _dt($warranty_claim['datecreated']) ?>
																</td>

																<td>
																	<?php 
																	echo render_warranty_status_html($warranty_claim['id'], 'warranty_claim', $warranty_claim['status']);
																	?>
																</td>
															</tr>
														<?php } ?>
													</tbody>
												</table>
											<?php } ?>

											<!-- transactions -->

											<!-- orders -->

											<!-- goods_delivery -->


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
							<a href="<?php echo admin_url('customer_service/tickets'); ?>"class="btn btn-info text-right"><?php echo _l('cs_close'); ?></a>
						</div>
					</div>
					<div class="btn-bottom-pusher"></div>
				</div>
			</div>

		</div>
		<div id="modal_wrapper"></div>
	</div>
</div>

<?php init_tail(); ?>
<?php require 'modules/customer_service/assets/js/tickets/ticket_detail_js.php';?>
<?php require 'modules/customer_service/assets/js/customer_service_js.php';?>
