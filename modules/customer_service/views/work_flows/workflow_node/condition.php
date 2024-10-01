<div class="panel-group no-margin">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title" data-toggle="collapse" data-target="#collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" aria-expanded="true">
				<span class="text-danger glyphicon glyphicon-fullscreen"> </span><span class="text-danger"> <?php echo _l('condition'); ?></span>
			</h4>
		</div>
		<div id="collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" class="panel-collapse collapse in" aria-expanded="true">
			<div class="box" node-id="<?php echo new_html_entity_decode($nodeId); ?>">
				<label><?php echo _l('cs_check_for_a_condition_in_your_workflow_on_ticket_or_stage'); ?></label>
				<?php $tracks = [
					['id' => 'ticket_id','name' => _l('cs_ticket_id')],
					['id' => 'requester_name','name' => _l('cs_requester_name')],
					['id' => 'requester_email','name' => _l('cs_requester_email')],
					['id' => 'ticket_status','name' => _l('cs_ticket_status')],
					['id' => 'ticket_priority','name' => _l('cs_ticket_priority')],
					['id' => 'ticket_type','name' => _l('cs_ticket_type')],
					['id' => 'ticket_subject','name' => _l('cs_ticket_subject')],
					['id' => 'stage_status','name' => _l('cs_stage_status')],
				]; ?>
				
				<?php echo render_select('name_of_variable['. $nodeId .']',$tracks, array('id', 'name'),'Name of variable', '', ['df-name_of_variable' => ''], [], '', '', true); ?>
				<?php $conditions = [ 
					1 => ['id' => 'equals', 'name' => _l('cs_equals')],
					2 => ['id' => 'not_equal', 'name' => _l('cs_not_equal')],
					3 => ['id' => 'greater_than', 'name' => _l('cs_greater_than')],
					4 => ['id' => 'greater_than_or_equal', 'name' => _l('cs_greater_than_or_equal')],
					5 => ['id' => 'less_than', 'name' => _l('cs_less_than')],
					6 => ['id' => 'less_than_or_equal', 'name' => _l('cs_less_than_or_equal')],
					7 => ['id' => 'empty', 'name' => _l('cs_empty')],
					8 => ['id' => 'not_empty', 'name' => _l('cs_not_empty')],
					9 => ['id' => 'like', 'name' => _l('cs_like')],
					10 => ['id' => 'not_like', 'name' => _l('cs_not_like')],
				]; ?>
				<?php echo render_select('condition['. $nodeId .']',$conditions, array('id', 'name'),'condition', '', ['df-condition' => ''], [], '', '', true); ?>

				<?php echo render_input('value_of_variable['. $nodeId .']','Value of variable', '', 'text', ['df-value_of_variable' => '']); ?>

			</div>
		</div>
	</div>
</div>