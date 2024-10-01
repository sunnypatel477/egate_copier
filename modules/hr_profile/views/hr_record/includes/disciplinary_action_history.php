			<div class="row">
				<div class="col-md-12">
					<?php if ($member->staffid == get_staff_user_id() || has_permission('hrm_hr_records', '', 'create') || has_permission('hrm_hr_records', '', 'edit')) { ?>
						<div class="_buttons">
							<a href="#" onclick="new_disciplinary_history(); return false;" class="btn btn-primary pull-left display-block">
								<?php echo _l('hr_add_disciplinary_history'); ?>
							</a>
						</div>
					<?php } ?>
				</div>
			</div>

			<div class="clearfix"></div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<?php render_datatable(array(
						_l('id'),
						_l('hr_hr_staff_name'),
						_l('date'),
						_l('discipline_category'),
						_l('discipline_reason_name'),
						_l('action_taken'),
						_l('Point'),
						_l('file_attachment'),
						_l('action'),
					), 'table_disciplinary_history'); ?>
				</div>
			</div>

			<div class="modal fade" id="disciplinary" tabindex="-1" role="dialog">
				<div class="modal-dialog modal-lg">
					<?php echo form_open_multipart(admin_url('hr_profile/disciplinary_history')); ?>

					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">
								<span class="edit-title"><?php echo get_staff_full_name($member->staffid); ?></span>
								<span class="add-title"><?php echo get_staff_full_name($member->staffid); ?></span>
							</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div id="disciplinary_history_id"></div>
									<div class="form">
										<?php echo form_hidden('staff_id', $member->staffid); ?>
										<div id="dependent_person_id"></div>
										<div class="row">
											<div class="col-md-6">
												<?php echo render_select('discipline_category', $disciplinary, ['id', 'name'], 'discipline_category', '', ['data-actions-box' => true], [], '', '', true); ?>
											</div>
											<div class="col-md-6">
												<?php echo render_select('reason', '', ['id', 'discipline_reason_name'], 'hr_reason_label', '', ['data-actions-box' => true], [], '', '', true); ?>
											</div>
										</div>
										<div class="row">
											
											<div class="col-md-6">
												<?php
												echo render_date_input('date', 'date',_d(date('Y-m-d'))); ?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<?php echo render_textarea('content', 'company_rules', '', array(), array(), '', 'tinymce'); ?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<?php echo render_textarea('content_template', 'content_template', '', array(), array(), '', 'tinymce'); ?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-8">
												<?php echo render_select('action_taken', $penalty, ['id', 'name'], 'action_taken_label', '', ['data-actions-box' => true], [], '', '', true); ?>
											</div>

											<div class="col-md-4">
												<?php echo render_input('penalty_point', 'penalty_point'); ?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<?php echo render_textarea('remark', 'Remark'); ?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<?php echo render_input('file', 'discipline_file', '', 'file'); ?>
												<!-- <span id="file_data"></span> -->
											</div>
										</div>

										<!-- download image  -->
										<a href="" id="image_download_a" target="_blank">
										</a>
										<!-- download image  -->

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
