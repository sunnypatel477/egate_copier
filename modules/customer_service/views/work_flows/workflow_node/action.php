<div class="panel-group no-margin">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title" data-toggle="collapse" data-target="#collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" aria-expanded="true">
				<span class="text-info glyphicon glyphicon-retweet"> </span><span class="text-info"> <?php echo _l('cs_action'); ?></span>
			</h4>
		</div>
		<div id="collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" class="panel-collapse collapse in" aria-expanded="true">
			<div class="box" node-id="<?php echo new_html_entity_decode($nodeId); ?>">
				<?php $actions = [
					['id' => 'send_email_and_notify_to_department_and_staff','name' => _l('cs_send_email_and_notify_to_department_and_staff')],
					['id' => 'send_email_to_customer','name' => _l('cs_send_email_to_customer')],
					['id' => 'send_reminder_to_customer','name' => _l('cs_send_reminder_to_customer')],
				]; ?>
				<?php echo render_select('action['.$nodeId.']',$actions, array('id', 'name'),'action', '', ['df-action' => ''], [], '', '', true); ?>

				<div class="div_action_change_email">
					<?php echo render_select('email['.$nodeId.']',$segments, array('id', 'name'),'cs_email', '', ['df-email' => '']); ?>
				</div>

				<div class="div_action_change_notify">
					<?php echo render_select('notify['.$nodeId.']',$segments, array('id', 'name'),'cs_notify', '', ['df-notify' => '']); ?>
				</div>
				
			</div>
		</div>
	</div>
</div>
