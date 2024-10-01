<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="si_todo_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">
					<span class="edit-title hide"><?php echo _l('todo_edit_title'); ?></span>
					<span class="add-title hide"><?php echo _l('todo_add_title'); ?></span>
				</h4>
			</div>
			<?php echo form_open('admin/si_todo/todo',array('id'=>'si_add_new_todo_item')); ?>
			<div class="modal-body">
				<div class="row">
				<?php echo form_hidden('todoid',''); ?>
					<div class="col-md-12">
						<?php echo render_textarea('description','add_new_todo_description',''); ?>
					</div>
					<div class="col-md-12">
						<?php 
						$category_button = '<a href="#si_todo_category_modal" data-toggle="modal"><i class="fa fa-plus"></i></a>';
						//for Perfex v 3.0
						$perfex_version = $this->app->get_current_db_version();
						if($perfex_version  >= 300)
							$category_button = '<div class="input-group-btn"><a class="btn btn-default" href="#si_todo_category_modal" data-toggle="modal"><i class="fa fa-plus"></i></a></div>';
							
						echo render_select_with_input_group('category',$categories,array('id','category_name'),'si_todo_category','',$category_button);?>
					</div>
					<div class="col-md-12">
						<label><?php echo _l('priority');?></label><br/>
						<?php foreach(si_todo_get_priorities() as $priority){?>
						<div class="radio radio-inline1 radio-primary">
							<input type="radio" id="priority_<?php echo ($priority['id'])?>" name="priority" value="<?php echo ($priority['id'])?>" <?php if($priority['id'] == 1){echo 'checked';} ?>>
							<label for="priority_<?php echo ($priority['id'])?>" style="color:<?php echo ($priority['color'])?>"><?php echo ($priority['name']); ?></label>
						</div>
						<?php }?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
				<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>