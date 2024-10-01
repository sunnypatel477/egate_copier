<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php hooks()->do_action('app_customers_portal_head'); ?>

<div class="panel_s section-heading section-invoices">
	<div class="panel-body">
		<div class="col-md-6">
			<h4 class="no-margin section-text"><?php echo _l('cs_tickets'); ?></h4>
			<br>
			<span><a href="<?php echo admin_url('customer_service/customer_service_client/support_term_condition') ?>"><?php echo _l('cs_support_term_condition'); ?></a></span>
		</div>

		<div class="col-md-6">
			<ul class="nav navbar-nav navbar-right">
				<a href="<?php echo admin_url('customer_service/customer_service_client/add_edit_ticket') ?>" class="btn btn-info"><?php echo _l('cs_add_ticket'); ?></a>
			</ul>

		</div>
	</div>
</div>

<div class="panel_s">
	<div class="panel-body">
		<div class="row mbot25">
			<div class="col-md-2 list-status projects-status">
				<a href="<?php echo site_url('customer_service/customer_service_client/tickets/all') ?>" class=" cs-portal-a-pause" >
					<h4 class="bold text-uppercase text-success cs-portal-h-cancelled" ><?php echo _l('cs_all'); ?></h4>
					<span class="bold cs-portal-h-cancelled" ><?php echo app_format_number(isset($ticket_status['all']) ? $ticket_status['all'] : 0 , true) ?></span>
				</a>
			</div>

			<div class="col-md-2 list-status statement-bg  projects-status">
				<a href="<?php echo site_url('customer_service/customer_service_client/tickets/open') ?>" class=" cs-portal-a-cancelled" >
					<h4 class="bold text-uppercase cs-portal-h-cancelled" ><?php echo _l('cs_open') ?></h4>
					<span class="bold cs-portal-h-cancelled" ><?php echo app_format_number(isset($ticket_status['open']) ? $ticket_status['open'] : 0 , true) ?></span>
				</a>
			</div>

			<div class="col-md-2 list-status projects-status">
				<a href="<?php echo site_url('customer_service/customer_service_client/tickets/inprogress') ?>" class=" cs-portal-a-renewal" >
					<h4 class="bold text-uppercase cs-portal-h-cancelled" ><?php echo _l('cs_inprogress'); ?></h4>
					<span class="bold cs-portal-h-cancelled" ><?php echo app_format_number(isset($ticket_status['inprogress']) ? $ticket_status['inprogress'] : 0 , true) ?></span>
				</a>
			</div>

			<div class="col-md-2 list-status projects-status">
				<a href="<?php echo site_url('customer_service/customer_service_client/tickets/answered') ?>" class=" cs-portal-a-expired">
					<h4 class="bold text-uppercase cs-portal-h-cancelled" ><?php echo _l('cs_answered'); ?></h4>
					<span class="bold cs-portal-h-cancelled" ><?php echo app_format_number(isset($ticket_status['answered']) ? $ticket_status['answered'] : 0 , true) ?></span>
				</a>
			</div>

			<div class="col-md-2 list-status projects-status">
				<a href="<?php echo site_url('customer_service/customer_service_client/tickets/on_hold') ?>" class=" cs-portal-a-complete">
					<h4 class="bold text-uppercase text-success cs-portal-h-cancelled" ><?php echo _l('cs_on_hold'); ?></h4>
					<span class="bold cs-portal-h-cancelled" ><?php echo app_format_number(isset($ticket_status['on_hold']) ? $ticket_status['on_hold'] : 0 , true) ?></span>
				</a>
			</div>

			
			<div class="col-md-2 list-status projects-status">
				<a href="<?php echo site_url('customer_service/customer_service_client/tickets/closed') ?>" class=" cs-portal-a-activate" >
					<h4 class="bold text-uppercase text-success cs-portal-h-cancelled" ><?php echo _l('cs_close') ; ?></h4>
					<span class="bold cs-portal-h-cancelled" ><?php echo app_format_number(isset($ticket_status['closed']) ? $ticket_status['closed'] : 0 , true) ?></span>
				</a>
			</div>

		</div>

		<hr />
		<table class="table dt-table table-invoices" data-order-col="1" data-order-type="desc">
			<thead>
				<tr>
					<th class="th-invoice-number hide"><?php echo _l('id'); ?></th>
					<th class="th-invoice-number "><?php echo _l('cs_code_label'); ?></th>
					<th class="th-invoice-number"><?php echo _l('cs_created_id'); ?></th>
					<th class="th-invoice-number"><?php echo _l('cs_created_type'); ?></th>
					<th class="th-invoice-number hide"><?php echo _l('client'); ?></th>
					<th class="th-invoice-number"><?php echo _l('cs_issue_summary'); ?></th>
					<th class="th-invoice-number"><?php echo _l('cs_due_date'); ?></th>
					<th class="th-invoice-number"><?php echo _l('cs_time_spent'); ?></th>
					<th class="th-invoice-number"><?php echo _l('cs_date_created'); ?></th>
					<th class="th-invoice-number"><?php echo _l('cs_status'); ?></th>
					<th class="th-invoice-number"><?php echo _l('cs_options'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($tickets as $ticket){ ?>
					<tr>

						<td class="hide" data-order="<?php echo new_html_entity_decode($ticket['id']); ?>"><?php echo new_html_entity_decode($ticket['id']); ?></td>

						<td  class="" data-order="<?php echo new_html_entity_decode($ticket['code']); ?>"><?php echo new_html_entity_decode($ticket['code']); ?></td>


						<?php 
						$created_name = '';
						if($ticket['created_type'] == 'staff'){
							$created_name = get_staff_full_name($ticket['created_id']);
						}else{
							$created_name = get_contact_full_name($ticket['created_id']);
						}
						?>
						<td data-order="<?php echo new_html_entity_decode($created_name != null ? $created_name : ''); ?>"><?php echo new_html_entity_decode($created_name != null ? $created_name : ''); ?></td>
						<td data-order="<?php echo new_html_entity_decode($ticket['created_type'] != null ? $ticket['created_type'] : ''); ?>"><?php echo new_html_entity_decode($ticket['created_type'] != null ? $ticket['created_type'] : ''); ?></td>

						<td class="hide"> data-order="<?php echo new_html_entity_decode(get_company_name($ticket['client_id'])); ?>"><?php echo new_html_entity_decode(get_company_name($ticket['client_id'])); ?></td>

						<td data-order="<?php echo new_html_entity_decode($ticket['issue_summary'] != null ? $ticket['issue_summary'] : ''); ?>"><?php echo strip_tags($ticket['issue_summary']); ?></td>
						<td data-order="<?php echo new_html_entity_decode($ticket['due_date'] != null ? $ticket['due_date'] : ''); ?>"><?php echo ($ticket['due_date']); ?></td>
						<td data-order="<?php echo new_html_entity_decode($ticket['time_spent'] != null ? $ticket['time_spent'] : ''); ?>"><?php echo ($ticket['time_spent']); ?></td>
						<td data-order="<?php echo new_html_entity_decode($ticket['datecreated'] != null ? $ticket['datecreated'] : ''); ?>"><?php echo _dt($ticket['datecreated']); ?></td>

						<td data-order=""><?php echo render_customer_status_html($ticket['id'], 'ticket_status', $ticket['status']) ?></td>

						<?php 
						$options = '';
						
						$options .= '<a href="'.site_url('customer_service/customer_service_client/ticket_detail/'.$ticket['id']).'" class="btn btn-primary btn-icon" data-original-title="View" data-toggle="tooltip" data-placement="top">
						<i class="fa fa-eye"></i>
						</a>';

						if($ticket['client_rating'] == 0 && $ticket['status'] == 'closed'){

							$options .= '&nbsp;'.icon_btn('#', 'fa-regular fa-pen-to-square', 'btn-primary', ['data-original-title' => _l('cs_rating'), 'data-toggle' => 'tooltip', 'data-placement' => 'top',
								'onclick'    => 'rating_modal('.$ticket['id'].'); return false;',
							]);
						}

						?>

						<td data-order="<?php echo new_html_entity_decode($ticket['status'] != null ? $ticket['status'] : ''); ?>"><?php echo new_html_entity_decode($options); ?> </td>

					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<div id="modal_wrapper"></div>

<?php echo form_open_multipart(site_url('customer_service/customer_service_client/ticket_rating'), array('id' => 'ticket_rating')); ?>
<input type="hidden" name="ticket_id" value="">
<div class="modal fade" id="rating_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 title">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">
						</h4>
						<hr>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="rating d-flex text-center">
							<h4><?php echo _l('cs_how_satisfied_are_you_with_our_customer_service'); ?></h4>
							<label for="rating1" data-original-title="<?php echo _l('cs_very_unsatisfied'); ?>" data-toggle="tooltip" data-placement="bottom">
								<input type="radio" name="rating_value" value="1" id="rating1" />
								<img src="<?php echo site_url('modules/customer_service/assets/emot/tear-line.svg')  ?>" alt="" class="emot-line" title="11">
								<img src="<?php echo site_url('modules/customer_service/assets/emot/tear.svg')  ?>" alt="" class="emot-fill">
							</label>

							<label for="rating2" data-original-title="<?php echo _l('cs_very_satisfied'); ?>" data-toggle="tooltip" data-placement="bottom">
								<input type="radio" name="rating_value" value="2" id="rating2" />
								<img src="<?php echo site_url('modules/customer_service/assets/emot/sad.svg')  ?>" alt="" class="emot-fill">
								<img src="<?php echo site_url('modules/customer_service/assets/emot/sad-line.svg')  ?>" alt="" class="emot-line">
							</label>


							<label for="rating3" data-original-title="<?php echo _l('cs_unsatisfied'); ?>" data-toggle="tooltip" data-placement="bottom">
								<input type="radio" name="rating_value" value="3" id="rating3" />
								<img src="<?php echo site_url('modules/customer_service/assets/emot/meh-line.svg')  ?>" alt="" class="emot-line">
								<img src="<?php echo site_url('modules/customer_service/assets/emot/meh.svg')  ?>" alt="" class="emot-fill">
							</label>

							<label for="rating4" data-original-title="<?php echo _l('cs_neutral'); ?>" data-toggle="tooltip" data-placement="bottom">
								<input type="radio" name="rating_value" value="4" id="rating4" />
								<img src="<?php echo site_url('modules/customer_service/assets/emot/smile-line.svg')  ?>" alt="" class="emot-line">
								<img src="<?php echo site_url('modules/customer_service/assets/emot/smile.svg')  ?>" alt="" class="emot-fill">
							</label>

							<label for="rating5" data-original-title="<?php echo _l('cs_satisfied'); ?>" data-toggle="tooltip" data-placement="bottom">
								<input type="radio" name="rating_value" value="5" id="rating5" checked />
								<img src="<?php echo site_url('modules/customer_service/assets/emot/laugh-line.svg')  ?>" alt="" class="emot-line">
								<img src="<?php echo site_url('modules/customer_service/assets/emot/laugh.svg')  ?>" alt="" class="emot-fill">
								
							</label>
						</div>
					</div>
				</div>
			</div>   
			<div class="modal-footer">
				<button type="button" class="btn btn-default close_btn" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
			</div>           
		</div>
	</div>
</div>
<?php echo form_close(); ?>


<?php hooks()->do_action('app_customers_portal_footer'); ?>
<?php require 'modules/customer_service/assets/js/client_portals/tickets/manage_js.php';?>

