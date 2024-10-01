<?php 

   $id = '';
  $name = '';
  $version = '';
  $url = '';
  $license_key = '';
  $notice = '';
  $host = '';
  $port = '';
  $password = '';
  $enable_log = '';
  $mgt_id = '';
  $custom_field = [];
  $datecreator = '';
  if(isset($software_license)){
      $id = $software_license->id;
      $name = $software_license->name;
      $version = $software_license->version;
      $url = $software_license->url;
      $license_key = $software_license->license_key;
      $notice = $software_license->notice;
      $host = $software_license->host;
      $port = $software_license->port;
      $password =  AES_256_Decrypt($software_license->password);
      $enable_log = $software_license->enable_log;
      $mgt_id = $software_license->mgt_id;
      $custom_field = json_decode($software_license->custom_field);
      $datecreator = $software_license->datecreator;
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
       <td class="bold"><?php echo _l('category'); ?></td>
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
        <td class="bold"><?php echo _l('version'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($version); 

           ?>      
        </td>
     </tr>  
          <tr class="project-overview">
        <td class="bold"><?php echo _l('license_key'); ?>

        </td>
        <td>                                        
          <?php
            
           echo html_entity_decode($license_key); 

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
        <td class="bold"><?php echo _l('related').' '._l($software_license->relate_to); ?>

        </td>
        <td>                                        
          <?php
            $contracts = get_contract_relate('software_license',$software_license->id);

            if($contracts){
              if($software_license->relate_to == 'contract'){
                foreach($contracts as $ctr){
                  $contract = get_contract_id($ctr);
                  if($contract){
                    echo '<a href="'.site_url('contract/'.$contract->id.'/'.$contract->hash).'" ><span class="label label-tag"> '.html_entity_decode( get_company_name($contract->client).' - '.$contract->subject).'</span></a>'; 
                  }else{
                    echo '';
                  }
                }
              }elseif($software_license->relate_to == 'project'){
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
            
           echo _l($enable_log); 

           ?>      
        </td>
     </tr>      
    </tbody>
  </table>

   <div class="col-md-12" id="ic_pv_file">
  <?php
   if(isset($software_license)){
      $attachments = get_item_tp_attachment($software_license->id,'tp_software_license');
      $file_html = '';
      $type_item = 'tp_software_license';
      if(count($attachments) > 0){
          $file_html .= '<hr />
                  <p class="bold text-muted">'._l('attachments').'</p>';
          foreach ($attachments as $f) {
              $href_url = site_url(TEAM_PASSWORD_PATH.'tp_software_license/'.$f['rel_id'].'/'.$f['file_name']).'" download';
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
        <?php echo html_entity_decode($name); ?><br>
      </div>
    </div>
    <?php } ?>
  </div>
<a href="<?php echo admin_url('team_password/clear_logs/'.$id.'/software_license'); ?>" class="btn btn-danger pull-right mtop5 mbot5"><?php echo _l('clear_log'); ?></a>
</div>
<?php } ?>
