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
												<h4><strong><?php  echo new_html_entity_decode($workflow->code.' '.$workflow->workflow_name); ?></strong></h4>
											</div>

											<div class="col-md-6 ">
												<div class="pull-right">
													<a href="<?php echo site_url('customer_service/add_edit_work_flow/'.$workflow->id); ?>" class="btn btn-sm btn-primary mleft5" ><span class="fa-regular fa-pen-to-square"></span> <?php echo _l('edit'); ?></a>

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
												<td class="bold"><?php echo _l('cs_SLA_plan'); ?></td>
												<td><?php echo cs_get_sla_name($workflow->sla_id) ; ?></td>
											</tr>
											<tr class="project-overview">
												<td class="bold"><?php echo _l('cs_kpi'); ?></td>
												<td><?php echo cs_get_kpi_name($workflow->kpi_id) ; ?></td>
											</tr>


										</tbody>
									</table>
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
							<a href="<?php echo site_url('customer_service/work_flows'); ?>"class="btn btn-info text-right"><?php echo _l('cs_close'); ?></a>
						</div>
					</div>
					<div class="btn-bottom-pusher"></div>
				</div>
			</div>

		</div>
	</div>
</div>
<?php init_tail(); ?>


