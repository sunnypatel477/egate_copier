<div class="modal fade" id="appointmentModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
				<?php 
				$title='';
				$id='';

				$code = $category_code;
				$category_name = '';
				$priority = '';
				$work_flow_id = '';
				$department_id = '';
				$custom_form_id = '';
				$thank_you_page_id = '';
				$sla_id = '';

				$public='';
				$private='';
				$auto_response = '';


				if(isset($category)){
					$title =_l('cs_update_category');
					$id= $category->id;

					$code = $category->code;
					$category_name = $category->category_name;
					$priority = $category->priority;
					$work_flow_id = $category->work_flow_id;
					$department_id = $category->department_id;
					$custom_form_id = $category->custom_form_id;
					$thank_you_page_id = $category->thank_you_page_id;
					$sla_id = $category->sla_id;

					if($category->type == 'public'){
						$public = "checked";

					}elseif($category->type == 'private'){
						$private = "checked";
					}

					if($category->auto_response == 'enabled'){
						$auto_response = ' checked';
					}

				}else{
					$title =_l('cs_add_category');
					$public='checked';
					$private='';
				}

				?>
				<h4 class="modal-title"><?php echo new_html_entity_decode($title); ?></h4>
			</div>
			<?php echo form_open_multipart(admin_url('customer_service/add_edit_category/'.$id), array('id' => 'add_edit_category')); ?>
			<div class="modal-body">
				<div class="tab-content">
					<div class="row">

						<div class="row">
							<div class="col-md-12">
								<div class="col-md-6">
									<?php echo render_input('code','cs_category_code_label', $code,'text'); ?>  
								</div>
								<div class="col-md-6">
									<?php echo render_input('category_name','cs_category_name_label', $category_name,'text'); ?>  
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="profit_rate" class="control-label clearfix"><?php echo _l('cs_type'); ?></label>
										<div class="radio radio-primary radio-inline" >
											<input type="radio" id="public" name="type" value="public" <?php  echo  new_html_entity_decode($public); ?>>
											<label for="public"><?php echo _l('public_label'); ?></label>

										</div>
										<div class="radio radio-primary radio-inline" >
											<input type="radio" id="private" name="type" value="private" <?php echo new_html_entity_decode($private) ; ?>>
											<label for="private"><?php echo _l('private_label'); ?></label>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<?php echo render_select('priority', cs_priority(), array('id', 'name'),'cs_category_priority', $priority, [], [], '', '', false); ?>
								</div>
								
								<div class="col-md-6">
									<?php echo render_select('work_flow_id', $workflows, array('id', array('code', 'workflow_name')),'cs_work_flow', $work_flow_id, [], [], '', '', false); ?>
								</div>
								<div class="col-md-6">
									<?php echo render_select('department_id', $departments, array('departmentid', 'name'),'cs_department', $department_id, [], [], '', '', false); ?>
								</div>
								<div class="col-md-6 hide">
									<?php echo render_select('sla_id', $slas, array('id', array('code', 'name')),'cs_service_level_agreement', $sla_id, [], [], '', '', false); ?>
								</div>
								
								<div class="col-md-6 hide">
									<?php echo render_select('custom_form_id', [], array('staffid', array('firstname', 'lastname')),'cs_custom_form', $custom_form_id, [], [], '', '', false); ?>
								</div>
								<div class="col-md-6 hide">
									<?php echo render_select('thank_you_page_id', [], array('staffid', array('firstname', 'lastname')),'cs_thank_you_page', $thank_you_page_id, [], [], '', '', false); ?>
								</div>

								<div class="col-md-12 hide">
									<div class="form-group">
										<label><?php echo _l('cs_auto_response_label'); ?></label>
										<div class="checkbox checkbox-primary">
											<input type="checkbox" id="auto_response" name="auto_response" <?php echo  new_html_entity_decode($auto_response); ?> value="auto_response">
											<label for="auto_response"><?php echo _l('cs_auto_response'); ?>
											<a href="#" class="pull-right display-block input_method"><i class="fa fa-question-circle i_tooltip" data-toggle="tooltip" title="" data-original-title="<?php echo _l('cs_auto_response'); ?>"></i></a></label>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group" id="tags_value">
										<p><strong><?php echo _l('cs_tag_description'); ?></strong></p>
										<div id="inputTagsWrapper">
											<label for="tags" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i> <?php echo _l('tags'); ?></label>
											<input type="text" class="tagsinput" id="tags" name="tags" value="<?php echo (isset($category) ? prep_tags_input(get_tags_in($category->id,'cs_category_tag')) : ''); ?>" data-role="tagsinput">
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default close_btn" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
			</div>

		</div>

		<?php echo form_close(); ?>
	</div>
</div>
</div>

<?php require('modules/customer_service/assets/js/settings/categories/add_edit_category_js.php'); ?>
