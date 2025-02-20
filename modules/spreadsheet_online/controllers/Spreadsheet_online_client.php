<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Team password client controller
 */
class Spreadsheet_online_client extends ClientsController
{
  /**
   * __construct
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->model('spreadsheet_online_model');
  }
    /**
     * index 
     * @param  int $page 
     * @param  int $id   
     * @param  string $key  
     * @return view       
     */
    public function index(){  
     if(is_client_logged_in()){
      $data['folder_my_share_tree'] = $this->spreadsheet_online_model->tree_my_folder_share_client();
      $data['title'] = _l('spreadsheet_online');
      $this->data($data);
      $this->view('client_share');
      $this->layout();
    }else{
      redirect(site_url('authentication'));
    }
  } 

      /**
     * get hash client
     * @param int $id 
     * @return json    
     */
      public function get_hash_client($id){
        $rel_id = get_client_user_id();
        $rel_type = 'client';
        echo json_encode($this->spreadsheet_online_model->get_hash($rel_type, $rel_id, $id));
      }

    /**
     * new file view 
     * @param  int $parent_id 
     * @param  int $id        
     * @return  view or json            
     */
    public function file_view_share($hash = ""){
      if(is_client_logged_in()){
        $data_form = $this->input->post();
        $data['tree_save'] = json_encode($this->spreadsheet_online_model->get_folder_tree());
        
        if($hash != ""){
          $share_child = $this->spreadsheet_online_model->get_share_form_hash($hash);
          $id = $share_child->id_share;
          $file_excel = $this->spreadsheet_online_model->get_file_sheet($id);
          $data['parent_id'] = $file_excel->parent_id;
          $data['role'] = $share_child->role;
          if (($share_child->rel_id != get_client_user_id())) {
            access_denied('spreadsheet_online');
          }
        }else{
          $id = "";
          $data['parent_id'] = "";
          $data['role'] = 1;
        }

        $data_form = $this->input->post();
        $data['title'] = _l('new_file');
        $data['folder'] = $this->spreadsheet_online_model->get_my_folder_all();
        if($data_form || isset($data_form['id'])){
          if($data_form['id'] == ""){
            $success = $this->spreadsheet_online_model->add_file_sheet($data_form);
            if(is_numeric($success)){
              $message = _l('added_successfully');
              $file_excel = $this->spreadsheet_online_model->get_file_sheet($success);
              echo json_encode(['success' => true, 'message' => $message, 'name_excel' => $file_excel->name ]);
            }
            else{
              $message = _l('added_fail');
              echo json_encode(['success' => false, 'message' => $message]);
            }
          }
        }
        if($id != "" || isset($data_form['id'])){
          if(isset($data_form['id'])){
            if($data_form['id'] != ""){
              $data['id'] = $data_form['id'];
            }
          }else{
            $data['id'] = $id;
            // process hanlde file                                 
            $data_file = process_file($id);
            if(isset($data_file['data_form'])){
              $data['data_form'] = $data_file['data_form'];
              $data['name'] = $data_file['name'];
            }
          }

          if($data_form && $data_form['id'] != ""){
            $success = $this->spreadsheet_online_model->edit_file_sheet($data_form);
            if($success == true){
              $message = _l('updated_successfully');
              echo json_encode(['success' => $success, 'message' => $message]);
            }
            else{
              $message = _l('updated_fail');
              echo json_encode(['success' => $success, 'message' => $message]);
            }
          }
        }
        if(!isset($success)){
          $this->data($data);
          $this->view('share_file_view_client');
          $this->layout();
        }
      }else{
        redirect(site_url('authentication'));
      }
      
    }
    public function send_email_notification($id){
      $this->send_mail($id);
      $message = _l('email send successfully');
      echo json_encode(['success' => true, 'message' => $message]);
    }
  
