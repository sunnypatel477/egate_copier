<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Disciplinary Action Templete -->
<div>
	<div class="_buttons">
		<?php if(is_admin() || has_permission('hrm_setting','','create')){ ?>
			<a href="#" onclick="new_disciplinary_action_type(); return false;" class="btn btn-primary pull-left display-block">
				<?php echo _l('disciplinary_action_templates'); ?>
			</a>
		<?php } ?>
	</div>
	<div class="clearfix"></div>
	<br>
	<table class="table dt-table">
		<thead>
			<th width="5%"><?php echo _l('id'); ?></th>
			<th width="30%"><?php echo _l('Score'); ?></th>
			<th width="30%"><?php echo _l('discipline_category'); ?></th>
			<th width="10%"><?php echo _l('discipline_reason_name'); ?></th>
			<th width="30%"><?php echo _l('discipline_reason_remarks'); ?></th>
			<th><?php echo _l('options'); ?></th>
		</thead>
		<tbody>
			<?php foreach($disciplinary_action_templates as $c){ 
				?>
				<tr>
					<td><?php echo new_html_entity_decode($c['id']); ?></td>
					<td><?php echo new_html_entity_decode($c['score']); ?></td>
					<td><?php echo get_discipline_name($c['discipline_category']); ?></td>
					<td><?php echo new_html_entity_decode($c['discipline_reason_name']); ?></td>
					<td><?php echo new_html_entity_decode($c['discipline_reason_remarks']); ?></td>
					<td>
						<?php if(is_admin() || has_permission('hrm_setting','','edit')){ ?>
							<a href="#" onclick="edit_disciplinary_action_type(this,<?php echo new_html_entity_decode($c['id']); ?>); return false"  class="btn btn-default btn-icon"><i class="fa-regular fa-pen-to-square"></i></a>
						<?php } ?>

						<?php if(is_admin() || has_permission('hrm_setting','','delete')){ ?>
							<a href="<?php echo admin_url('hr_profile/delete_disciplinary_action_type/'.$c['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
						<?php } ?>

					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>     

	<div class="modal fade" id="disciplinary_action" tabindex="-1" role="dialog">
				<div class="modal-dialog modal-lg">
					<?php echo form_open(admin_url('hr_profile/disciplinary_action_history')); ?>

					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">
								<span class="edit-title"><?php echo _l('hr_edit_disciplinary'); ?></span>
								<span class="add-title"><?php echo _l('hr_add_disciplinary'); ?></span>
							</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div id="disciplinary_action_history_id"></div>
									<div class="form">
									<div id="dependent_action_id"></div>  
										<div class="row">
											<div class="col-md-6">
												<?php echo render_select('discipline_category', $disciplinary, ['id', 'name'], 'discipline_category', '', ['data-actions-box' => true], [], '', '', true); ?>
											</div>
											<div class="col-md-6">
												<?php
												echo render_input('discipline_reason_name', 'discipline_reason_name'); ?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<?php
												echo render_input('discipline_reason_remarks', 'discipline_reason_remarks'); ?>
											</div>
											<div class="col-md-6">
												<?php
												echo render_input('Score', 'Score', '', 'number'); ?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="bold"><?php echo _l('company_rules'); ?></p>
												<?php echo render_textarea('company_rules', '', '', array(), array(), '', 'tinymce'); ?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="bold"><?php echo _l('content_template'); ?></p>
												<?php echo render_textarea('content_template', '', '', array(), array(), '', 'tinymce'); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('hr_close'); ?></button>
							<button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
						</div>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
</div>
</body>
</html>
