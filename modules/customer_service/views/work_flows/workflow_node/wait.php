<div class="panel-group no-margin">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title" data-toggle="collapse" data-target="#collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" aria-expanded="true">
				<span class="text-danger glyphicon glyphicon-time"> </span><span class="text-danger"> <?php echo _l('cs_wait'); ?></span>
			</h4>
		</div>
		<div id="collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" class="panel-collapse collapse in" aria-expanded="true">
			<div class="box" node-id="<?php echo new_html_entity_decode($nodeId); ?>">
				<label><?php echo _l('cs_wait_for_a_specified_duration_before_next_action'); ?></label>
				
				<?php echo render_input('wait_duration['. $nodeId .']','Value of variable', '', 'text', ['df-wait_duration' => '']); ?>

				<?php echo render_select('wait_type['. $nodeId .']', cs_waits(), array('id', 'name'), 'cs_wait', '', ['df-wait_type' => ''], [], '', '', true); ?>
			</div>
		</div>
	</div>
</div>