    public function send_mail($id,$success = true){
      $data = $this->spreadsheet_online_model->get_my_folder($id);
      
      $data = (array) $data;
      if(isset($data['staffs_share'])){
        $data['staffs_share'] = explode(",",$data['staffs_share']);
      }
      if(isset($data['clients_share'])){
        $data['clients_share'] = explode(",",$data['clients_share']);
      }
      $staff_notification = get_option('spreadsheet_staff_notification');
        $staff_sent_email = get_option('spreadsheet_email_templates_staff');
        $client_notification = get_option('spreadsheet_client_notification');
        $client_sent_email = get_option('spreadsheet_email_templates_client');
  
        if($success == true){
          
          if(is_array($data['staffs_share']) && count($data['staffs_share']) > 0){
            if($data['staffs_share'][0] != ''){
              foreach ($data['staffs_share'] as $key => $value) {
                $this->db->where('id', $data['id']);
                $share = $this->db->get(db_prefix() . 'spreadsheet_online_my_folder')->row();
  
                $share->receiver = spreadsheet_email_staff($value);
                $share->staff_share_id = $value;
  
                $share->type_template = "staff_template";
  
                if($staff_sent_email == 1){
                  $template = mail_template('spreadsheet_update_notification', 'spreadsheet_online', array_to_object($share));
                  $template->send();
                }
  
                if($staff_notification == 1){
                  $link = '';
                  $link = 'spreadsheet_online/new_file_view/'.$data['parent_id'].'/'.$data['id'];
                  $string_sub = get_staff_full_name($value) . ' ' . _l('update') . ' ' . $share->type . ' ' . $share->name . ' ' . _l('for_you');
                  $this->spreadsheet_online_model->notifications($value, $link, strtolower($string_sub));
                }
  
              }
            }
          }
  
          if(is_array($data['clients_share']) &&  count($data['clients_share']) > 0){
            if($data['clients_share'][0] != ''){
              foreach ($data['clients_share'] as $key => $value) {
                $this->db->where('id', $data['id']);
                $share = $this->db->get(db_prefix() . 'spreadsheet_online_my_folder')->row();
  
                $this->db->where('userid', $value);
                $contact = $this->db->get(db_prefix() . 'contacts')->row()->email;
  
                if($contact != null || $contact != ''){
                  $share->receiver = $contact;
                  $share->client_share_id = $value;
                  $share->type_template = "client_template";
                  if($client_sent_email == 1){
                    $template = mail_template('spreadsheet_update_notification_client', 'spreadsheet_online', array_to_object($share));
                    $template->send();
                  }
  
                  if($client_notification == 1){
                    $link_client = '';
                    $link_client = 'spreadsheet_online/new_file_view/'.$data['parent_id'].'/'.$data['id'];
                    $string_sub = get_staff_full_name($value) . ' ' . _l('update') . ' ' . $share->type . ' ' . $share->name . ' ' . _l('for_you');
                    $this->spreadsheet_online_model->notifications($value, $link_client, strtolower($string_sub));
                  }
  
                }
              }
            }
          }
  
  
  
  
        }
    }
    /**
     * Add edit folder
    */
    public function add_edit_folder_client(){
      if($this->input->post()){
        $data = $this->input->post();    
        if($data['id'] == ''){
          $id = $this->spreadsheet_online_model->add_folder($data);
          if(is_numeric($id)){
            $message = _l('added_successfully');
            set_alert('success', $message);
          }
          else{
            $message = _l('added_fail');
            set_alert('warning', $message);
          }
        }
        else{
          $res = $this->spreadsheet_online_model->edit_folder($data);
          if($res == true){
            $message = _l('updated_successfully');
            set_alert('success', $message);
          }
          else{
            $message = _l('updated_fail');
            set_alert('warning', $message);
          }
        }
        redirect(site_url('spreadsheet_online/spreadsheet_online_client'));
      }    
    }
    
