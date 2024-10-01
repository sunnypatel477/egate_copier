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

											<div class="col-md-6">
												<h4><strong><?php  echo new_html_entity_decode($sla->code.' '.$sla->name); ?></strong></h4>
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
							<h4><strong><?php echo _l('cs_sla_details'); ?></strong></h4>

							<div class="row">
								<div class="col-md-6">
									<table class="table border table-striped no-mtop">
										<tbody>
											<tr class="project-overview">
												<td class="bold"><?php echo _l('cs_grace_period_label'); ?></td>
												<td><?php echo new_html_entity_decode($sla->grace_period.' '._l('cs_hours')) ; ?></td>
											</tr>
											
										</tbody>
									</table>
								</div>
								<div class="col-md-12">
									<html><?php echo new_html_entity_decode($sla->admin_note); ?></html>
								</div>
							</div>

							<div class="row">
								<div class="horizontal-scrollable-tabs preview-tabs-top">
									<div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
									<div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
									<div class="horizontal-tabs">
										<ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
											<li role="presentation" class="active">
												<a href="#stages" aria-controls="stages"  class="stages" role="tab" data-toggle="tab">
													<i class="fa-sharp fa-solid fa-brake-warning"></i>&nbsp;<?php echo _l('cs_sla_violation_ticket'); ?>
												</a>
											</li>

											<li role="presentation" class="">
												<a href="#ticket_workflow" aria-controls="ticket_workflow"  class="ticket_workflow" role="tab" data-toggle="tab">
													<i class="fa-brands fa-stack-overflow"></i>&nbsp;<?php echo _l('cs_tickets'); ?>
												</a>
											</li>

										</ul>
									</div>
								</div>

								<div class="tab-content">
									<div role="tabpanel" class="tab-pane active" id="stages">
										<div class="col-md-12">
											<div class="table-responsive">

												<?php if(isset($sla_rule['arr_ticket_violate']) && count($sla_rule['arr_ticket_violate']) > 0){ ?>
													<table class="table items table-bordered no-mtop">
														<thead>
															<tr>
																<th align="left"><strong><?php echo _l('cs_code_label') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_created_id') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_created_type') ?></strong></th>
																<th align="left"><strong><?php echo _l('client') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_ticket_source') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_category') ?></strong></th>
																<th align="left"><strong><?php echo _l('department') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_assigned_to') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_sla') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_time_spent') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_due_date') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_issue_summary') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_first_reply_time') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_date_created') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_customer_satisfied_rating') ?></strong></th>
																
															</tr>
														</thead>
														<tbody>
															<?php foreach ($sla_rule['arr_ticket_violate'] as $key => $ticket_data) { ?>
																<tr>
																	<td ><a href="<?php echo admin_url('customer_service/ticket_detail/' . $ticket_data['id'] ) ?>" ><?php echo new_html_entity_decode($ticket_data['code']); ?></a></td>
																	<td >
																		<?php 
																		if($ticket_data['created_type'] == 'staff'){
																			echo get_staff_full_name($ticket_data['created_id']);
																		}else{
																			echo get_contact_full_name($ticket_data['created_id']);
																		}
																		?>
																	</td>
																	
																	<td >
																		<?php echo new_html_entity_decode($ticket_data['created_type']); ?>
																	</td>
																	<td >
																		<?php echo get_company_name($ticket_data['client_id']); ?>
																	</td>
																	<td >
																		<?php echo _l($ticket_data['ticket_source']); ?>
																	</td>
																	
																	<td >
																		<?php echo cs_get_category_name($ticket_data['category_id']); ?>
																	</td>
																	<td >
																		<?php echo cs_get_department_name($ticket_data['department_id']); ?>
																	</td>
																	<td >
																		<?php echo get_staff_full_name($ticket_data['assigned_id']); ?>
																	</td>
																	<td >
																		<?php echo cs_get_sla_name($ticket_data['sla_id']); ?>
																	</td>
																	<td >
																		<?php echo new_html_entity_decode($ticket_data['time_spent']); ?>
																	</td>
																	<td >
																		<?php echo new_html_entity_decode($ticket_data['due_date']); ?>
																	</td>
																	<td >
																		<?php echo new_html_entity_decode($ticket_data['issue_summary']); ?>
																	</td>

																	<td >
																		<?php echo _dt($ticket_data['first_reply_time']); ?>
																	</td>
																	<td >
																		<?php echo _dt($ticket_data['datecreated']); ?>
																	</td>
																	<td >
																		<?php 
																		if($ticket_data['client_rating'] == 1){
																			echo _l('cs_very_unsatisfied');
																		}elseif($ticket_data['client_rating'] == 2){
																			echo _l('cs_very_satisfied');

																		}elseif($ticket_data['client_rating'] == 3){
																			echo _l('cs_unsatisfied');

																		}elseif($ticket_data['client_rating'] == 4){
																			echo _l('cs_neutral');

																		}elseif($ticket_data['client_rating'] == 5){
																			echo _l('cs_satisfied');

																		}

																		 ?>
																	</td>
																	
																	
																</tr>
															<?php } ?>
														</tbody>
													</table>
												<?php }else{ ?>
													<h5><?php echo _l('cs_There_are_not_found_tickets_that_violate_this_KPI'); ?></h5>
												<?php } ?>

											</div>
										</div>
									</div>

									<div role="tabpanel" class="tab-pane " id="ticket_workflow">
										<div class="col-md-12">
											<div class="table-responsive">

												<?php if(isset($sla_rule['arr_total_ticket']) && count($sla_rule['arr_total_ticket']) > 0){ ?>
													<table class="table items table-bordered no-mtop">
														<thead>
															<tr>
																<th align="left"><strong><?php echo _l('cs_code_label') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_created_id') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_created_type') ?></strong></th>
																<th align="left"><strong><?php echo _l('client') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_ticket_source') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_category') ?></strong></th>
																<th align="left"><strong><?php echo _l('department') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_assigned_to') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_sla') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_time_spent') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_due_date') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_issue_summary') ?></strong></th>
															
																<th align="left"><strong><?php echo _l('cs_first_reply_time') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_date_created') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_customer_satisfied_rating') ?></strong></th>
																<th align="left"><strong><?php echo _l('cs_status') ?></strong></th>
																
															</tr>
														</thead>
														<tbody>
															<?php foreach ($sla_rule['arr_total_ticket'] as $key => $ticket_data) { ?>
																<tr>
																	<td ><a href="<?php echo admin_url('customer_service/ticket_detail/' . $ticket_data['id'] ) ?>" ><?php echo new_html_entity_decode($ticket_data['code']); ?></a></td>
																	<td >
																		<?php 
																		if($ticket_data['created_type'] == 'staff'){
																			echo get_staff_full_name($ticket_data['created_id']);
																		}else{
																			echo get_contact_full_name($ticket_data['created_id']);
																		}
																		?>
																	</td>
																	
																	<td >
																		<?php echo new_html_entity_decode($ticket_data['created_type']); ?>
																	</td>
																	<td >
																		<?php echo get_company_name($ticket_data['client_id']); ?>
																	</td>
																	<td >
																		<?php echo _l($ticket_data['ticket_source']); ?>
																	</td>
																	
																	<td >
																		<?php echo cs_get_category_name($ticket_data['category_id']); ?>
																	</td>
																	<td >
																		<?php echo cs_get_department_name($ticket_data['department_id']); ?>
																	</td>
																	<td >
																		<?php echo get_staff_full_name($ticket_data['assigned_id']); ?>
																	</td>
																	<td >
																		<?php echo cs_get_sla_name($ticket_data['sla_id']); ?>
																	</td>
																	<td >
																		<?php echo new_html_entity_decode($ticket_data['time_spent']); ?>
																	</td>
																	<td >
																		<?php echo new_html_entity_decode($ticket_data['due_date']); ?>
																	</td>
																	<td >
																		<?php echo new_html_entity_decode($ticket_data['issue_summary']); ?>
																	</td>
																	

																	<td >
																		<?php echo _dt($ticket_data['first_reply_time']); ?>
																	</td>
																	<td >
																		<?php echo _dt($ticket_data['datecreated']); ?>
																	</td>
																	<td >
																		<?php 
																		if($ticket_data['client_rating'] == 1){
																			echo _l('cs_very_unsatisfied');
																		}elseif($ticket_data['client_rating'] == 2){
																			echo _l('cs_very_satisfied');

																		}elseif($ticket_data['client_rating'] == 3){
																			echo _l('cs_unsatisfied');

																		}elseif($ticket_data['client_rating'] == 4){
																			echo _l('cs_neutral');

																		}elseif($ticket_data['client_rating'] == 5){
																			echo _l('cs_satisfied');

																		}
																		 ?>
																	</td>
																	<td >
																		<?php echo render_customer_status_html($ticket_data['id'], 'ticket_status', $ticket_data['status'], false); ?>
																	</td>
																	
																	
																</tr>
															<?php } ?>
														</tbody>
													</table>
												<?php }else{ ?>
													<h5><?php echo _l('cs_There_are_not_found_tickets_that_violate_this_KPI'); ?></h5>
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
							<a href="<?php echo site_url('customer_service/sla_manage'); ?>"class="btn btn-info text-right"><?php echo _l('cs_close'); ?></a>
						</div>
					</div>
					<div class="btn-bottom-pusher"></div>
				</div>
			</div>

		</div>
	</div>
</div>
<?php init_tail(); ?>


