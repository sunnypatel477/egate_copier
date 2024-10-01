<div class="panel-group no-margin">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title" data-toggle="collapse" data-target="#collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" aria-expanded="true">
				<span class="text-info glyphicon glyphicon-retweet"> </span><span class="text-info"> <?php echo _l('cs_stage'); ?></span>
			</h4>
		</div>
		<div id="collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" class="panel-collapse collapse in" aria-expanded="true">
			<div class="box" node-id="<?php echo new_html_entity_decode($nodeId); ?>">
				
				<?php echo render_input('stage_name['.$nodeId.']', 'cs_stage_name', '', 'text', ['df-stage_name' => '']); ?>
				<?php echo render_textarea('stage_description['.$nodeId.']', 'cs_stage_description','', ['df-stage_description' => '']); ?>
			</div>
		</div>
	</div>
</div>
