<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
   <div class="col-md-12">
     <div class="row">
        <div class="col-md-4">
        <?php if(has_permission('team_password','','create') || is_admin()){ ?>  
          <a href="<?php echo admin_url('team_password/add_credit_card?cate='.$cate); ?>" class="btn btn-info pull-left">
              <?php echo _l('add'); ?>
          </a>
        <?php } ?>
     </div>
    <div class="col-md-12">
    <hr class="hr-panel-heading" />
    <div class="clearfix"></div>
    <table class="table table-credit_card scroll-responsive">
          <thead>
            <th><?php echo _l('id'); ?></th>
          <th><?php echo _l('name'); ?></th>
          <th><?php echo _l('category'); ?></th>
          <th><?php echo _l('credit_card_type'); ?></th>
          <th><?php echo _l('valid_from'); ?></th>
          <th><?php echo _l('valid_to'); ?></th>
          <th><?php echo _l('notice'); ?></th>
          <th><?php echo _l('options'); ?></th>
          </thead>
          <tbody></tbody>
          <tfoot>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>      
             <td></td>      
          </tfoot>
       </table>
    </div>
     </div>
   </div>
    
