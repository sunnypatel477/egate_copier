<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Disciplinary Action Category -->
<div>
	<div class="_buttons">
		<?php if(is_admin() || has_permission('hrm_setting','','create')){ ?>
			<a href="#" onclick="new_disciplinary_type(); return false;" class="btn btn-primary pull-left display-block">
				<?php echo _l('hr_disciplinary_add'); ?>
			</a>
		<?php } ?>
	</div>
	<div class="clearfix"></div>
	<br>
	<table class="table dt-table">
		<thead>
			<th width="30%"><?php echo _l('hr_disciplinary_name'); ?></th>
			<th><?php echo _l('hr_disciplinary_description'); ?></th>
			<th><?php echo _l('options'); ?></th>
		</thead>
		<tbody>
			<?php foreach($disciplinary_templates as $c){ ?>
				<tr>
					<?php 
					/*get frist 400 character */
					if(new_strlen($c['content']) > 400){
						$pos=strpos($c['content'], ' ', 400);
						$description_sub = substr($c['content'],0,$pos ); 
					}else{
						$description_sub = $c['content'];
					}
					?>

					<td><?php echo new_html_entity_decode($c['name']); ?></td>
					<td><?php echo new_html_entity_decode($description_sub); ?></td>
					<td>
						<?php if(is_admin() || has_permission('hrm_setting','','edit')){ ?>
							<a href="#" onclick="edit_disciplinary_type(this,<?php echo new_html_entity_decode($c['id']); ?>); return false"  class="btn btn-default btn-icon"><i class="fa-regular fa-pen-to-square"></i></a>
						<?php } ?>

						<?php if(is_admin() || has_permission('hrm_setting','','delete')){ ?>
							<a href="<?php echo admin_url('hr_profile/delete_disciplinary_type/'.$c['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
						<?php } ?>

					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>       
	<div class="modal" id="disciplinary_type" tabindex="-1" role="dialog">
		<div class="modal-dialog w-25">
			<?php echo form_open(admin_url('hr_profile/disciplinary_type'),  array('id'=>'add_disciplinary_type')); ?>
			<div class="modal-content ">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">
						<span class="edit-title"><?php echo _l('hr_edit_disciplinary_type'); ?></span>
						<span class="add-title"><?php echo _l('hr_new_disciplinary_type'); ?></span>
					</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div id="additional_disciplinary_type"></div>   
							<div class="form">
								<div class="col-md-12">
									<?php 
									echo render_input('name','name'); ?>
								</div>
								
								<div class="col-md-12">
									<p class="bold"><?php echo _l('hr_hr_description'); ?></p>
									<?php echo render_textarea('content','','',array(),array(),'','tinymce'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('hr_close'); ?></button>
					<button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
				</div>
			</div><!-- /.modal-content -->
			<?php echo form_close(); ?>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<!-- Penalty Point -->
<div>
	<div class="_buttons">
		<?php if(is_admin() || has_permission('hrm_setting','','create')){ ?>
			<a href="#" onclick="new_penalty_type(); return false;" class="btn btn-primary pull-left display-block">
				<?php echo _l('hr_penalty_add'); ?>
			</a>
		<?php } ?>
	</div>
	<div class="clearfix"></div>
	<br>
	<table class="table dt-table">
		<thead>
			<th width="30%"><?php echo _l('hr_penalty_name'); ?></th>
			<th><?php echo _l('hr_penalty_description'); ?></th>
			<th><?php echo _l('hr_penalty_point'); ?></th>
			<th><?php echo _l('options'); ?></th>
		</thead>
		<tbody>
			<?php foreach($penalty_templates as $c){ ?>
				<tr>
					<?php 
					if(new_strlen($c['content']) > 400){
						$pos=strpos($c['content'], ' ', 400);
						$description_sub = substr($c['content'],0,$pos ); 
					}else{
						$description_sub = $c['content'];
					}
					?>

					<td><?php echo new_html_entity_decode($c['name']); ?></td>
					<td><?php echo new_html_entity_decode($description_sub); ?></td>
					<td><?php echo new_html_entity_decode($c['point']); ?></td>
					<td>
						<?php if(is_admin() || has_permission('hrm_setting','','edit')){ ?>
							<a href="#" onclick="edit_penalty_type(this,<?php echo new_html_entity_decode($c['id']); ?>); return false"  class="btn btn-default btn-icon"><i class="fa-regular fa-pen-to-square"></i></a>
						<?php } ?>

						<?php if(is_admin() || has_permission('hrm_setting','','delete')){ ?>
							<a href="<?php echo admin_url('hr_profile/delete_penalty_type/'.$c['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
						<?php } ?>

					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>       
	<div class="modal" id="penalty_type" tabindex="-1" role="dialog">
		<div class="modal-dialog w-25">
			<?php echo form_open(admin_url('hr_profile/penalty_type'),  array('id'=>'add_penalty_type')); ?>
			<div class="modal-content ">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">
						<span class="edit-title"><?php echo _l('hr_edit_penalty_type'); ?></span>
						<span class="add-title"><?php echo _l('hr_new_penalty_type'); ?></span>
					</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div id="additional_penalty_type"></div>   
							<div class="form">
								<div class="col-md-12">
									<?php 
									echo render_input('name','name'); ?>
								</div>

								<div class="col-md-12">
									<?php 
									echo render_input('point','hr_penalty_point','','number'); ?>
								</div>
								
								<div class="col-md-12">
									<p class="bold"><?php echo _l('hr_hr_description'); ?></p>
									<?php echo render_textarea('content','','',array(),array(),'','tinymce'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('hr_close'); ?></button>
					<button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
				</div>
			</div><!-- /.modal-content -->
			<?php echo form_close(); ?>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>
</body>
</html>