    /**
     * new file view 
     * @param  int $parent_id 
     * @param  int $id        
     * @return  view or json            
     */
    public function file_view_share_related($hash = ""){
      $data_form = $this->input->post();
      $data['tree_save'] = json_encode($this->spreadsheet_online_model->get_folder_tree());
      
      if($hash != ""){
        $share_child = $this->spreadsheet_online_model->get_share_form_hash_related($hash);
        $id = $share_child->parent_id;
        $file_excel = $this->spreadsheet_online_model->get_file_sheet($id);
        $data['parent_id'] = $file_excel->parent_id;
        $data['role'] = $share_child->role;
      }else{
        $id = "";
        $data['parent_id'] = "";
        $data['role'] = 1;
      }

      $data_form = $this->input->post();
      $data['title'] = _l('new_file');
      $data['folder'] = $this->spreadsheet_online_model->get_my_folder_all();
      if($data_form || isset($data_form['id'])){
        if($data_form['id'] == ""){
          $success = $this->spreadsheet_online_model->add_file_sheet($data_form);
          if(is_numeric($success)){
            $message = _l('added_successfully');
            $file_excel = $this->spreadsheet_online_model->get_file_sheet($success);
            echo json_encode(['success' => true, 'message' => $message, 'name_excel' => $file_excel->name ]);
          }
          else{
            $message = _l('added_fail');
            echo json_encode(['success' => false, 'message' => $message]);
          }
        }
      }
      if($id != "" || isset($data_form['id'])){
        if(isset($data_form['id'])){
          if($data_form['id'] != ""){
            $data['id'] = $data_form['id'];
          }
        }else{  
          $data['id'] = $id;
          // process hanlde file                                 
          $data_file = process_file($id);
          if(isset($data_file['data_form'])){
            $data['data_form'] = $data_file['data_form'];
            $data['name'] = $data_file['name'];
          }
        }

        if($data_form && $data_form['id'] != ""){
          $success = $this->spreadsheet_online_model->edit_file_sheet($data_form);
          if($success == true){
            $message = _l('updated_successfully');
            echo json_encode(['success' => $success, 'message' => $message]);
          }
          else{
            $message = _l('updated_fail');
            echo json_encode(['success' => $success, 'message' => $message]);
          }
        }
      }
      if(!isset($success)){
        $this->data($data);
        $this->view('share_file_view_client');
        $this->layout();
      }
    }
    /**
     * get file sheet 
     * @param  int $id 
     * @return  json    
     */
    public function get_file_sheet($id){
      $data = $this->spreadsheet_online_model->get_file_sheet($id);
      $path = SPREAD_ONLINE_MODULE_UPLOAD_FOLDER.$data->realpath_data;
      $main_dl = file_get_contents($path, true);
      $data_form = replace_spreadsheet_value($main_dl);        
      echo json_encode($data_form);  
    }
    /**
     * [check_file_exits description]
     * @param  [type] $id_set [description]
     * @return [type]         [description]
     */
    public function check_file_exits($id_set){
      if(is_numeric($id_set)){
        $data = $this->spreadsheet_online_model->get_my_folder($id_set);
        if($data->realpath_data != '' && $data->realpath_data != NULL){
          if(!file_exists(SPREAD_ONLINE_MODULE_UPLOAD_FOLDER.$data->realpath_data)){
            echo json_encode(['message' => _l('physical_file_have_been_deleted'), 'success' => false ]);
          }else{
            echo json_encode(['message' => "success", 'success' => true ]);
          }
        }else{
          if($data->realpath_data == '' && $data->data_form != NULL && $data->data_form != ''){
            $path = SPREAD_ONLINE_MODULE_UPLOAD_FOLDER . '/spreadsheet_online/' . $id_set . '-'.$data->name.'.txt';
            $realpath_data = '/spreadsheet_online/' . $id_set . '-'.$data->name.'.txt';
            file_force_contents($path, $data->data_form);
            $this->db->where('id', $id_set);
            $this->db->update(db_prefix() . 'spreadsheet_online_my_folder', ['realpath_data' => $realpath_data]);
            echo json_encode(['message' => "success", 'success' => true ]);
          }else{
            echo json_encode(['message' => _l('physical_file_have_been_deleted'), 'success' => false ]);
          }
        }
      }else{
        echo json_encode(['message' => _l('physical_file_have_been_deleted'), 'success' => false ]);
      }
    }
  }