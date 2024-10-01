<div class="panel-group no-margin">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title" data-toggle="collapse" data-target="#collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" aria-expanded="true">
        <span class="text-warning glyphicon glyphicon-random"> </span><span class="text-warning"> <?php echo _l('filter'); ?></span>
      </h4>
    </div>
    <div id="collapse_node_<?php echo new_html_entity_decode($nodeId); ?>" class="panel-collapse collapse in" aria-expanded="true">
      <div class="box" node-id="<?php echo new_html_entity_decode($nodeId); ?>">
        <div class="form-group">
          <label for="complete_action"><?php echo _l('complete_change_stage_ticket_status'); ?>:</label><br />
          
          <?php $tracks = [
            ['id' => 'stage_status','name' => _l('cs_stage_status')],
            ['id' => 'ticket_status','name' => _l('cs_ticket_status')],
          ]; ?>
          <?php echo render_select('status_type['. $nodeId .']',$tracks, array('id', 'name'),'cs_status_type', '', ['df-status_type' => ''], [], '', '', false); ?>
          
          <?php echo render_select('value_of_status['. $nodeId .']', cs_stage_status(), array('id', 'name'),'cs_value_of_status', '', ['df-value_of_status' => ''], [], '', '', false); ?>

        </div>
      </div>
    </div>
  </div>
</div>