<div class="panel-group no-margin">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title" data-toggle="collapse" data-target="#collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" aria-expanded="true">
				<span class="text-danger glyphicon glyphicon-refresh"> </span><span class="text-danger"> <?php echo _l('cs_assignee'); ?></span>
			</h4>
		</div>
		<div id="collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" class="panel-collapse collapse in" aria-expanded="true">
			<div class="box" node-id="<?php echo new_html_entity_decode($nodeId); ?>">
				<label><?php echo _l('cs_you_can_change_the_group_department_and_assignee_for_a_ticket'); ?></label>
				<?php echo render_select('assignee_department_id['. $nodeId .']', $departments, array('departmentid', 'name'), 'ticket_assigness_department', '', ['df-assignee_department_id' => ''], [], '', '', true); ?>

				<!-- TODO -->
				<?php echo render_select('assignee_id['. $nodeId .']', $staffs, array('staffid', array('firstname', 'lastname')), 'cs_assignee', '', ['df-assignee_id' => ''], [], '', '', true); ?>
			</div>
		</div>
	</div>
</div>