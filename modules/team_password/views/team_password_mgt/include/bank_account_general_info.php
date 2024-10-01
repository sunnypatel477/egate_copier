<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php 
$id = '';
  $name = '';
  $url = '';
  $user_name = '';
  $pin = '';
  $bank_name = '';
  $bank_code = '';
  $account_holder = '';
  $account_number = '';
  $iban = '';
  $notice = '';
  $password = '';
  $enable_log = '';
  $custom_field = [];
  $datecreator = '';
  $mgt_id = '';

  if(isset($bank_account)){
  $id = $bank_account->id;
  $name = $bank_account->name;
  $url = $bank_account->url;
  $user_name = $bank_account->user_name;
  $pin = AES_256_Decrypt($bank_account->pin);
  $bank_name = $bank_account->bank_name;
  $bank_code = $bank_account->bank_code;
  $account_holder = $bank_account->account_holder;
  $account_number = $bank_account->account_number;
  $iban = $bank_account->iban;
  $notice = $bank_account->notice;
  $password =  AES_256_Decrypt($bank_account->password);
  $enable_log = $bank_account->enable_log;
  $custom_field =json_decode($bank_account->custom_field);
  $mgt_id = $bank_account->mgt_id;
  }
 ?>
<div class="col-md-8">
<table class="table border table-striped ">
 <tbody>                                  
     <tr class="project-overview">
        <td class="bold"><?php echo _l('name'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($name); 

           ?>      
        </td>
     </tr> 

     <tr class="project-overview">
        <td class="bold"><?php echo _l('category'); ?>
        </td>
        <td>                                        
          <?php
            
             $category_name = '';
            if($mgt_id){
              $data_category = $this->team_password_model->get_category_management($mgt_id); 
              if($data_category){
                   $category_name = '<i class="fa '.$data_category->icon.'"></i> '.$data_category->category_name;
              }      
            }
            echo html_entity_decode($category_name); 

           ?>      
        </td>
     </tr> 

     <tr class="project-overview">
        <td class="bold"><?php echo _l('url'); ?>

        </td>
        <td>                                        
          <?php            
           		echo html_entity_decode($url); 
           ?>      
        </td>
     </tr>  

     <tr class="project-overview">
        <td class="bold"><?php echo _l('user_name'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($user_name); 

           ?>      
        </td>
     </tr>  
     <tr class="project-overview">
        <td class="bold"><?php echo _l('pin'); ?>
        </td>
        <td>                                        
         <div class="col-md-4 row mright10"><input type="text" class="form-control" id="bank_pin" value="<?php echo set_value('bank_pin',$pin); ?>" /></div>
          <button onclick="copyToClipboard('bank_pin');" class="btn btn-success btn-icon pull-left mtop5" data-toggle="tooltip" data-placement="top" title="<?php echo _l('copy'); ?>" ><i class="fa fa-copy"></i></button>
         
        </td>
     </tr>  
     <tr class="project-overview">
        <td class="bold"><?php echo _l('bank_name'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($bank_name);

           ?>      
        </td>
     </tr>  
     <tr class="project-overview">
        <td class="bold"><?php echo _l('bank_code'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($bank_code);

           ?>      
        </td>
     </tr>  
     <tr class="project-overview">
        <td class="bold"><?php echo _l('account_holder'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($account_holder);

           ?>      
        </td>
     </tr>
     <tr class="project-overview">
        <td class="bold"><?php echo _l('account_number'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($account_number);

           ?>      
        </td>
     </tr> 
     <tr class="project-overview">
        <td class="bold">IBAN

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($iban);

           ?>      
        </td>
     </tr>
     <tr class="project-overview">
        <td class="bold"><?php echo _l('notice'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($notice); 

           ?>      
        </td>
     </tr>  
     <tr class="project-overview">
        <td class="bold"><?php echo _l('related').' '._l($bank_account->relate_to); ?>

        </td>
        <td>                                        
          <?php
            $contracts = get_contract_relate('bank_account',$bank_account->id);

            if($contracts){
              if($bank_account->relate_to == 'contract'){
                foreach($contracts as $ctr){
                  $contract = get_contract_id($ctr);
                  if($contract){
                    echo '<a href="'.site_url('contract/'.$contract->id.'/'.$contract->hash).'" ><span class="label label-tag"> '.html_entity_decode( get_company_name($contract->client).' - '.$contract->subject).'</span></a>'; 
                  }else{
                    echo '';
                  }
                }
              }elseif($bank_account->relate_to == 'project'){
                foreach($contracts as $pj){
                  $project = get_project_id($pj);
                  if($project){
                    echo '<a href="'.admin_url('projects/view/'.$project->id).'" ><span class="label label-tag"> '.html_entity_decode( get_company_name($project->id).' - '.$project->name).'</span></a>'; 
                  }else{
                    echo '';
                  }
                }
              }else{
                echo '';
              }
            }else{
              echo '';
            }

           ?>      
        </td>
     </tr> 
     <?php if(count($custom_field)>0){ ?>
     <tr class="project-overview">
        <td class="bold"><?php echo _l('custom_field'); ?>

        </td>
        <td>                                        
           <?php foreach ($custom_field as $key => $tag) { ?>
             &nbsp;<span class="btn btn-default ptop-10 tag">
             <label  name="field_name[<?php echo html_entity_decode($key); ?>]"><?php echo html_entity_decode($tag->name); ?></label>&nbsp; - &nbsp;<label  name="field_value[<?php echo html_entity_decode($key); ?>]"><?php echo html_entity_decode($tag->value); ?></label>&nbsp;
             </span>&nbsp;
            <?php } ?>     
        </td>
     </tr>   
     <?php } ?>   
    <tr class="project-overview">
        <td class="bold"><?php echo _l('enable_log'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($enable_log); 

           ?>      
        </td>
     </tr>   
    </tbody>
  </table>


      <div class="col-md-12" id="ic_pv_file">
  <?php
   if(isset($bank_account)){
      $attachments = get_item_tp_attachment($bank_account->id,'tp_bank');
      $file_html = '';
      $type_item = 'tp_bank';
      if(count($attachments) > 0){
          $file_html .= '<hr />
                  <p class="bold text-muted">'._l('attachments').'</p>';
          foreach ($attachments as $f) {
              $href_url = site_url(TEAM_PASSWORD_PATH.'tp_bank/'.$f['rel_id'].'/'.$f['file_name']).'" download';
                              if(!empty($f['external'])){
                                $href_url = $f['external_link'];
                              }
             $file_html .= '<div class="mbot15 row inline-block full-width" data-attachment-id="'. $f['id'].'">
            <div class="col-md-8">
               <a name="preview-ic-btn" onclick="preview_ic_btn(this); return false;" rel_id = "'. $f['rel_id']. '" type_item = "'. $type_item. '" id = "'.$f['id'].'" href="Javascript:void(0);" class="mbot10 btn btn-success pull-left mright5" data-toggle="tooltip" title data-original-title="'. _l('preview_file').'"><i class="fa fa-eye"></i></a>
               <div class="pull-left"><i class="'. get_mime_class($f['filetype']).'"></i></div>
               <a href=" '. $href_url.'" target="_blank" download>'.$f['file_name'].'</a>
               <br />
               <small class="text-muted">'.$f['filetype'].'</small>
            </div>
            <div class="col-md-4 text-right">';
              if($f['staffid'] == get_staff_user_id() || is_admin()){
              $file_html .= '<a href="#" class="text-danger" onclick="delete_ic_attachment('. $f['id'].',this); return false;" type_item = "'. $type_item. '"><i class="fa fa-times"></i></a>';
              } 
             $file_html .= '</div></div>';
          }
          $file_html .= '<hr />';
          echo html_entity_decode($file_html);
      }
   ?>
</div>
<?php } ?>
<div id="ic_file_data"></div>

</div>
<?php if(has_permission('team_password','','view') || is_admin()){ ?>
<div class="col-md-4">
  <h4 class="no-margin font-bold" ><?php echo _l('activity_log'); ?></h4>
  <hr/>
  <div class="activity-feed">

    <?php foreach($logs as $b){ ?>
    <div class="feed-item">
      <div class="date"><span class="text-has-action" data-toggle="tooltip" data-title="<?php echo _dt($b['time']); ?>">
        <?php echo time_ago($b['time']); ?>
      </span>
    </div>
      <div class="text">
       <p class="bold no-mbot">
        <a href="<?php echo admin_url('profile/'.$b['staff']); ?>"><?php echo get_staff_full_name($b['staff']); ?></a> -
        <?php echo _l($b['type']); ?></p>
        <?php echo html_entity_decode($bank_account->name); ?><br>
      </div>
    </div>
    <?php } ?>
  </div>
  <a href="<?php echo admin_url('team_password/clear_logs/'.$id.'/bank_account'); ?>" class="btn btn-danger pull-right mtop5 mbot5"><?php echo _l('clear_log'); ?></a>
</div>
<?php } ?>
<br>