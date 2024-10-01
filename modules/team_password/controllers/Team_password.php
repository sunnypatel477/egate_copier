<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Team password
 */
class Team_password extends AdminController
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('team_password_model');
    require_once module_dir_path(TEAM_PASSWORD_MODULE_NAME) . '/third_party/excel/PHPExcel.php';
  }

    /**
     * category management 
      * @return view
     */

    public function category_management()
    {
      if(!has_permission('team_password','','view_own') && !has_permission('team_password','','view') && !is_admin()){
        access_denied('team_password');
      }

      $data['title'] = _l('category_managements');
      if ($this->input->post()) {
        $message          = '';
        $data             = $this->input->post();
        if ($data['id'] == '') {
          if(!has_permission('team_password','','create') && !is_admin()){
            access_denied('team_password');
          }
          $id = $this->team_password_model->add_category_management($data);
          if ($id) {
            $success = true;
            $message = _l('added_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/category_management'));
        } else {
          if(!has_permission('team_password','','edit') && !is_admin()){
            access_denied('team_password');
          }

          $success = $this->team_password_model->update_category_management($data);
          if ($success) {
            $message = _l('updated_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/category_management'));
        }
        die;
      }

      $data['cates'] = $this->team_password_model->get_category_management();

      $this->load->view('category_management', $data);
    }
    /**
     * category management table
     * @return json
     */
    public function category_management_table()
    {
     if ($this->input->is_ajax_request()) {
      if($this->input->post()){
        $staff_filter = $this->input->post('bed_category_filter'); 
        $query = '';
        if($staff_filter!=''){
          $query = ' where bed_category_id in ('.implode(',', $staff_filter).')';
        } 
        $select = [
          'id',
          'category_name',
          'icon',
          'parent',
          'description',
          'id'          
        ];
        $where              = [(($query!='')?$query:'')];


        $aColumns     = $select;
        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'team_password_category';
        $join         = [];

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
          'id',
          'category_name',
          'icon',
          'parent',
          'color',
          'description', 
          'parent',  
        ]);


        $output  = $result['output'];
        $rResult = $result['rResult'];
        foreach ($rResult as $aRow) {
          $row = [];
          $row[] = $aRow['id'];             
          $row[] = '<a href="' . admin_url('team_password/team_password_mgt?cate=' . $aRow['id'].'&type=normal') . '">'.$aRow['category_name'].'</a>';             
          $row[] = '<i class="fa '.$aRow['icon'].'"></i>';
          $row[] = get_category_name_tp($aRow['parent']);             
          $row[] = $aRow['description'];

          $option = '';
          if(has_permission('team_password','','edit') || is_admin()){
            $option .= '<a href="#" class="btn btn-default btn-icon" onclick="update(this); return false;" data-id="'.$aRow['id'].'" data-category_name="'.$aRow['category_name'].'" data-icon="'.$aRow['icon'].'" data-description="'.$aRow['description'].'" data-parent="'.$aRow['parent'].'" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
            $option .= '<i class="fa fa-pencil-square-o"></i>';
            $option .= '</a>';
          }

          if(has_permission('team_password','','delete') || is_admin()){
            $option .= '<a href="' . admin_url('team_password/delete_category_management/' . $aRow['id']) . '" class="btn btn-danger btn-icon _delete"  data-toggle="tooltip" data-placement="top" title="'._l('delete').'">';
            $option .= '<i class="fa fa-remove"></i>';
            $option .= '</a>';
          }

          $row[] = $option; 
          $output['aaData'][] = $row;                                      
        }

        echo json_encode($output);
        die();
      }
    }
  }
    /**
     * delete category management
     * @param  id
     * @return redirect
     */
    public function delete_category_management($id='')
    {
      if(!has_permission('team_password','','delete') && !is_admin()){
        access_denied('team_password');
      }

      $response = $this->team_password_model->delete_category_management($id);
      if($response == true){
        set_alert('success', _l('deleted', _l('category')));
      }
      else{
        set_alert('warning', _l('problem_deleting'));            
      }
      redirect(admin_url('team_password/category_management'));
    }
    /**
     * team password management
     * @param  id
     * @return view
     */
    
    public function team_password_mgt($id='')
    {  
      if(!has_permission('team_password','','view_own') && !has_permission('team_password','','view') && !is_admin()){
        access_denied('team_password');
      }

      $this->load->model('staff_model'); 
      $data['type'] = $this->input->get('type');
      $data['tab'] = $this->input->get('tab');
      $data['cate'] = $this->input->get('cate');
      $category_name = '';
      $icon = 'fa-list-ul';
      if(!$data['type']){
        $data['type'] = 'normal';
      }

      if(!$data['cate']){
        $data['cate'] = 'all';
      }

      $this->load->model('client_groups_model');
      $data['customer_groups'] = $this->client_groups_model->get_groups();
      $data['mgt_id'] ='';  
      $data['title'] = _l($data['type']);
      $data['category'] = $this->team_password_model->get_category_management();
      $data['contact'] = $this->team_password_model->get_contact();
      $data['staffs'] = $this->staff_model->get();
      if(has_permission('team_password','','view') || is_admin()){ 
        $data['tree_cate'] = json_encode($this->team_password_model->get_tree_data_cate($data['type'],$data['cate']) );
      }else{
        $data['tree_cate'] = json_encode($this->team_password_model->get_tree_data_cate_staff($data['type'],$data['cate'], get_staff_user_id()));
      }

      if($data['type'] == 'import'){
        if (!is_admin() && !has_permission('purchase', '', 'create')) {
          access_denied('purchase');
        }
        $this->load->model('staff_model');
        $data_staff = $this->staff_model->get(get_staff_user_id());

        /*get language active*/
        if ($data_staff) {
          if ($data_staff->default_language != '') {
            $data['active_language'] = $data_staff->default_language;

          } else {

            $data['active_language'] = get_option('active_language');
          }

        } else {
          $data['active_language'] = get_option('active_language');
        }
      }

      $this->load->view('team_password_mgt/team_password_management', $data);
    }

    /**
     * add normal
     * @param id
     * @return view
     */
    public function add_normal($id = '' )
    {   
      if(!has_permission('team_password','','view_own') && !has_permission('team_password','','view') && !is_admin()){
        access_denied('team_password');
      }

      $cate = $this->input->get('cate');

      if($cate != 'all' && $cate != ''){
        $data['cate'] = $cate;
      }else{
        $data['cate'] = '';
      }

      $data['title'] = _l('add_normal');
      if ($this->input->post()) {
        $message          = '';
        $data             = $this->input->post();
        if ($data['id'] == '') {
          if(!has_permission('team_password','','create') && !is_admin()){
            access_denied('team_password');
          }
          $insert_id = $this->team_password_model->add_normal($data);
          if ($insert_id) {
            handle_item_password_file($insert_id,'tp_normal');
            $success = true;
            $message = _l('added_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/team_password_mgt?cate='.$data['mgt_id'].'&type=normal'));
        } else {
          if(!has_permission('team_password','','edit') && !is_admin()){
            access_denied('team_password');
          }

          handle_item_password_file($data['id'],'tp_normal');

          $success = $this->team_password_model->update_normal($data);
          if ($success) {
            $message = _l('updated_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/team_password_mgt?cate='.$data['mgt_id'].'&type=normal'));
        }
        die;
      }
      $data['category'] = $this->team_password_model->get_category_management();
      if($id != ''){
        $data['title'] = _l('update_normal');
        $data['normal'] = $this->team_password_model->get_normal($id);      
      }

      $this->load->model('projects_model');
      $this->load->model('contracts_model');

      if(is_admin()){
        $data['contracts'] = $this->contracts_model->get();
        $data['projects'] = $this->projects_model->get();
      }else{
        $data['contracts'] = $this->contracts_model->get('', ['tblcontracts.addedfrom' => get_staff_user_id()]);
        $data['projects'] = $this->projects_model->get('',  db_prefix() . 'projects.id IN (SELECT project_id FROM ' . db_prefix() . 'project_members WHERE staff_id=' . get_staff_user_id() . ')' );
      }

      $this->load->view('team_password_mgt/add_normal', $data);
    }
    /**
     * normal table
     * @return json
     */
    public function normal_table($category)
    {
     if ($this->input->is_ajax_request()) {
      if($this->input->post()){
        if($category != 'all'){
          $category_filter  = $category;
        }else{
         $category_filter  = '';
       }

       $query = '';
       $str_cate = '';
       if($category_filter != ''){
        $cate_ids = get_recursive_cate($category_filter);

        if($cate_ids && count($cate_ids) > 0){
          foreach ($cate_ids as $s) {
            $str_cate = $str_cate . $s['id'].',';
          }
        }
        $str_cate = $str_cate. $category_filter;

        $query .= 'AND mgt_id IN ('.$str_cate.')';

      }else{
        if(!has_permission('team_password','','view') && !is_admin()){
          $ids = $this->team_password_model->list_cate_permission(get_staff_user_id());
          foreach($ids as $idc){
            $query .= ' OR mgt_id IN (select 
            id 
            from    (select * from '.db_prefix().'team_password_category
            order by '.db_prefix().'team_password_category.parent, '.db_prefix().'team_password_category.id) departments_sorted,
            (select @pv := '.$idc.') initialisation
            where   find_in_set(parent, @pv)
            and     length(@pv := concat(@pv, ",", id)) OR id = '.$idc.')';
          }
        }
      }

      $select = [
        'id',
        'name',
        'url',
        'user_name',
        'notice',
        'mgt_id',
        'add_from',

      ];

      $where              = [];

      if(!has_permission('team_password','','view') && !is_admin()){
        if($category_filter != ''){
          array_push($where, ' AND ( (add_from = '.get_staff_user_id().' AND add_by = "staff") OR mgt_id IN ('.$str_cate.') OR '.get_staff_user_id().' IN (SELECT staff from '.db_prefix().'permission WHERE (obj_id = '.db_prefix().'tp_normal.id AND type = "normal") ))');
        }else{
          array_push($where, ' AND ( (add_from = '.get_staff_user_id().' AND add_by = "staff") OR '.get_staff_user_id().' IN (SELECT staff from '.db_prefix().'permission WHERE (obj_id = '.db_prefix().'tp_normal.id AND type = "normal") ))');
        }
      }

      array_push($where, $query);


      $aColumns     = $select;
      $sIndexColumn = 'id';
      $sTable       = db_prefix() . 'tp_normal';
      $join         = [];

      $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
        'password'
      ]);


      $output  = $result['output'];
      $rResult = $result['rResult'];
      foreach ($rResult as $aRow) {
        $row = [];
        $row[] = $aRow['id'];             
        $row[] = $aRow['name'];  
        $category_name = '';
        if($aRow['mgt_id']){
          $data_category = $this->team_password_model->get_category_management($aRow['mgt_id']); 
          if($data_category){
           $category_name = '<i class="fa '.$data_category->icon.'"></i> '.$data_category->category_name;
         }      
       }
       $row[] = $category_name;             
       $row[] = $aRow['url'];             
       $row[] = $aRow['user_name'];             
       $row[] = $aRow['notice'];             
       $option = '';
       if(is_admin()){
        $option .= '<a href="' . admin_url('team_password/view_normal/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
        $option .= '<i class="fa fa-eye"></i>';
        $option .= '</a>';
        $option .= '<a href="' . admin_url('team_password/add_normal/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
        $option .= '<i class="fa fa-pencil-square-o"></i>';
        $option .= '</a>';
        $password = AES_256_Decrypt($aRow['password']);
        $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
        $option .= '<i class="fa fa-copy"></i>';
        $option .= '</a>';
        $option .= '<a href="' . admin_url('team_password/delete_normal/'.$aRow['id'].'/'.$category) . '" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'">';
        $option .= '<i class="fa fa-remove"></i>';
        $option .= '</a>';
      }
      else{
        if(has_permission('team_password','','view') || $aRow['add_from'] == get_staff_user_id()){
          $option .= '<a href="' . admin_url('team_password/view_normal/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'">';
          $option .= '<i class="fa fa-eye"></i>';
          $option .= '</a>';

          if(has_permission('team_password','','edit') || $aRow['add_from'] == get_staff_user_id()){
            $option .= '<a href="' . admin_url('team_password/add_normal/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
            $option .= '<i class="fa fa-pencil-square-o"></i>';
            $option .= '</a>';
          }
          $password = AES_256_Decrypt($aRow['password']);
          $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
          $option .= '<i class="fa fa-copy"></i>';
          $option .= '</a>';
        }else{
          if(get_permission('normal',$aRow['id'],'r') == 1 &&!get_permission('normal',$aRow['id'],'w') == 1){
            $option .= '<a href="' . admin_url('team_password/view_normal/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
            $option .= '<i class="fa fa-eye"></i>';
            $option .= '</a>';
            $password = AES_256_Decrypt($aRow['password']);
            $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
            $option .= '<i class="fa fa-copy"></i>';
            $option .= '</a>';
          }
          elseif(get_permission('normal',$aRow['id'],'rw') == 1 ||get_permission('normal',$aRow['id'],'w') == 1){
            $option .= '<a href="' . admin_url('team_password/view_normal/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'">';
            $option .= '<i class="fa fa-eye"></i>';
            $option .= '</a>';
            $option .= '<a href="' . admin_url('team_password/add_normal/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
            $option .= '<i class="fa fa-pencil-square-o"></i>';
            $option .= '</a>';
            $password = AES_256_Decrypt($aRow['password']);
            $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
            $option .= '<i class="fa fa-copy"></i>';
            $option .= '</a>';
          }
        }

        if(has_permission('team_password','','delete')){
          $option .= '<a href="' . admin_url('team_password/delete_normal/'.$aRow['id'].'/'.$category) . '" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'">';
          $option .= '<i class="fa fa-remove"></i>';
          $option .= '</a>';
        }
      }

      $row[] = $option; 
      $output['aaData'][] = $row;  
    }                                    
  }

  echo json_encode($output);
  die();

}
}
    /**
     * delete normal
     * @param  id
     * @return redirect
     *     
     */
    public function delete_normal($id = '',$cate)
    {
      if(!has_permission('team_password','','delete') && !is_admin()){
        access_denied('team_password');
      }
      $response = $this->team_password_model->delete_normal($id);
      if($response == true){
        set_alert('success', _l('deleted', _l('category')));
      }
      else{
        set_alert('warning', _l('problem_deleting'));            
      }
      redirect(admin_url('team_password/team_password_mgt?cate='.$cate.'&type=normal'));
    }

    /**
     * View normal
     * @param  string $id
     * @return view    
     */
    public function view_normal($id = ''){
      if(!(get_permission('normal',$id) == 0) && !has_permission('team_password','','view_own') && !has_permission('team_password','','view') && !is_admin()){
        access_denied('team_password');
      }

      if($id != ''){
        $this->team_password_model->log_password_action($id,'normal','see');
        $data['title'] = _l('view_normal');
        $data['tab'] = $this->input->get('tab');
        $data['id'] = $id;
        $this->load->model('staff_model');
        $data['staffs'] = $this->staff_model->get();              
        $data['normal'] = $this->team_password_model->get_normal($id); 
        $data['contact'] = $this->team_password_model->get_contact();
        $this->load->model('client_groups_model');
        $data['customer_groups'] = $this->client_groups_model->get_groups();
        $data['logs'] = $this->team_password_model->get_logs_password($id,'normal');
        $this->load->view('team_password_mgt/view_normal', $data);
      }
    }
    /**
     * add permission
     * @return redirect
     */
    public function add_permission(){
      if(!has_permission('team_password','','create') && !is_admin()){
        access_denied('team_password');
      }
      if ($this->input->post()) {
        $message          = '';
        $data             = $this->input->post();
        $view_name = $data['view_name'];
        unset($data['view_name']);
        $insert_id = $this->team_password_model->add_permission($data);
        if ($insert_id) {
          $success = true;
          $message = _l('added_successfully');
          set_alert('success', $message);
        }
        redirect(admin_url('team_password/'.$view_name.'/'.$data['obj_id'].'?tab=permission'));
        die;
      }       
    }
    /**
     * permision table
     * @return json
    */

    public function permission_table()
    {
     if ($this->input->is_ajax_request()) {
      if($this->input->post()){
        $obj_id = $this->input->post('obj_id'); 
        $type = $this->input->post('type'); 
        $query = '';
        if($obj_id!=''){
          $query = ' where obj_id = '.$obj_id.' and type = \''.$type.'\'';
        } 
        $select = [
          'id',
          'id',
          'id',
          'id',
          'id',     
        ];
        $where              = [(($query!='')?$query:'')];


        $aColumns     = $select;
        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'permission';
        $join         = [];

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
          'id',
          'staff',
          'r',
          'w',

        ]);


        $output  = $result['output'];
        $rResult = $result['rResult'];
        foreach ($rResult as $aRow) {
          $row = [];
          $row[] = $aRow['id'];             
          $row[] = get_staff_full_name($aRow['staff']);             
          $row[] = _l($aRow['r']);             
          $row[] = _l($aRow['w']);             


          $option = '';
          if(has_permission('team_password','','edit') || is_admin()){
            $option .= '<a href="#" onclick="update_permission(this)" data-id="'.$aRow['id'].'" data-staff="'.$aRow['staff'].'" data-read="'.$aRow['r'].'" data-write="'.$aRow['w'].'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
            $option .= '<i class="fa fa-pencil-square-o"></i>';
            $option .= '</a>';
          }

          if(has_permission('team_password','','delete') || is_admin()){
            $option .= '<a href="' . admin_url('team_password/delete_permision/'.$aRow['id']. '/'.$type.'/'.$obj_id.'').'" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'">';
            $option .= '<i class="fa fa-remove"></i>';
            $option .= '</a>';
          }

          $row[] = $option;
          $output['aaData'][] = $row;                                      
        }

        echo json_encode($output);
        die();
      }
    }
  }

    /**
     * { permission table by cate }
     */
    public function permission_table_by_cate()
    {
     if ($this->input->is_ajax_request()) {
      if($this->input->post()){

        $cate = $this->input->post('cate'); 



        $select = [
          db_prefix() . 'permission.id',
          db_prefix() . 'permission.id',
          db_prefix() . 'permission.id',
          db_prefix() . 'permission.id',
          db_prefix() . 'permission.id', 
          db_prefix() . 'permission.id',     
        ];

        $aColumns     = $select;
        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'permission';
        $join         = [
          'LEFT JOIN '.db_prefix().'tp_bank_account ON '.db_prefix().'tp_bank_account.id = '.db_prefix().'permission.obj_id AND '.db_prefix().'permission.type = "bank_account"',
          'LEFT JOIN '.db_prefix().'tp_credit_card ON '.db_prefix().'tp_credit_card.id = '.db_prefix().'permission.obj_id AND '.db_prefix().'permission.type = "credit_card"',
          'LEFT JOIN '.db_prefix().'tp_email ON '.db_prefix().'tp_email.id = '.db_prefix().'permission.obj_id AND '.db_prefix().'permission.type = "email"',
          'LEFT JOIN '.db_prefix().'tp_normal ON '.db_prefix().'tp_normal.id = '.db_prefix().'permission.obj_id AND '.db_prefix().'permission.type = "normal"',
          'LEFT JOIN '.db_prefix().'tp_server ON '.db_prefix().'tp_server.id = '.db_prefix().'permission.obj_id AND '.db_prefix().'permission.type = "server"',
          'LEFT JOIN '.db_prefix().'tp_software_license ON '.db_prefix().'tp_software_license.id = '.db_prefix().'permission.obj_id AND '.db_prefix().'permission.type = "software_license"',
          'LEFT JOIN '.db_prefix().'team_password_category ON '.db_prefix().'team_password_category.id = '.db_prefix().'permission.obj_id AND '.db_prefix().'permission.type = "category"',
        ];
        $where = [];

        if($cate != 'all'){
          $query =  'IN (select 
          id 
          from    (select * from '.db_prefix().'team_password_category
          order by '.db_prefix().'team_password_category.parent, '.db_prefix().'team_password_category.id) departments_sorted,
          (select @pv := '.$cate.') initialisation
          where   find_in_set(parent, @pv)
          and     length(@pv := concat(@pv, ",", id)) OR id = '.$cate.')';

          array_push($where, ' AND (('.db_prefix().'tp_bank_account.mgt_id '.$query.' AND type = "bank_account") 
            OR ('.db_prefix().'tp_credit_card.mgt_id '.$query.' AND type = "credit_card")
            OR ('.db_prefix().'tp_email.mgt_id '.$query.' AND type = "email") 
            OR ('.db_prefix().'tp_normal.mgt_id '.$query.' AND type = "normal") 
            OR ('.db_prefix().'tp_server.mgt_id '.$query.' AND type = "server") 
            OR ('.db_prefix().'tp_software_license.mgt_id '.$query.' AND type = "software_license")
            OR ('.db_prefix().'team_password_category.id '.$query.' AND type = "category") 
          )');

        }

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
          db_prefix() . 'permission.id as id',
          'staff',
          'r',
          'w',
          'type',
          'obj_id',
          db_prefix() . 'tp_bank_account.name as bank_account_name',
          db_prefix() . 'tp_credit_card.name as credit_card_name',
          db_prefix() . 'tp_email.name as email_name',
          db_prefix() . 'tp_normal.name as normal_name',
          db_prefix() . 'tp_server.name as server_name',
          db_prefix() . 'tp_software_license.name as software_license_name',
          db_prefix() . 'team_password_category.category_name as category_name',

          db_prefix() . 'tp_bank_account.mgt_id as bank_account_mgt_id',
          db_prefix() . 'tp_credit_card.mgt_id as credit_card_mgt_id',
          db_prefix() . 'tp_email.mgt_id as email_mgt_id',
          db_prefix() . 'tp_normal.mgt_id as normal_mgt_id',
          db_prefix() . 'tp_server.mgt_id as server_mgt_id',
          db_prefix() . 'tp_software_license.mgt_id as software_license_mgt_id',

        ]);


        $output  = $result['output'];
        $rResult = $result['rResult'];
        foreach ($rResult as $aRow) {
          $row = [];            
          $row[] = get_staff_full_name($aRow['staff']);       
          $row[] = $aRow[$aRow['type'].'_name'];
          $row[] = _l($aRow['type']);
          if($aRow['type'] != 'category'){
            $row[] = get_category_name_tp($aRow[$aRow['type'].'_mgt_id']);
          } else{
            $row[] = $aRow[$aRow['type'].'_name'];
          }  
          $row[] = _l($aRow['r']);             
          $row[] = _l($aRow['w']);             


          $option = '';
          if(has_permission('team_password','','edit') || is_admin()){
            $option .= '<a href="#" onclick="update_permission('.$aRow['id'].',this)" data-id="'.$aRow['id'].'" data-staff="'.$aRow['staff'].'" data-read="'.$aRow['r'].'" data-write="'.$aRow['w'].'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
            $option .= '<i class="fa fa-pencil-square-o"></i>';
            $option .= '</a>';
          }

          if(has_permission('team_password','','delete') || is_admin()){
            $option .= '<a href="' . admin_url('team_password/delete_permision_by_cate/'.$aRow['id']. '/'.$cate).'" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'">';
            $option .= '<i class="fa fa-remove"></i>';
            $option .= '</a>';
          }

          $row[] = $option;
          $output['aaData'][] = $row;                                      
        }

        echo json_encode($output);
        die();
      }
    }
  }

    /**
     * delete permision
     * @param  $id    
     * @param  $view  
     * @param  $obj_id
     * @return redirect        
    */
    
    public function delete_permision_by_cate($id ,$cate ){
      if(!has_permission('team_password','','delete') && !is_admin()){
        access_denied('team_password');
      }

      $response = $this->team_password_model->delete_permision($id);
      if($response == true){
        set_alert('success', _l('deleted', _l('permission')));
      }
      else{
        set_alert('warning', _l('problem_deleting'));            
      }
      redirect(admin_url('team_password/team_password_mgt?cate='.$cate.'&type=permission'));
    }

    /**
     * delete permision
     * @param  $id    
     * @param  $view  
     * @param  $obj_id
     * @return redirect        
    */
    
    public function delete_permision($id = '',$view = '',$obj_id = ''){
      if(!has_permission('team_password','','delete') && !is_admin()){
        access_denied('team_password');
      }

      $response = $this->team_password_model->delete_permision($id);
      if($response == true){
        set_alert('success', _l('deleted', _l('permission')));
      }
      else{
        set_alert('warning', _l('problem_deleting'));            
      }
      redirect(admin_url('team_password/view_'.$view.'/'.$obj_id.'?tab=permission'));
    }
    
    /**
     * add share
     * @return redirect   
     */
    public function add_share(){
      if ($this->input->post()) {
        $message          = '';
        $data             = $this->input->post();
        $view = '';
        if(isset($data['view'])){
          $view = $data['view'];
          unset($data['view']);
        }
        $obj_id = '';
        if(isset($data['share_id'])){
          $obj_id = $data['share_id'];
        }

        $data['creator'] = get_staff_user_id();
        if ($data['id'] == '') {  
          if(!has_permission('team_password','','create') && !is_admin()){
            access_denied('team_password');
          }            
          $insert_id = $this->team_password_model->add_share($data);
          if ($insert_id) {
            $success = true;
            $message = _l('added_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/'.$view.'/'.$obj_id.'?tab=share'));
        } else {
          if(!has_permission('team_password','','edit') && !is_admin()){
            access_denied('team_password');
          } 
          $success = $this->team_password_model->update_share($data);
          if ($success) {
            $message = _l('updated_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/'.$view.'/'.$obj_id.'?tab=share'));
        }
        die;
      }
    }


    /**
     * share table
     * @return json
     */
    public function share_table()
    {
     if ($this->input->is_ajax_request()) {
      if($this->input->post()){
        $obj_id = $this->input->post('obj_id'); 
        $type = $this->input->post('type'); 
        $query = '';
        if($obj_id!=''){
          $query = ' where share_id = '.$obj_id.' and type = \''.$type.'\'';
        } 
        $select = [
          'id',
          'id',
          'id',
          'id',
          'id',

        ];
        $where              = [(($query!='')?$query:'')];


        $aColumns     = $select;
        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'tp_share';
        $join         = [];

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
          'id',
          'not_in_the_system',
          'mgt_id',
          'type',
          'client',
          'email',
          'effective_time',
          'creator',
          'datecreator',
          'share_id',
          'r',
          'w',
          'unlimited',
          'customer_group',
          'send_notify',

        ]);


        $output  = $result['output'];
        $rResult = $result['rResult'];
        foreach ($rResult as $aRow) {
          $row = [];
          $row[] = $aRow['id'];   
          $customer = '';

          if($aRow['not_in_the_system'] == 'off'){
            $name = '';
            $client_name = '';
            $contact = $this->team_password_model->get_contact_by_email($aRow['client']);
            if($contact && !is_array($contact)){
              $lastname = '';
              $firstname = '';
              if(isset($contact->id)){
                $client_id = get_user_id_by_contact_id($contact->id);
                $client_name = get_company_name($client_id);
              }
              if(isset($contact->lastname)){
                $lastname = $contact->lastname;
              }
              if(isset($contact->firstname)){
                $firstname = $contact->firstname;
              }
              $name = $lastname.' '.$firstname;
              $customer = $client_name.' - '. $name.' ['.$aRow['client'].']';
            }else{
              $customer = _l('customer_groups').': '. customer_group_name($aRow['customer_group']);
            }

          }
          else{
            $customer = $aRow['email'];                    
          }

          $row[] = $customer;             
          $row[] = _d($aRow['datecreator']);             
          $row[] = _d($aRow['effective_time']);             



          $option = '';
          if(has_permission('team_password','','edit') || is_admin()){
            $option .= '<a href="javascript:void(0)" onclick="update(this)" data-id="'.$aRow['id'].'" data-not_in_the_system="'.$aRow['not_in_the_system'].'" data-client="'.$aRow['client'].'" data-email="'.$aRow['email'].'" data-email="'.$aRow['email'].'" data-share_id="'.$aRow['share_id'].'" data-effective_time="'.$aRow['effective_time'].'" data-read="'.$aRow['r'].'" data-write="'.$aRow['w'].'" data-unlimited="'.$aRow['unlimited'].'" data-customer_group="'.$aRow['customer_group'].'" data-send_notify="'.$aRow['send_notify'].'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
            $option .= '<i class="fa fa-pencil-square-o"></i>';
            $option .= '</a>';
          }

          if(has_permission('team_password','','delete') || is_admin()){
            $option .= '<a href="' . admin_url('team_password/delete_share/'.$aRow['id']).'/'.$type.'/'.$obj_id. '/view_normal" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'">';
            $option .= '<i class="fa fa-remove"></i>';
            $option .= '</a>';
          }
          $row[] = $option;
          $output['aaData'][] = $row;                                      
        }

        echo json_encode($output);
        die();
      }
    }
  }

     /**
     * { share table by cate }
     */
     public function share_table_by_cate()
     {
       if ($this->input->is_ajax_request()) {
        if($this->input->post()){

          $cate = $this->input->post('cate'); 



          $select = [
            db_prefix() . 'tp_share.id',
            db_prefix() . 'tp_share.id',
            db_prefix() . 'tp_share.id',
            db_prefix() . 'tp_share.id',
            db_prefix() . 'tp_share.id', 

          ];

          $aColumns     = $select;
          $sIndexColumn = 'id';
          $sTable       = db_prefix() . 'tp_share';
          $join         = [
            'LEFT JOIN '.db_prefix().'tp_bank_account ON '.db_prefix().'tp_bank_account.id = '.db_prefix().'tp_share.share_id AND '.db_prefix().'tp_share.type = "bank_account"',
            'LEFT JOIN '.db_prefix().'tp_credit_card ON '.db_prefix().'tp_credit_card.id = '.db_prefix().'tp_share.share_id AND '.db_prefix().'tp_share.type = "credit_card"',
            'LEFT JOIN '.db_prefix().'tp_email ON '.db_prefix().'tp_email.id = '.db_prefix().'tp_share.share_id AND '.db_prefix().'tp_share.type = "email"',
            'LEFT JOIN '.db_prefix().'tp_normal ON '.db_prefix().'tp_normal.id = '.db_prefix().'tp_share.share_id AND '.db_prefix().'tp_share.type = "normal"',
            'LEFT JOIN '.db_prefix().'tp_server ON '.db_prefix().'tp_server.id = '.db_prefix().'tp_share.share_id AND '.db_prefix().'tp_share.type = "server"',
            'LEFT JOIN '.db_prefix().'tp_software_license ON '.db_prefix().'tp_software_license.id = '.db_prefix().'tp_share.share_id AND '.db_prefix().'tp_share.type = "software_license"',
            'LEFT JOIN '.db_prefix().'team_password_category ON '.db_prefix().'team_password_category.id = '.db_prefix().'tp_share.share_id AND '.db_prefix().'tp_share.type = "category"',
          ];
          $where = [];

          if($cate != 'all'){
            $query =  'IN (select 
            id 
            from    (select * from '.db_prefix().'team_password_category
            order by '.db_prefix().'team_password_category.parent, '.db_prefix().'team_password_category.id) departments_sorted,
            (select @pv := '.$cate.') initialisation
            where   find_in_set(parent, @pv)
            and     length(@pv := concat(@pv, ",", id)) OR id = '.$cate.')';

            array_push($where, ' AND (('.db_prefix().'tp_bank_account.mgt_id '.$query.' AND type = "bank_account") 
              OR ('.db_prefix().'tp_credit_card.mgt_id '.$query.' AND type = "credit_card")
              OR ('.db_prefix().'tp_email.mgt_id '.$query.' AND type = "email") 
              OR ('.db_prefix().'tp_normal.mgt_id '.$query.' AND type = "normal") 
              OR ('.db_prefix().'tp_server.mgt_id '.$query.' AND type = "server") 
              OR ('.db_prefix().'tp_software_license.mgt_id '.$query.' AND type = "software_license")
              OR ('.db_prefix().'team_password_category.id '.$query.' AND type = "category") 
            )');

          }

          if ($this->input->post('customer_group')
            && count($this->input->post('customer_group')) > 0) {
            array_push($where, 'AND customer_group IN (' . implode(',', $this->input->post('customer_group')) . ')');
        }

        $client_filter = $this->input->post('client_filter');

        if (isset($client_filter) && count($client_filter) > 0) {
          $where_client_filter = '';
          foreach ($client_filter as $t) {
            if ($t != '') {
              if ($where_client_filter == '') {
                $where_client_filter .= ' AND (client = "' . $t . '"';
              } else {
                $where_client_filter .= ' or client = "' . $t . '"';
              }
            }
          }
          if ($where_client_filter != '') {
            $where_client_filter .= ')';
            array_push($where, $where_client_filter);
          }
        }

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
          db_prefix() . 'tp_share.id as id',
          'not_in_the_system',
          'type',
          'client',
          'email',
          'effective_time',
          'creator',
          db_prefix() . 'tp_share.datecreator as datecreator',
          'share_id',
          'r',
          'w',
          'unlimited',
          'customer_group',
          'send_notify',
          db_prefix() . 'tp_bank_account.name as bank_account_name',
          db_prefix() . 'tp_credit_card.name as credit_card_name',
          db_prefix() . 'tp_email.name as email_name',
          db_prefix() . 'tp_normal.name as normal_name',
          db_prefix() . 'tp_server.name as server_name',
          db_prefix() . 'tp_software_license.name as software_license_name',
          db_prefix() . 'team_password_category.category_name as category_name',

          db_prefix() . 'tp_bank_account.mgt_id as bank_account_mgt_id',
          db_prefix() . 'tp_credit_card.mgt_id as credit_card_mgt_id',
          db_prefix() . 'tp_email.mgt_id as email_mgt_id',
          db_prefix() . 'tp_normal.mgt_id as normal_mgt_id',
          db_prefix() . 'tp_server.mgt_id as server_mgt_id',
          db_prefix() . 'tp_software_license.mgt_id as software_license_mgt_id',

        ]);


        $output  = $result['output'];
        $rResult = $result['rResult'];
        foreach ($rResult as $aRow) {
          $row = [];
          $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
          $row[] = $aRow['id'];   
          $customer = '';

          if($aRow['not_in_the_system'] == 'off'){
            $name = '';
            $client_name = '';
            $contact = $this->team_password_model->get_contact_by_email($aRow['client']);
            if($contact && !is_array($contact)){
              $lastname = '';
              $firstname = '';
              if(isset($contact->id)){
                $client_id = get_user_id_by_contact_id($contact->id);
                $client_name = get_company_name($client_id);
              }
              if(isset($contact->lastname)){
                $lastname = $contact->lastname;
              }
              if(isset($contact->firstname)){
                $firstname = $contact->firstname;
              }
              $name = $lastname.' '.$firstname;
              $customer = $client_name.' - '. $name.' ['.$aRow['client'].']';
            }else{
              $customer = _l('customer_groups').': '. customer_group_name($aRow['customer_group']);
            }

          }
          else{
            $customer = $aRow['email'];                    
          }

          $row[] = $customer; 

          $row[] = $aRow[$aRow['type'].'_name'];
          $row[] = _l($aRow['type']);
          if($aRow['type'] != 'category'){
            $row[] = get_category_name_tp($aRow[$aRow['type'].'_mgt_id']);
          } else{
            $row[] = $aRow[$aRow['type'].'_name'];
          }

          $row[] = _d($aRow['datecreator']);             
          $row[] = _d($aRow['effective_time']);             

          $option = '';
          if(has_permission('team_password','','edit') || is_admin()){
            $option .= '<a href="javascript:void(0)" onclick="update_share(this)" data-id="'.$aRow['id'].'" data-not_in_the_system="'.$aRow['not_in_the_system'].'" data-client="'.$aRow['client'].'" data-email="'.$aRow['email'].'" data-email="'.$aRow['email'].'" data-share_id="'.$aRow['share_id'].'" data-effective_time="'.$aRow['effective_time'].'" data-read="'.$aRow['r'].'" data-write="'.$aRow['w'].'" data-unlimited="'.$aRow['unlimited'].'" data-customer_group="'.$aRow['customer_group'].'" data-send_notify="'.$aRow['send_notify'].'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
            $option .= '<i class="fa fa-pencil-square-o"></i>';
            $option .= '</a>';
          }

          if(has_permission('team_password','','delete') || is_admin()){
            $option .= '<a href="' . admin_url('team_password/delete_share_by_cate/'.$aRow['id']).'/'.$cate.'" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'">';
            $option .= '<i class="fa fa-remove"></i>';
            $option .= '</a>';
          }
          $row[] = $option;
          $output['aaData'][] = $row;                                     
        }

        echo json_encode($output);
        die();
      }
    }
  }

    /**
     * delete share by cate
     * @param $id     
     * @param $mgt_id 
     * @param $view     
     * @return redirect         
     */
    public function delete_share_by_cate($id ,$cate)
    {   
      if(!has_permission('team_password','','delete') && !is_admin()){
        access_denied('team_password');
      }

      $response = $this->team_password_model->delete_share($id);
      if($response == true){
        set_alert('success', _l('deleted'));
      }
      else{
        set_alert('warning', _l('problem_deleting'));            
      }
      redirect(admin_url('team_password/team_password_mgt?cate='.$cate.'&type=share'));
    }

    /**
     * delete share
     * @param $id     
     * @param $mgt_id 
     * @param $view     
     * @return redirect         
     */
    public function delete_share($id='',$view = '', $obj_id = '')
    {   
      if(!has_permission('team_password','','delete') && !is_admin()){
        access_denied('team_password');
      }

      $response = $this->team_password_model->delete_share($id);
      if($response == true){
        set_alert('success', _l('deleted'));
      }
      else{
        set_alert('warning', _l('problem_deleting'));            
      }
      redirect(admin_url('team_password/view_'.$view.'/'.$obj_id.'?tab=share'));
    }   
    /**
     * bank account table
     * @return json
    */
    
    public function bank_account_table($category)
    {
     if ($this->input->is_ajax_request()) {
      if($this->input->post()){
        if($category != 'all'){
          $category_filter  = $category;
        }else{
         $category_filter  = '';
       }

       $query = '';
       $str_cate = '';
       if($category_filter != ''){
        $cate_ids = get_recursive_cate($category_filter);
        if($cate_ids && count($cate_ids) > 0){
          foreach ($cate_ids as $s) {
            $str_cate = $str_cate . $s['id'].',';
          }
        }
        $str_cate = $str_cate. $category_filter;

        $query .= 'AND mgt_id IN ('.$str_cate.')';
      }else{
        if(!has_permission('team_password','','view') && !is_admin()){
          $ids = $this->team_password_model->list_cate_permission(get_staff_user_id());
          foreach($ids as $idc){
            $query .= ' OR mgt_id IN (select 
            id 
            from    (select * from '.db_prefix().'team_password_category
            order by '.db_prefix().'team_password_category.parent, '.db_prefix().'team_password_category.id) departments_sorted,
            (select @pv := '.$idc.') initialisation
            where   find_in_set(parent, @pv)
            and     length(@pv := concat(@pv, ",", id)) OR id = '.$idc.')';
          }
        }
      }

      $select = [
        'id',
        'id',
        'id',
        'id',
        'id',
        'id',
        'id',
        'id'

      ];
      $where              = [];

      if(!has_permission('team_password','','view') && !is_admin()){
        if($category_filter != ''){
          array_push($where, ' AND ((add_from = '.get_staff_user_id().' AND add_by = "staff") OR mgt_id IN ('.$str_cate.') OR '.get_staff_user_id().' IN (SELECT staff from '.db_prefix().'permission WHERE (obj_id = '.db_prefix().'tp_bank_account.id AND type = "bank_account") ))');
        }else{
          array_push($where, ' AND ((add_from = '.get_staff_user_id().' AND add_by = "staff") OR '.get_staff_user_id().' IN (SELECT staff from '.db_prefix().'permission WHERE (obj_id = '.db_prefix().'tp_bank_account.id AND type = "bank_account") ))');
        }
      }

      array_push($where, $query);

      $aColumns     = $select;
      $sIndexColumn = 'id';
      $sTable       = db_prefix() . 'tp_bank_account';
      $join         = [];

      $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
        'id',
        'name',
        'url',
        'user_name',
        'notice',
        'enable_log',
        'mgt_id',
        'add_from',
        'pin',
      ]);


      $output  = $result['output'];
      $rResult = $result['rResult'];
      foreach ($rResult as $aRow) {

       $row = [];
       $row[] = $aRow['id'];             
       $row[] = $aRow['name']; 
       $category_name = '';
       if($aRow['mgt_id']){
        $data_category = $this->team_password_model->get_category_management($aRow['mgt_id']); 
        if($data_category){
         $category_name = '<i class="fa '.$data_category->icon.'"></i> '.$data_category->category_name;
       }      
     }
     $row[] = $category_name;             
     $row[] = $aRow['url'];             
     $row[] = $aRow['user_name'];             
     $row[] = $aRow['enable_log'];          
     $row[] = $aRow['notice'];         



     $option = '';
     if(is_admin()){
      $option .= '<a href="' . admin_url('team_password/view_bank_account/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
      $option .= '<i class="fa fa-eye"></i>';
      $option .= '</a>';
      $option .= '<a href="' . admin_url('team_password/add_bank_account/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
      $option .= '<i class="fa fa-pencil-square-o"></i>';
      $option .= '</a>';
      $password = AES_256_Decrypt($aRow['pin']);
      $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
      $option .= '<i class="fa fa-copy"></i>';
      $option .= '</a>';
      $option .= '<a href="' . admin_url('team_password/delete_bank_account/'.$aRow['id'].'/'.$category) . '" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'" >';
      $option .= '<i class="fa fa-remove"></i>';
      $option .= '</a>';
    }
    else{
      if(has_permission('team_password','','view') || $aRow['add_from'] == get_staff_user_id()){
        $option .= '<a href="' . admin_url('team_password/view_bank_account/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
        $option .= '<i class="fa fa-eye"></i>';
        $option .= '</a>';

        if(has_permission('team_password','','edit') || $aRow['add_from'] == get_staff_user_id()){
          $option .= '<a href="' . admin_url('team_password/add_bank_account/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
          $option .= '<i class="fa fa-pencil-square-o"></i>';
          $option .= '</a>';
        }
        $password = AES_256_Decrypt($aRow['pin']);
        $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
        $option .= '<i class="fa fa-copy"></i>';
        $option .= '</a>';
      }else{

        if(get_permission('bank_account',$aRow['id'],'r') == 1 &&!get_permission('bank_account',$aRow['id'],'w') == 1){
          $option .= '<a href="' . admin_url('team_password/view_bank_account/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
          $option .= '<i class="fa fa-eye"></i>';
          $option .= '</a>';
          $password = AES_256_Decrypt($aRow['pin']);
          $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
          $option .= '<i class="fa fa-copy"></i>';
          $option .= '</a>';
        }
        elseif(get_permission('bank_account',$aRow['id'],'rw') == 1 ||get_permission('bank_account',$aRow['id'],'w') == 1){
          $option .= '<a href="' . admin_url('team_password/view_bank_account/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
          $option .= '<i class="fa fa-eye"></i>';
          $option .= '</a>';
          $option .= '<a href="' . admin_url('team_password/add_bank_account/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
          $option .= '<i class="fa fa-pencil-square-o"></i>';
          $option .= '</a>';
          $password = AES_256_Decrypt($aRow['pin']);
          $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
          $option .= '<i class="fa fa-copy"></i>';
          $option .= '</a>';
        }

      }

      if(has_permission('team_password','','delete')){
        $option .= '<a href="' . admin_url('team_password/delete_bank_account/'.$aRow['id'].'/'.$category) . '" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'" >';
        $option .= '<i class="fa fa-remove"></i>';
        $option .= '</a>';
      }
    }
    $row[] = $option; 
    $output['aaData'][] = $row;  

  }

  echo json_encode($output);
  die();
}
}
}
      /**
     * delete bank account
     * @param  id
     * @return redirect
     * 
     */
      public function delete_bank_account($id = '',$cate)
      {   
        if(!has_permission('team_password','','delete') && !is_admin()){
          access_denied('team_password');
        }

        $response = $this->team_password_model->delete_bank_account($id);
        if($response == true){
          set_alert('success', _l('deleted', _l('category')));
        }
        else{
          set_alert('warning', _l('problem_deleting'));            
        }
        redirect(admin_url('team_password/team_password_mgt?cate='.$cate.'&type=bank_account'));
      }

    /**
     * add bank account permission
     * @return redirect
     */
    public function add_bank_account_permission(){
      if(!has_permission('team_password','','create') && !is_admin()){
        access_denied('team_password');
      }

      if ($this->input->post()) {
        $message          = '';
        $data             = $this->input->post();
        $insert_id = $this->team_password_model->add_permission($data);
        if ($insert_id) {
          $success = true;
          $message = _l('added_successfully');
          set_alert('success', $message);
        }
        redirect(admin_url('team_password/team_password_mgt/'.$data['mgt_id'].'?type=bank_account&tab=permission'));
        die;
      }       
    }
    /**
     * permision bank account table
     * @return json
     */
    public function bank_account_permission_table()
    {
     if ($this->input->is_ajax_request()) {
      if($this->input->post()){
        $mgt_fillter = $this->input->post('mgt_id'); 
        $query = '';
        if($mgt_fillter!=''){
          $query = ' where mgt_id = '.$mgt_fillter.' and type = \'bank_account\'';
        } 
        $select = [
          'id',
          'id',
          'id',
          'id',
          'id',

        ];
        $where              = [(($query!='')?$query:'')];


        $aColumns     = $select;
        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'permission';
        $join         = [];

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
          'id',
          'staff',
          'r',
          'w',

        ]);


        $output  = $result['output'];
        $rResult = $result['rResult'];
        foreach ($rResult as $aRow) {
          $row = [];
          $row[] = $aRow['id'];             
          $row[] = get_staff_full_name($aRow['staff']);             
          $row[] = _l($aRow['r']);             
          $row[] = _l($aRow['w']);             



          $option = '';
          if(has_permission('team_password','','edit') || is_admin()){
            $option .= '<a href="#" onclick="update_permission(this)" data-id="'.$aRow['id'].'" data-staff="'.$aRow['staff'].'" data-read="'.$aRow['r'].'" data-write="'.$aRow['w'].'" class="btn btn-default btn-icon" >';
            $option .= '<i class="fa fa-pencil-square-o"></i>';
            $option .= '</a>';
          }

          if(has_permission('team_password','','delete') || is_admin()){
            $option .= '<a href="' . admin_url('team_password/delete_permision/' . $mgt_fillter.'/'.$aRow['id']) . '/bank_account" class="btn btn-danger btn-icon _delete">';
            $option .= '<i class="fa fa-remove"></i>';
            $option .= '</a>';
          }
          $row[] = $option;
          $output['aaData'][] = $row;                                      
        }                
        echo json_encode($output);
        die();
      }
    }
  }    
    /**
     * add bank account share
     * @return json
     */
    public function add_bank_account_share(){
      if ($this->input->post()) {
        $message          = '';
        $data             = $this->input->post();
        $data['creator'] = get_staff_user_id();
        if ($data['id'] == '') { 
          if(!has_permission('team_password','','create') && !is_admin()){
            access_denied('team_password');
          }
          $insert_id = $this->team_password_model->add_bank_account_share($data);
          if ($insert_id) {
            $success = true;
            $message = _l('added_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/team_password_mgt/'.$data['mgt_id'].'?type=bank_account&tab=share'));
        } else {
          if(!has_permission('team_password','','edit') && !is_admin()){
            access_denied('team_password');
          }
          $success = $this->team_password_model->update_bank_account_share($data);
          if ($success) {
            $message = _l('updated_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/team_password_mgt/'.$data['mgt_id'].'?type=bank_account&tab=share'));
        }
        die;
      }
    }
    /**
     * view share bank_account
     * @param $id   
     * @param $hash 
     * @return view       
     */
    public function view_share_bank_account($id='',$hash=''){
      $data_share = $this->team_password_model->get_tp_share_hash($hash);
      if($data_share){
        $data['r'] = $data_share->r;
        $data['w'] = $data_share->w;
        $data['share_id'] = $data_share->share_id;
        $data['effective_time'] = $data_share->effective_time;
        $data['bank_account'] = $this->team_password_model->get_bank_account($data['share_id']);
        if(strtotime($data['effective_time'])<=strtotime(date('Y-m-d H:i:s'))){
          die;
        }
        $name = '';
        if($data['bank_account']){          
          if($data['bank_account']->enable_log == 'on'){
            $name = $data['bank_account']->name;
            $data['title'] = $name;
            $data['mgt_id'] = $id;        
            $data['id'] = $data['share_id'];
            if(($data['r'] == 'on' && $data['w'] == 'on')||($data['w'] == 'on')){
              $this->load->view('team_password_mgt/add_bank_account', $data);
            }
            elseif($data['r'] == 'on'){
              $this->load->view('team_password_mgt/view_bank_account', $data);
            }
          } 
          else{
            die;
          }      
        }
        else{
          die;
        }
      }
      else{
        die;
      }
    }
    /**
     * bank_account share table
     * @return json
     */
    public function bank_account_share_table()
    {
     if ($this->input->is_ajax_request()) {
      if($this->input->post()){
        $mgt_fillter = $this->input->post('mgt_id'); 
        $query = '';
        if($mgt_fillter!=''){
          $query = ' where mgt_id = '.$mgt_fillter.' and type = \'bank_account\'';
        } 
        $select = [
          'id',
          'id',
          'id',
          'id',
          'id',
          'id',
          'id',
          'id',

        ];
        $where              = [(($query!='')?$query:'')];


        $aColumns     = $select;
        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'tp_share';
        $join         = [];

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
          'id',
          'not_in_the_system',
          'mgt_id',
          'type',
          'client',
          'email',
          'effective_time',
          'creator',
          'datecreator',
          'share_id',
          'r',
          'w',

        ]);


        $output  = $result['output'];
        $rResult = $result['rResult'];
        foreach ($rResult as $aRow) {
          $row = [];
          $row[] = $aRow['id'];   
          $customer = '';

          if($aRow['not_in_the_system'] == 'off'){
            $name = '';
            $client_name = '';
            $contact = $this->team_password_model->get_contact_by_email($aRow['client']);
            if($contact){
              if(isset($contact->id)){
                $client_id = get_user_id_by_contact_id($contact->id);
                $client_name = get_company_name($client_id);
              }
              $name = $contact->lastname.' '.$contact->firstname;
            }
            $customer = $client_name.' - '. $name.' ['.$aRow['client'].']';
          }
          else{
            $customer = $aRow['email'];                    
          }
          $row[] = $customer;  
          $share = '';
          $data_share = $this->team_password_model->get_bank_account($aRow['share_id']);  
          if($data_share){
            $share = $data_share->name;
          }         
          $row[] = $share;  
          $row[] = $aRow['r'];   
          $row[] = $aRow['w'];  
          $row[] = _d($aRow['datecreator']);             
          $row[] = _d($aRow['effective_time']);             

          $option = '';
          if(has_permission('team_password','','edit') || is_admin()){
            $option .= '<a href="#" onclick="update(this)" data-id="'.$aRow['id'].'" data-not_in_the_system="'.$aRow['not_in_the_system'].'" data-client="'.$aRow['client'].'" data-email="'.$aRow['email'].'" data-email="'.$aRow['email'].'" data-share_id="'.$aRow['share_id'].'" data-effective_time="'.$aRow['effective_time'].'" data-read="'.$aRow['r'].'" data-write="'.$aRow['w'].'" class="btn btn-default btn-icon" >';
            $option .= '<i class="fa fa-pencil-square-o"></i>';
            $option .= '</a>';
          }

          if(has_permission('team_password','','delete') || is_admin()){ 
            $option .= '<a href="' . admin_url('team_password/delete_share/' . $mgt_fillter.'/'.$aRow['id']) . '/bank_account" class="btn btn-danger btn-icon _delete">';
            $option .= '<i class="fa fa-remove"></i>';
            $option .= '</a>';
          }
          $row[] = $option;
          $output['aaData'][] = $row;                                      
        }

        echo json_encode($output);
        die();
      }
    }
  }   
     /**
     * credit card table
     * @return json
     */
     public function credit_card_table($category)
     {
       if ($this->input->is_ajax_request()) {
        if($this->input->post()){
          if($category != 'all'){
            $category_filter  = $category;
          }else{
           $category_filter  = '';
         }

         $query = '';
         $str_cate = '';
         if($category_filter != ''){
          $cate_ids = get_recursive_cate($category_filter);
          if($cate_ids && count($cate_ids) > 0){
            foreach ($cate_ids as $s) {
              $str_cate = $str_cate . $s['id'].',';
            }
          }
          $str_cate = $str_cate. $category_filter;

          $query .= 'AND mgt_id IN ('.$str_cate.')';
        }else{
          if(!has_permission('team_password','','view') && !is_admin()){
            $ids = $this->team_password_model->list_cate_permission(get_staff_user_id());
            foreach($ids as $idc){
              $query .= ' OR mgt_id IN (select 
              id 
              from    (select * from '.db_prefix().'team_password_category
              order by '.db_prefix().'team_password_category.parent, '.db_prefix().'team_password_category.id) departments_sorted,
              (select @pv := '.$idc.') initialisation
              where   find_in_set(parent, @pv)
              and     length(@pv := concat(@pv, ",", id)) OR id = '.$idc.')';
            }
          }
        }

        $select = [
          'id',
          'id',
          'id',
          'id',
          'id',
          'id',
          'id',
          'id',

        ];
        $where              = [];

        if(!has_permission('team_password','','view') && !is_admin()){
          if($category_filter != ''){
            array_push($where, ' AND ((add_from = '.get_staff_user_id().' AND add_by = "staff") OR mgt_id IN ('.$str_cate.') OR '.get_staff_user_id().' IN (SELECT staff from '.db_prefix().'permission WHERE (obj_id = '.db_prefix().'tp_credit_card.id AND type = "credit_card") ))');
          }else{
            array_push($where, ' AND ((add_from = '.get_staff_user_id().' AND add_by = "staff") OR '.get_staff_user_id().' IN (SELECT staff from '.db_prefix().'permission WHERE (obj_id = '.db_prefix().'tp_credit_card.id AND type = "credit_card") ))');
          }
        }

        array_push($where, $query);

        $aColumns     = $select;
        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'tp_credit_card';
        $join         = [];

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
          'id',
          'name',
          'pin',
          'credit_card_type',
          'card_number',
          'card_cvc',
          'valid_from',
          'valid_to',
          'notice',
          'password',
          'enable_log',
          'mgt_id',
          'add_from',
        ]);


        $output  = $result['output'];
        $rResult = $result['rResult'];
        foreach ($rResult as $aRow) {

          $row = [];

          $row[] = $aRow['id'];             
          $row[] = $aRow['name']; 
          $category_name = '';
          if($aRow['mgt_id']){
            $data_category = $this->team_password_model->get_category_management($aRow['mgt_id']); 
            if($data_category){
             $category_name = '<i class="fa '.$data_category->icon.'"></i> '.$data_category->category_name;
           }      
         }
         $row[] = $category_name;             
         $row[] = $aRow['credit_card_type'];             
         $row[] = _d($aRow['valid_from']);             
         $row[] = _d($aRow['valid_to']);          
         $row[] = $aRow['notice'];             

         $option = '';
         if(is_admin()){
          $option .= '<a href="' . admin_url('team_password/view_credit_card/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
          $option .= '<i class="fa fa-eye"></i>';
          $option .= '</a>';
          $option .= '<a href="' . admin_url('team_password/add_credit_card/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
          $option .= '<i class="fa fa-pencil-square-o"></i>';
          $option .= '</a>';
          $password = AES_256_Decrypt($aRow['pin']);
          $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
          $option .= '<i class="fa fa-copy"></i>';
          $option .= '</a>';
          $option .= '<a href="' . admin_url('team_password/delete_credit_card/'.$aRow['id'].'/'.$category) . '" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'" >';
          $option .= '<i class="fa fa-remove"></i>';
          $option .= '</a>';
        }
        else{
          if(has_permission('team_password','','view') || $aRow['add_from'] == get_staff_user_id()){
            $option .= '<a href="' . admin_url('team_password/view_credit_card/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
            $option .= '<i class="fa fa-eye"></i>';
            $option .= '</a>';

            if(has_permission('team_password','','edit') || $aRow['add_from'] == get_staff_user_id()){
              $option .= '<a href="' . admin_url('team_password/add_credit_card/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
              $option .= '<i class="fa fa-pencil-square-o"></i>';
              $option .= '</a>';
            }
            $password = AES_256_Decrypt($aRow['pin']);
            $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
            $option .= '<i class="fa fa-copy"></i>';
            $option .= '</a>';
          }else{

            if(get_permission('credit_card',$aRow['id'],'r') == 1 &&!get_permission('credit_card',$aRow['id'],'w') == 1){
              $option .= '<a href="' . admin_url('team_password/view_credit_card/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
              $option .= '<i class="fa fa-eye"></i>';
              $option .= '</a>';
              $password = AES_256_Decrypt($aRow['pin']);
              $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
              $option .= '<i class="fa fa-copy"></i>';
              $option .= '</a>';
            }
            elseif(get_permission('credit_card',$aRow['id'],'rw') == 1 ||get_permission('credit_card',$aRow['id'],'w') == 1){
              $option .= '<a href="' . admin_url('team_password/view_credit_card/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
              $option .= '<i class="fa fa-eye"></i>';
              $option .= '</a>';
              $option .= '<a href="' . admin_url('team_password/add_credit_card/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
              $option .= '<i class="fa fa-pencil-square-o"></i>';
              $option .= '</a>';
              $password = AES_256_Decrypt($aRow['pin']);
              $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
              $option .= '<i class="fa fa-copy"></i>';
              $option .= '</a>';
            }
          }

          if(has_permission('team_password','','delete')){
            $option .= '<a href="' . admin_url('team_password/delete_credit_card/'.$aRow['id'].'/'.$category) . '" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'" >';
            $option .= '<i class="fa fa-remove"></i>';
            $option .= '</a>';
          }
        }
        $row[] = $option; 
        $output['aaData'][] = $row;                                      

      }


      echo json_encode($output);
      die();
    }
  }
}
      /**
     * delete credit card
     * @param  id
     * @return redirect
     */
      public function delete_credit_card($id = '',$cate)
      {
        if(!has_permission('team_password','','delete') && !is_admin()){
          access_denied('team_password');
        }
        $response = $this->team_password_model->delete_credit_card($id);
        if($response == true){
          set_alert('success', _l('deleted', _l('category')));
        }
        else{
          set_alert('warning', _l('problem_deleting'));            
        }
        redirect(admin_url('team_password/team_password_mgt?cate='.$cate.'&type=credit_card'));
      }
    /**
     * add credit card permission
     * @return redirect
    */
    public function add_credit_card_permission(){
      if(!has_permission('team_password','','create') && !is_admin()){
        access_denied('team_password');
      }
      if ($this->input->post()) {
        $message          = '';
        $data             = $this->input->post();
        $insert_id = $this->team_password_model->add_permission($data);
        if ($insert_id) {
          $success = true;
          $message = _l('added_successfully');
          set_alert('success', $message);
        }
        redirect(admin_url('team_password/team_password_mgt/'.$data['mgt_id'].'?type=credit_card&tab=permission'));
        die;
      }       
    }
    /**
     * permision credit card table
     * @return json
     */
    public function credit_card_permission_table()
    {
     if ($this->input->is_ajax_request()) {
      if($this->input->post()){
        $mgt_fillter = $this->input->post('mgt_id'); 
        $query = '';
        if($mgt_fillter!=''){
          $query = ' where mgt_id = '.$mgt_fillter.' and type = \'credit_card\'';
        } 
        $select = [
          'id',
          'id',
          'id',
          'id',
          'id',

        ];
        $where              = [(($query!='')?$query:'')];


        $aColumns     = $select;
        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'permission';
        $join         = [];

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
          'id',
          'staff',
          'r',
          'w',

        ]);


        $output  = $result['output'];
        $rResult = $result['rResult'];
        foreach ($rResult as $aRow) {
          $row = [];
          $row[] = $aRow['id'];             
          $row[] = get_staff_full_name($aRow['staff']);             
          $row[] = _l($aRow['r']);             
          $row[] = _l($aRow['w']);             



          $option = '';
          if(has_permission('team_password','','edit') || is_admin()){
            $option .= '<a href="#" onclick="update_permission(this)" data-id="'.$aRow['id'].'" data-staff="'.$aRow['staff'].'" data-read="'.$aRow['r'].'" data-write="'.$aRow['w'].'" class="btn btn-default btn-icon" >';
            $option .= '<i class="fa fa-pencil-square-o"></i>';
            $option .= '</a>';
          }

          if(has_permission('team_password','','delete') || is_admin()){
            $option .= '<a href="' . admin_url('team_password/delete_permision/' . $mgt_fillter.'/'.$aRow['id']) . '/credit_card" class="btn btn-danger btn-icon _delete">';
            $option .= '<i class="fa fa-remove"></i>';
            $option .= '</a>';
          }
          $row[] = $option;
          $output['aaData'][] = $row;                                      
        }

        echo json_encode($output);
        die();
      }
    }
  }    
    /**
     * add credit card share
     * @return redirect 
    */
    public function add_credit_card_share(){
      if ($this->input->post()) {
        $message          = '';
        $data             = $this->input->post();
        $data['creator'] = get_staff_user_id();
        if ($data['id'] == '') {
          if(!has_permission('team_password','','create') && !is_admin()){
            access_denied('team_password');
          }

          $insert_id = $this->team_password_model->add_credit_card_share($data);
          if ($insert_id) {
            $success = true;
            $message = _l('added_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/team_password_mgt/'.$data['mgt_id'].'?type=credit_card&tab=share'));
        } else {
          if(!has_permission('team_password','','edit') && !is_admin()){
            access_denied('team_password');
          }
          $success = $this->team_password_model->update_credit_card_share($data);
          if ($success) {
            $message = _l('updated_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/team_password_mgt/'.$data['mgt_id'].'?type=credit_card&tab=share'));
        }
        die;
      }
    }
    /**
     * view share credit card
     * @param $id   
     * @param $hash 
     * @return view       
     */
    public function view_share_credit_card($id='',$hash=''){
      $data_share = $this->team_password_model->get_tp_share_hash($hash);
      if($data_share){
        $data['r'] = $data_share->r;
        $data['w'] = $data_share->w;
        $data['share_id'] = $data_share->share_id;
        $data['effective_time'] = $data_share->effective_time;
        $data['credit_card'] = $this->team_password_model->get_credit_card($data['share_id']);
        if(strtotime($data['effective_time'])<=strtotime(date('Y-m-d H:i:s'))){
          die;
        }
        $name = '';
        if($data['credit_card']){          
          if($data['credit_card']->enable_log == 'on'){
            $name = $data['credit_card']->name;
            $data['title'] = $name;
            $data['mgt_id'] = $id;        
            $data['id'] = $data['share_id'];
            if(($data['r'] == 'on' && $data['w'] == 'on')||($data['w'] == 'on')){
              $this->load->view('team_password_mgt/add_credit_card', $data);
            }
            elseif($data['r'] == 'on'){
              $this->load->view('team_password_mgt/view_credit_card', $data);
            }
          } 
          else{
            die;
          }      
        }
        else{
          die;
        }
      }
      else{
        die;
      }
    }
    /**
     * credit card share table
     * @return json
     */
    public function credit_card_share_table()
    {
     if ($this->input->is_ajax_request()) {
      if($this->input->post()){
        $mgt_fillter = $this->input->post('mgt_id'); 
        $query = '';
        if($mgt_fillter!=''){
          $query = ' where mgt_id = '.$mgt_fillter.' and type = \'credit_card\'';
        } 
        $select = [
          'id',
          'id',
          'id',
          'id',
          'id',
          'id',
          'id',
          'id',

        ];
        $where              = [(($query!='')?$query:'')];


        $aColumns     = $select;
        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'tp_share';
        $join         = [];

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
          'id',
          'not_in_the_system',
          'mgt_id',
          'type',
          'client',
          'email',
          'effective_time',
          'creator',
          'datecreator',
          'share_id',
          'r',
          'w',

        ]);


        $output  = $result['output'];
        $rResult = $result['rResult'];
        foreach ($rResult as $aRow) {
          $row = [];
          $row[] = $aRow['id'];   
          $customer = '';

          if($aRow['not_in_the_system'] == 'off'){
            $name = '';
            $contact = $this->team_password_model->get_contact_by_email($aRow['client']);
            if($contact){
              $name = $contact->lastname.' '.$contact->firstname;
            }
            $customer = $name.' ['.$aRow['client'].']';
          }
          else{
            $customer = $aRow['email'];                    
          }
          $row[] = $customer;  
          $share = '';
          $data_share = $this->team_password_model->get_credit_card($aRow['share_id']);  
          if($data_share){
            $share = $data_share->name;
          }         
          $row[] = $share;  
          $row[] = $aRow['r'];   
          $row[] = $aRow['w'];  
          $row[] = _d($aRow['datecreator']);             
          $row[] = _d($aRow['effective_time']);             

          $option = '';

          if(has_permission('team_password','','edit') || is_admin()){
            $option .= '<a href="#" onclick="update(this)" data-id="'.$aRow['id'].'" data-not_in_the_system="'.$aRow['not_in_the_system'].'" data-client="'.$aRow['client'].'" data-email="'.$aRow['email'].'" data-email="'.$aRow['email'].'" data-share_id="'.$aRow['share_id'].'" data-effective_time="'.$aRow['effective_time'].'" data-read="'.$aRow['r'].'" data-write="'.$aRow['w'].'" class="btn btn-default btn-icon" >';
            $option .= '<i class="fa fa-pencil-square-o"></i>';
            $option .= '</a>';
          }

          if(has_permission('team_password','','delete') || is_admin()){
            $option .= '<a href="' . admin_url('team_password/delete_share/' . $mgt_fillter.'/'.$aRow['id']) . '/credit_card" class="btn btn-danger btn-icon _delete">';
            $option .= '<i class="fa fa-remove"></i>';
            $option .= '</a>';
          }
          $row[] = $option;
          $output['aaData'][] = $row;                                      
        }

        echo json_encode($output);
        die();
      }
    }
  }   

    /**
     * add bank account
     * @param id
     * @return view
     */
    public function add_bank_account($id = '')
    {
      $data['title'] = _l('add_bank_account');

      $cate = $this->input->get('cate');

      if($cate != 'all' && $cate != ''){
        $data['cate'] = $cate;
      }else{
        $data['cate'] = '';
      }

      if ($this->input->post()) {
        $message          = '';
        $data             = $this->input->post();
        if ($data['id'] == '') {
          if(!has_permission('team_password','','create') && !is_admin()){
            access_denied('team_password');
          }
          $insert_id = $this->team_password_model->add_bank_account($data);
          if ($insert_id) {
            handle_item_password_file($insert_id,'tp_bank');
            $success = true;
            $message = _l('added_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/team_password_mgt?cate='.$data['mgt_id'].'&type=bank_account'));
        } else {
          if(!has_permission('team_password','','edit') && !is_admin()){
            access_denied('team_password');
          }

          handle_item_password_file($data['id'],'tp_bank');
          $success = $this->team_password_model->update_bank_account($data);
          if ($success) {
            $message = _l('updated_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/team_password_mgt?cate='.$data['mgt_id'].'&type=bank_account'));
        }
        die;
      }
      $data['category'] = $this->team_password_model->get_category_management();
      if($id != ''){
        $data['title'] = _l('update_bank_account');
        $data['bank_account'] = $this->team_password_model->get_bank_account($id);      
      }

      $this->load->model('projects_model');
      $this->load->model('contracts_model');

      if(is_admin()){
        $data['contracts'] = $this->contracts_model->get();
        $data['projects'] = $this->projects_model->get();
      }else{
        $data['contracts'] = $this->contracts_model->get('', ['tblcontracts.addedfrom' => get_staff_user_id()]);
        $data['projects'] = $this->projects_model->get('',  db_prefix() . 'projects.id IN (SELECT project_id FROM ' . db_prefix() . 'project_members WHERE staff_id=' . get_staff_user_id() . ')' );
      }

      $this->load->view('team_password_mgt/add_bank_account', $data);
    }
    /**
     * View bank account
     * @return view
     */
    public function view_bank_account($id = ''){
      if(!(get_permission('bank_account',$id) == 0 ) && !has_permission('team_password','','view_own') && !has_permission('team_password','','view') && !is_admin()){
        access_denied('team_password');
      }
      if($id != ''){
        $this->team_password_model->log_password_action($id,'bank_account','see');
        $data['title'] = _l('view_bank_account');
        $data['tab'] = $this->input->get('tab');
        $data['id'] = $id;
        $this->load->model('staff_model');
        $data['staffs'] = $this->staff_model->get();              
        $data['bank_account'] = $this->team_password_model->get_bank_account($id); 
        $data['contact'] = $this->team_password_model->get_contact();
        $this->load->model('client_groups_model');
        $data['customer_groups'] = $this->client_groups_model->get_groups();
        $data['logs'] = $this->team_password_model->get_logs_password($id,'bank_account');
        $this->load->view('team_password_mgt/view_bank_account', $data);       
      }
    }
    /**
     * add credit card
     * @param id
     * @return view
    */
    public function add_credit_card($id = '')
    {
      $data['title'] = _l('add_credit_card');

      $cate = $this->input->get('cate');

      if($cate != 'all' && $cate != ''){
        $data['cate'] = $cate;
      }else{
        $data['cate'] = '';
      }

      if ($this->input->post()) {
        $message          = '';
        $data             = $this->input->post();
        if ($data['id'] == '') {
          if(!has_permission('team_password','','create') && !is_admin()){
            access_denied('team_password');
          }
          $insert_id = $this->team_password_model->add_credit_card($data);
          if ($insert_id) {
            handle_item_password_file($insert_id,'tp_credit_card');
            $success = true;
            $message = _l('added_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/team_password_mgt?cate='.$data['mgt_id'].'&type=credit_card'));
        } else {
          if(!has_permission('team_password','','edit') && !is_admin()){
            access_denied('team_password');
          }
          handle_item_password_file($data['id'],'tp_credit_card');
          $success = $this->team_password_model->update_credit_card($data);
          if ($success) {
            $message = _l('updated_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/team_password_mgt?cate='.$data['mgt_id'].'&type=credit_card'));
        }
        die;
      }
      $data['category'] = $this->team_password_model->get_category_management();
      if($id != ''){
        $data['title'] = _l('update_credit_card');
        $data['credit_card'] = $this->team_password_model->get_credit_card($id);     
      }

      $this->load->model('projects_model');
      $this->load->model('contracts_model');

      if(is_admin()){
        $data['contracts'] = $this->contracts_model->get();
        $data['projects'] = $this->projects_model->get();
      }else{
        $data['contracts'] = $this->contracts_model->get('', ['tblcontracts.addedfrom' => get_staff_user_id()]);
        $data['projects'] = $this->projects_model->get('',  db_prefix() . 'projects.id IN (SELECT project_id FROM ' . db_prefix() . 'project_members WHERE staff_id=' . get_staff_user_id() . ')' );
      }

      $this->load->view('team_password_mgt/add_credit_card', $data);
    }
    /**
     * view credit card
     * @param id
     * @return view
    */
    public function view_credit_card($id = ''){
      if(!(get_permission('credit_card',$id) == 0) && !has_permission('team_password','','view_own') && !has_permission('team_password','','view') && !is_admin()){
        access_denied('team_password');
      }
      if($id != ''){
        $this->team_password_model->log_password_action($id,'credit_card','see');
        $data['title'] = _l('view_credit_card');
        $data['tab'] = $this->input->get('tab');
        $data['id'] = $id;
        $this->load->model('staff_model');
        $data['staffs'] = $this->staff_model->get();
        $data['credit_card'] = $this->team_password_model->get_credit_card($id); 
        $data['contact'] = $this->team_password_model->get_contact();
        $this->load->model('client_groups_model');
        $data['customer_groups'] = $this->client_groups_model->get_groups();
        $data['logs'] = $this->team_password_model->get_logs_password($id,'credit_card');
        $this->load->view('team_password_mgt/view_credit_card', $data);          
      }
    }
      /**
       * add email
       * @param id
       * @return view
      */
      public function add_email($id = '')
      {
        $data['title'] = _l('add_email');

        $cate = $this->input->get('cate');

        if($cate != 'all' && $cate != ''){
          $data['cate'] = $cate;
        }else{
          $data['cate'] = '';
        }

        if ($this->input->post()) {
          $message          = '';
          $data             = $this->input->post();
          if ($data['id'] == '') {
            if(!has_permission('team_password','','create') && !is_admin()){
              access_denied('team_password');
            }
            $insert_id = $this->team_password_model->add_email($data);
            if ($insert_id) {
              handle_item_password_file($insert_id,'tp_email');
              $success = true;
              $message = _l('added_successfully');
              set_alert('success', $message);
            }
            redirect(admin_url('team_password/team_password_mgt?cate='.$data['mgt_id'].'&type=email'));
          }else {
            if(!has_permission('team_password','','edit') && !is_admin()){
              access_denied('team_password');
            }
            handle_item_password_file($data['id'],'tp_email');
            $success = $this->team_password_model->update_email($data);
            if ($success) {
              $message = _l('updated_successfully');
              set_alert('success', $message);
            }
            redirect(admin_url('team_password/team_password_mgt?cate='.$data['mgt_id'].'&type=email'));
          }
          die;
        }
        $data['category'] = $this->team_password_model->get_category_management();
        if($id != ''){
          $data['title'] = _l('update_email');
          $data['email'] = $this->team_password_model->get_email($id);     
        }

        $this->load->model('projects_model');
        $this->load->model('contracts_model');

        if(is_admin()){
          $data['contracts'] = $this->contracts_model->get();
          $data['projects'] = $this->projects_model->get();
        }else{
          $data['contracts'] = $this->contracts_model->get('', ['tblcontracts.addedfrom' => get_staff_user_id()]);
          $data['projects'] = $this->projects_model->get('',  db_prefix() . 'projects.id IN (SELECT project_id FROM ' . db_prefix() . 'project_members WHERE staff_id=' . get_staff_user_id() . ')' );
        }

        $this->load->view('team_password_mgt/add_email', $data);
      }


    /**
     * email table
     * @param id
     * @return view
    */
    public function email_table($category)
    {
     if ($this->input->is_ajax_request()) {
      if($this->input->post()){
        if($category != 'all'){
          $category_filter  = $category;
        }else{
         $category_filter  = '';
       }

       $query = '';
       $str_cate = '';
       if($category_filter != ''){
        $cate_ids = get_recursive_cate($category_filter);
        if($cate_ids && count($cate_ids) > 0){
          foreach ($cate_ids as $s) {
            $str_cate = $str_cate . $s['id'].',';
          }
        }
        $str_cate = $str_cate. $category_filter;

        $query .= 'AND mgt_id IN ('.$str_cate.')';
      }else{
        if(!has_permission('team_password','','view') && !is_admin()){
          $ids = $this->team_password_model->list_cate_permission(get_staff_user_id());
          foreach($ids as $idc){
            $query .= ' OR mgt_id IN (select 
            id 
            from    (select * from '.db_prefix().'team_password_category
            order by '.db_prefix().'team_password_category.parent, '.db_prefix().'team_password_category.id) departments_sorted,
            (select @pv := '.$idc.') initialisation
            where   find_in_set(parent, @pv)
            and     length(@pv := concat(@pv, ",", id)) OR id = '.$idc.')';
          }
        }
      }
      $select = [
        'id',
        'id',
        'id',
        'id',
        'id',
        'id',
        'id',
        'id',

      ];
      $where              = [];

      if(!has_permission('team_password','','view') && !is_admin()){
        if($category_filter != ''){
          array_push($where, ' AND ((add_from = '.get_staff_user_id().' AND add_by = "staff") OR mgt_id IN ('.$str_cate.') OR '.get_staff_user_id().' IN (SELECT staff from '.db_prefix().'permission WHERE (obj_id = '.db_prefix().'tp_email.id AND type = "email") ))');
        }else{
          array_push($where, ' AND ((add_from = '.get_staff_user_id().' AND add_by = "staff") OR '.get_staff_user_id().' IN (SELECT staff from '.db_prefix().'permission WHERE (obj_id = '.db_prefix().'tp_email.id AND type = "email") ))');
        }
      }

      array_push($where, $query);


      $aColumns     = $select;
      $sIndexColumn = 'id';
      $sTable       = db_prefix() . 'tp_email';
      $join         = [];

      $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
        'id',
        'name',
        'pin',
        'credit_card_type',
        'card_number',
        'card_cvc', 
        'notice',
        'email_type',
        'auth_method',
        'host',
        'port',
        'smtp_auth_method',
        'smtp_host',
        'smtp_port',
        'smtp_user_name',
        'smtp_password',
        'password',
        'mgt_id',
        'enable_log',
        'add_from',

      ]);


      $output  = $result['output'];
      $rResult = $result['rResult'];
      foreach ($rResult as $aRow) {

        $row = [];
        $row[] = $aRow['id'];             
        $row[] = $aRow['name'];   
        $category_name = '';
        if($aRow['mgt_id']){
          $data_category = $this->team_password_model->get_category_management($aRow['mgt_id']); 
          if($data_category){
           $category_name = '<i class="fa '.$data_category->icon.'"></i> '.$data_category->category_name;
         }      
       }

       $row[] = $category_name;          
       $row[] = $aRow['email_type'];             
       $row[] = $aRow['host'];          
       $row[] = $aRow['port'];          
       $row[] = $aRow['notice'];        

       $option = '';
       if(is_admin()){
        $option .= '<a href="' . admin_url('team_password/view_email/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
        $option .= '<i class="fa fa-eye"></i>';
        $option .= '</a>';
        $option .= '<a href="' . admin_url('team_password/add_email/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
        $option .= '<i class="fa fa-pencil-square-o"></i>';
        $option .= '</a>';
        $password = AES_256_Decrypt($aRow['password']);
        $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
        $option .= '<i class="fa fa-copy"></i>';
        $option .= '</a>';
        $option .= '<a href="' . admin_url('team_password/delete_email/'.$aRow['id'].'/'.$category) . '" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'" >';
        $option .= '<i class="fa fa-remove"></i>';
        $option .= '</a>';
      }
      else{

        if(has_permission('team_password','','view') || $aRow['add_from'] == get_staff_user_id()){
          $option .= '<a href="' . admin_url('team_password/view_email/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
          $option .= '<i class="fa fa-eye"></i>';
          $option .= '</a>';

          if(has_permission('team_password','','edit') || $aRow['add_from'] == get_staff_user_id()){
            $option .= '<a href="' . admin_url('team_password/add_email/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
            $option .= '<i class="fa fa-pencil-square-o"></i>';
            $option .= '</a>';
          }
          $password = AES_256_Decrypt($aRow['password']);
          $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
          $option .= '<i class="fa fa-copy"></i>';
          $option .= '</a>';
        }else{

          if(get_permission('email',$aRow['id'],'r') == 1 &&!get_permission('email',$aRow['id'],'w') == 1){
            $option .= '<a href="' . admin_url('team_password/view_email/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
            $option .= '<i class="fa fa-eye"></i>';
            $option .= '</a>';
            $password = AES_256_Decrypt($aRow['password']);
            $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
            $option .= '<i class="fa fa-copy"></i>';
            $option .= '</a>';
          }
          elseif(get_permission('email',$aRow['id'],'rw') == 1 ||get_permission('email',$aRow['id'],'w') == 1){
            $option .= '<a href="' . admin_url('team_password/view_email/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
            $option .= '<i class="fa fa-eye"></i>';
            $option .= '</a>';
            $option .= '<a href="' . admin_url('team_password/add_email/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
            $option .= '<i class="fa fa-pencil-square-o"></i>';
            $option .= '</a>';
            $password = AES_256_Decrypt($aRow['password']);
            $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
            $option .= '<i class="fa fa-copy"></i>';
            $option .= '</a>';
          }
        }

        if(has_permission('team_password','','delete')){
          $option .= '<a href="' . admin_url('team_password/delete_email/'.$aRow['id'].'/'.$category) . '" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'" >';
          $option .= '<i class="fa fa-remove"></i>';
          $option .= '</a>';
        }
      }
      $row[] = $option; 
      $output['aaData'][] = $row;   
    }                                   


    echo json_encode($output);
    die();
  }
}
}
    /**
     * View email
     * @param id
     * @return view
     */
    public function view_email($id = ''){
      if(!(get_permission('email',$id) == 0 ) && !has_permission('team_password','','view_own') && !has_permission('team_password','','view') && !is_admin()){
        access_denied('team_password');
      }
      if($id != ''){
        $this->team_password_model->log_password_action($id,'email','see');
        $data['title'] = _l('view_email');
        $data['tab'] = $this->input->get('tab');
        $data['id'] = $id;
        $this->load->model('staff_model');
        $data['staffs'] = $this->staff_model->get();              
        $data['email'] = $this->team_password_model->get_email($id); 
        $data['contact'] = $this->team_password_model->get_contact();
        $this->load->model('client_groups_model');
        $data['customer_groups'] = $this->client_groups_model->get_groups();
        $data['logs'] = $this->team_password_model->get_logs_password($id,'email');
        $this->load->view('team_password_mgt/view_email', $data);
      }
    }

    /**
     * add server
     * @param id
     * @return view
    */
    public function add_server($id = '')
    {
      $data['title'] = _l('add_server');

      $cate = $this->input->get('cate');

      if($cate != 'all' && $cate != ''){
        $data['cate'] = $cate;
      }else{
        $data['cate'] = '';
      }

      if ($this->input->post()) {
        $message          = '';
        $data             = $this->input->post();
        if ($data['id'] == '') {
          if(!has_permission('team_password','','create') && !is_admin()){
            access_denied('team_password');
          }
          $insert_id = $this->team_password_model->add_server($data);
          if ($insert_id) {
            handle_item_password_file($insert_id,'tp_server');
            $success = true;
            $message = _l('added_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/team_password_mgt?cate='.$data['mgt_id'].'&type=server'));
        } else {
          if(!has_permission('team_password','','edit') && !is_admin()){
            access_denied('team_password');
          }
          handle_item_password_file($data['id'],'tp_server');
          $success = $this->team_password_model->update_server($data);
          if ($success) {
            $message = _l('updated_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/team_password_mgt?cate='.$data['mgt_id'].'&type=server'));
        }
        die;
      }
      $data['category'] = $this->team_password_model->get_category_management();
      if($id != ''){
        $data['title'] = _l('update_server');
        $data['server'] = $this->team_password_model->get_server($id);     
      }

      $this->load->model('projects_model');
      $this->load->model('contracts_model');

      if(is_admin()){
        $data['contracts'] = $this->contracts_model->get();
        $data['projects'] = $this->projects_model->get();
      }else{
        $data['contracts'] = $this->contracts_model->get('', ['tblcontracts.addedfrom' => get_staff_user_id()]);
        $data['projects'] = $this->projects_model->get('',  db_prefix() . 'projects.id IN (SELECT project_id FROM ' . db_prefix() . 'project_members WHERE staff_id=' . get_staff_user_id() . ')' );
      }

      $this->load->view('team_password_mgt/add_server', $data);
    }
    /**
     * view server
     * @param id
     * @return view
     */
    public function view_server($id = ''){
      if(!(get_permission('server',$id) == 0) && !has_permission('team_password','','view_own') && !has_permission('team_password','','view') && !is_admin()){
        access_denied('team_password');
      }
      if($id != ''){
        $this->team_password_model->log_password_action($id,'server','see');
        $data['title'] = _l('view_server');
        $data['tab'] = $this->input->get('tab');
        $data['id'] = $id;
        $this->load->model('staff_model');
        $data['staffs'] = $this->staff_model->get();              
        $data['server'] = $this->team_password_model->get_server($id); 
        $data['contact'] = $this->team_password_model->get_contact();
        $this->load->model('client_groups_model');
        $data['customer_groups'] = $this->client_groups_model->get_groups();
        $data['logs'] = $this->team_password_model->get_logs_password($id,'server');
        $this->load->view('team_password_mgt/view_server', $data);          
      }
    }
       /**
     * load data table 'tp_server'
     * @return json
     */
       public function server_table($category)
       {
         if ($this->input->is_ajax_request()) {
          if($this->input->post()){
           if($category != 'all'){
            $category_filter  = $category;
          }else{
           $category_filter  = '';
         }

         $query = '';
         $str_cate = '';
         if($category_filter != ''){
          $cate_ids = get_recursive_cate($category_filter);

          if($cate_ids && count($cate_ids) > 0){
            foreach ($cate_ids as $s) {
              $str_cate = $str_cate . $s['id'].',';
            }
          }
          $str_cate = $str_cate. $category_filter;

          $query .= 'AND mgt_id IN ('.$str_cate.')';
        }else{
          if(!has_permission('team_password','','view') && !is_admin()){
            $ids = $this->team_password_model->list_cate_permission(get_staff_user_id());
            foreach($ids as $idc){
              $query .= ' OR mgt_id IN (select 
              id 
              from    (select * from '.db_prefix().'team_password_category
              order by '.db_prefix().'team_password_category.parent, '.db_prefix().'team_password_category.id) departments_sorted,
              (select @pv := '.$idc.') initialisation
              where   find_in_set(parent, @pv)
              and     length(@pv := concat(@pv, ",", id)) OR id = '.$idc.')';
            }
          }
        }
        $select = [
          'id',
          'id',
          'id',
          'id',
          'id',
          'id',
          'id',

        ];
        $where              = [];

        if(!has_permission('team_password','','view') && !is_admin()){
          if($category_filter != ''){
            array_push($where, ' AND ((add_from = '.get_staff_user_id().' AND add_by = "staff") OR mgt_id IN ('.$str_cate.') OR '.get_staff_user_id().' IN (SELECT staff from '.db_prefix().'permission WHERE (obj_id = '.db_prefix().'tp_server.id AND type = "server") ))');
          }else{
            array_push($where, ' AND ((add_from = '.get_staff_user_id().' AND add_by = "staff") OR '.get_staff_user_id().' IN (SELECT staff from '.db_prefix().'permission WHERE (obj_id = '.db_prefix().'tp_server.id AND type = "server") ))');
          }
        }

        array_push($where, $query);

        $aColumns     = $select;
        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'tp_server';
        $join         = [];

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
          'id',
          'name',
          'user_name',
          'notice',
          'host',
          'port',
          'password',
          'mgt_id',
          'enable_log',
          'add_from',
        ]);


        $output  = $result['output'];
        $rResult = $result['rResult'];
        foreach ($rResult as $aRow) {

          $row = [];
          $row[] = $aRow['id'];             
          $row[] = $aRow['name'];             
          $category_name = '';
          if($aRow['mgt_id']){
            $data_category = $this->team_password_model->get_category_management($aRow['mgt_id']); 
            if($data_category){
             $category_name = '<i class="fa '.$data_category->icon.'"></i> '.$data_category->category_name;
           }      
         }

         $row[] = $category_name;           
         $row[] = $aRow['host'];          
         $row[] = $aRow['port'];          
         $row[] = $aRow['notice'];         


         $option = '';
         if(is_admin()){
          $option .= '<a href="' . admin_url('team_password/view_server/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
          $option .= '<i class="fa fa-eye"></i>';
          $option .= '</a>';
          $option .= '<a href="' . admin_url('team_password/add_server/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
          $option .= '<i class="fa fa-pencil-square-o"></i>';
          $option .= '</a>';
          $password = AES_256_Decrypt($aRow['password']);
          $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
          $option .= '<i class="fa fa-copy"></i>';
          $option .= '</a>';
          $option .= '<a href="' . admin_url('team_password/delete_server/'.$aRow['id'].'/'.$category) . '" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'" >';
          $option .= '<i class="fa fa-remove"></i>';
          $option .= '</a>';
        }
        else{

          if(has_permission('team_password','','view') || $aRow['add_from'] == get_staff_user_id()){
            $option .= '<a href="' . admin_url('team_password/view_server/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
            $option .= '<i class="fa fa-eye"></i>';
            $option .= '</a>';

            if(has_permission('team_password','','edit') || $aRow['add_from'] == get_staff_user_id()){
              $option .= '<a href="' . admin_url('team_password/add_server/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
              $option .= '<i class="fa fa-pencil-square-o"></i>';
              $option .= '</a>';
            }
            $password = AES_256_Decrypt($aRow['password']);
            $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
            $option .= '<i class="fa fa-copy"></i>';
            $option .= '</a>';
          }else{

            if(get_permission('server',$aRow['id'],'r') == 1 &&!get_permission('server',$aRow['id'],'w') == 1){
              $option .= '<a href="' . admin_url('team_password/view_server/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
              $option .= '<i class="fa fa-eye"></i>';
              $option .= '</a>';
              $password = AES_256_Decrypt($aRow['password']);
              $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
              $option .= '<i class="fa fa-copy"></i>';
              $option .= '</a>';
            }
            elseif(get_permission('server',$aRow['id'],'rw') == 1 ||get_permission('server',$aRow['id'],'w') == 1){
              $option .= '<a href="' . admin_url('team_password/view_server/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
              $option .= '<i class="fa fa-eye"></i>';
              $option .= '</a>';
              $option .= '<a href="' . admin_url('team_password/add_server/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
              $option .= '<i class="fa fa-pencil-square-o"></i>';
              $option .= '</a>';
              $password = AES_256_Decrypt($aRow['password']);
              $option .= '<a href="#" data-password="'.$password.'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
              $option .= '<i class="fa fa-copy"></i>';
              $option .= '</a>';
            }
          }

          if(has_permission('team_password','','delete')){
            $option .= '<a href="' . admin_url('team_password/delete_server/'.$aRow['id'].'/'.$category) . '" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'" >';
            $option .= '<i class="fa fa-remove"></i>';
            $option .= '</a>';
          }
        }

        $row[] = $option; 
        $output['aaData'][] = $row;   
      }                                   


      echo json_encode($output);
      die();
    }
  }
}
    /**
     * delete server
     * @param  id
     * @return redirect
     */
    public function delete_server($id = '',$cate)
    {
      if(!has_permission('team_password','','delete') && !is_admin()){
        access_denied('team_password');
      }
      $response = $this->team_password_model->delete_server($id);
      if($response == true){
        set_alert('success', _l('deleted', _l('category')));
      }
      else{
        set_alert('warning', _l('problem_deleting'));            
      }
      redirect(admin_url('team_password/team_password_mgt?cate='.$cate.'&type=server'));
    }
    /**
     * add software license
     * @param id
     * @return view
    */
    public function add_software_license($id = '')
    {
      $data['title'] = _l('add_software_license');

      $cate = $this->input->get('cate');

      if($cate != 'all' && $cate != ''){
        $data['cate'] = $cate;
      }else{
        $data['cate'] = '';
      }

      if ($this->input->post()) {
        $message          = '';
        $data             = $this->input->post();
        if ($data['id'] == '') {
          if(!has_permission('team_password','','create') && !is_admin()){
            access_denied('team_password');
          }
          $insert_id = $this->team_password_model->add_software_license($data);
          if ($insert_id) {
            handle_item_password_file($insert_id,'tp_software_license');
            $success = true;
            $message = _l('added_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/team_password_mgt?cate='.$data['mgt_id'].'&type=software_license'));
        } else {
          if(!has_permission('team_password','','edit') && !is_admin()){
            access_denied('team_password');
          }
          handle_item_password_file($data['id'],'tp_software_license');
          $success = $this->team_password_model->update_software_license($data);
          if ($success) {
            $message = _l('updated_successfully');
            set_alert('success', $message);
          }
          redirect(admin_url('team_password/team_password_mgt?cate='.$data['mgt_id'].'&type=software_license'));
        }
        die;
      }
      $data['category'] = $this->team_password_model->get_category_management();
      if($id != ''){
        $data['title'] = _l('update_software_license');
        $data['software_license'] = $this->team_password_model->get_software_license($id);  
      }

      $this->load->model('projects_model');
      $this->load->model('contracts_model');

      if(is_admin()){
        $data['contracts'] = $this->contracts_model->get();
        $data['projects'] = $this->projects_model->get();
      }else{
        $data['contracts'] = $this->contracts_model->get('', ['tblcontracts.addedfrom' => get_staff_user_id()]);
        $data['projects'] = $this->projects_model->get('',  db_prefix() . 'projects.id IN (SELECT project_id FROM ' . db_prefix() . 'project_members WHERE staff_id=' . get_staff_user_id() . ')' );
      }

      $this->load->view('team_password_mgt/add_software_license', $data);
    }
    /**
     * view software license detail
     * @param id
     * @return view
     */
    public function view_software_license($id = ''){
      if(!(get_permission('software_license',$id) == 0) && !has_permission('team_password','','view_own') && !has_permission('team_password','','view') && !is_admin()){
        access_denied('team_password');
      }
      if($id != ''){
        $this->team_password_model->log_password_action($id,'software_license','see');
        $data['title'] = _l('view_software_license');
        $data['tab'] = $this->input->get('tab');
        $data['id'] = $id;
        $this->load->model('staff_model');
        $data['staffs'] = $this->staff_model->get();              
        $data['software_license'] = $this->team_password_model->get_software_license($id); 
        $data['contact'] = $this->team_password_model->get_contact();
        $this->load->model('client_groups_model');
        $data['customer_groups'] = $this->client_groups_model->get_groups();
        $data['logs'] = $this->team_password_model->get_logs_password($id,'software_license');
        $this->load->view('team_password_mgt/view_software_license', $data);
      }
    }
    /**
     * delete software_license
     * @param  id
     * @return redirect
     */
    public function delete_software_license($id = '',$cate)
    {
      if(!has_permission('team_password','','delete') && !is_admin()){
        access_denied('team_password');
      }
      $response = $this->team_password_model->delete_software_license($id);
      if($response == true){
        set_alert('success');
      }
      else{
        set_alert('warning');            
      }
      redirect(admin_url('team_password/team_password_mgt?cate='.$cate.'&type=software_license'));
    }
    /**
     * software license table
     * @param  id
     * @return json
     */
    public function software_license_table( $category)
    {
     if ($this->input->is_ajax_request()) {
      if($this->input->post()){
        if($category != 'all'){
          $category_filter  = $category;
        }else{
         $category_filter  = '';
       }

       $query = '';
       $str_cate = '';
       if($category_filter != ''){
        $cate_ids = get_recursive_cate($category_filter);
        if($cate_ids && count($cate_ids) > 0){
          foreach ($cate_ids as $s) {
            $str_cate = $str_cate . $s['id'].',';
          }
        }
        $str_cate = $str_cate. $category_filter;
        $query .= 'AND mgt_id IN ('.$str_cate.')';
      }else{
        if(!has_permission('team_password','','view') && !is_admin()){
          $ids = $this->team_password_model->list_cate_permission(get_staff_user_id());
          foreach($ids as $idc){
            $query .= ' OR mgt_id IN (select 
            id 
            from    (select * from '.db_prefix().'team_password_category
            order by '.db_prefix().'team_password_category.parent, '.db_prefix().'team_password_category.id) departments_sorted,
            (select @pv := '.$idc.') initialisation
            where   find_in_set(parent, @pv)
            and     length(@pv := concat(@pv, ",", id)) OR id = '.$idc.')';
          }
        }
      }
      $select = [
        'id',
        'id',
        'id',
        'id',
        'id',
        'id',        
      ];
      $where              = [];

      if(!has_permission('team_password','','view') && !is_admin()){
        if($category_filter != ''){
          array_push($where, ' AND ((add_from = '.get_staff_user_id().' AND add_by = "staff") OR mgt_id IN ('.$str_cate.') OR '.get_staff_user_id().' IN (SELECT staff from '.db_prefix().'permission WHERE (obj_id = '.db_prefix().'tp_software_license.id AND type = "software_license") ))');
        }else{
          array_push($where, ' AND ((add_from = '.get_staff_user_id().' AND add_by = "staff") OR '.get_staff_user_id().' IN (SELECT staff from '.db_prefix().'permission WHERE (obj_id = '.db_prefix().'tp_software_license.id AND type = "software_license") ))');
        }
      }


      array_push($where, $query);

      $aColumns     = $select;
      $sIndexColumn = 'id';
      $sTable       = db_prefix() . 'tp_software_license';
      $join         = [];

      $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
        'id',
        'name',
        'version',
        'url',
        'license_key',
        'notice',
        'password',
        'enable_log',
        'mgt_id',
        'add_from',
      ]);


      $output  = $result['output'];
      $rResult = $result['rResult'];
      foreach ($rResult as $aRow) {

        $row = [];
        $row[] = $aRow['id'];             
        $row[] = $aRow['name'];  
        $category_name = '';
        if($aRow['mgt_id']){
          $data_category = $this->team_password_model->get_category_management($aRow['mgt_id']); 
          if($data_category){
           $category_name = '<i class="fa '.$data_category->icon.'"></i> '.$data_category->category_name;
         }      
       }

       $row[] = $category_name;             
       $row[] = $aRow['version'];             

       $row[] = $aRow['notice'];             

       $option = '';
       if(is_admin()){
        $option .= '<a href="' . admin_url('team_password/view_software_license/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
        $option .= '<i class="fa fa-eye"></i>';
        $option .= '</a>';
        $option .= '<a href="' . admin_url('team_password/add_software_license/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
        $option .= '<i class="fa fa-pencil-square-o"></i>';
        $option .= '</a>';

        $option .= '<a href="#" data-password="'.$aRow['license_key'].'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
        $option .= '<i class="fa fa-copy"></i>';
        $option .= '</a>';

        $option .= '<a href="' . admin_url('team_password/delete_software_license/'.$aRow['id'].'/'.$category) . '" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'" >';
        $option .= '<i class="fa fa-remove"></i>';
        $option .= '</a>';
      }
      else{
        if(has_permission('team_password','','view') || $aRow['add_from'] == get_staff_user_id()){
          $option .= '<a href="' . admin_url('team_password/view_software_license/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
          $option .= '<i class="fa fa-eye"></i>';
          $option .= '</a>';
          if(has_permission('team_password','','edit') || $aRow['add_from'] == get_staff_user_id()){
            $option .= '<a href="' . admin_url('team_password/add_software_license/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
            $option .= '<i class="fa fa-pencil-square-o"></i>';
            $option .= '</a>';
          }
          $option .= '<a href="#" data-password="'.$aRow['license_key'].'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
          $option .= '<i class="fa fa-copy"></i>';
          $option .= '</a>';
        }else{
          if(get_permission('software_license',$aRow['id'],'r') == 1 &&!get_permission('software_license',$aRow['id'],'w') == 1){
            $option .= '<a href="' . admin_url('team_password/view_software_license/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
            $option .= '<i class="fa fa-eye"></i>';
            $option .= '</a>';
            $option .= '<a href="#" data-password="'.$aRow['license_key'].'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
            $option .= '<i class="fa fa-copy"></i>';
            $option .= '</a>';
          }
          elseif(get_permission('software_license',$aRow['id'],'rw') == 1 ||get_permission('software_license',$aRow['id'],'w') == 1){
            $option .= '<a href="' . admin_url('team_password/view_software_license/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('view').'" >';
            $option .= '<i class="fa fa-eye"></i>';
            $option .= '</a>';
            $option .= '<a href="' . admin_url('team_password/add_software_license/'.$aRow['id']) . '" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('edit').'" >';
            $option .= '<i class="fa fa-pencil-square-o"></i>';
            $option .= '</a>';
            $option .= '<a href="#" data-password="'.$aRow['license_key'].'" class="btn btn-default btn-icon" data-toggle="tooltip" data-placement="top" title="'._l('copy_password_to_clipboard').'" onclick="copyToClipboard(this)">';
            $option .= '<i class="fa fa-copy"></i>';
            $option .= '</a>';
          }
        } 

        if(has_permission('team_password','','delete')){
          $option .= '<a href="' . admin_url('team_password/delete_software_license/'.$aRow['id'].'/'.$category) . '" class="btn btn-danger btn-icon _delete" data-toggle="tooltip" data-placement="top" title="'._l('delete').'" >';
          $option .= '<i class="fa fa-remove"></i>';
          $option .= '</a>';
        }
      }
      $row[] = $option; 
      $output['aaData'][] = $row;     

    }

    echo json_encode($output);
    die();
  }
}
}
    /**
     * delete email
     * @param  id
     * @return redirect
     * 
    */
    public function delete_email($id = '',$cate)
    { 
      if(!has_permission('team_password','','delete') && !is_admin()){
        access_denied('team_password');
      }
      $response = $this->team_password_model->delete_email($id);
      if($response == true){
        set_alert('success', _l('deleted', _l('category')));
      }
      else{
        set_alert('warning', _l('problem_deleting'));            
      }
      redirect(admin_url('team_password/team_password_mgt?cate='.$cate.'&type=email'));
    }

    /**
     * { report }
     */
    public function report(){
      if(!has_permission('team_password','','view') && !is_admin()){
        access_denied('team_password');
      }
      $this->load->model('staff_model');
      $data['contact'] = $this->team_password_model->get_contact();
      $data['category'] = $this->team_password_model->get_category_management();
      $data['staffs'] = $this->staff_model->get(); 
      $data['tab'] = $this->input->get('tab');
      $data['ef_time'] = $this->input->get('ef_time');
      $data['title'] = _l('statistical');
      $this->load->view('report/manage',$data);
    }

    /**
   * { table permission rp }
   */
    public function table_permission_rp(){
      $this->app->get_table_data(module_views_path('team_password', 'report/table_permission_rp'));
    }

   /**
   * { table share rp }
   */
   public function table_share_rp(){
    $this->app->get_table_data(module_views_path('team_password', 'report/table_share_rp'));
  }

  /**
   * { items relate contract }
   *
   * @param       $contract_id  The contract identifier
   */
  public function items_relate($contract_id){
    if(!has_permission('team_password','','view') && !is_admin()){
      access_denied('team_password');
    }
    $data['items']['bank_account'] = $this->team_password_model->get_item_relate_contract($contract_id,'bank_account');
    $data['items']['credit_card'] = $this->team_password_model->get_item_relate_contract($contract_id,'credit_card');
    $data['items']['email'] = $this->team_password_model->get_item_relate_contract($contract_id,'email');
    $data['items']['normal'] = $this->team_password_model->get_item_relate_contract($contract_id,'normal');
    $data['items']['server'] = $this->team_password_model->get_item_relate_contract($contract_id,'server');
    $data['items']['software_license'] = $this->team_password_model->get_item_relate_contract($contract_id,'software_license');
    $data['title'] = _l('items_relate');
    $this->load->view('contract_items/list_items',$data);
  }

  /**
   * Adds a share by cate.
   *
   * @param      string  $cate   The cate
   * @param      $type   The type
   */
  public function add_share_by_cate($cate,$type){
    if(!has_permission('team_password','','create') && !is_admin()){
      access_denied('team_password');
    }

    if($this->input->post()){
      $result = 0;
      $data = $this->input->post();
      $data['datecreator'] = date('Y-m-d H:i:s');
      $data['creator'] = get_staff_user_id();

      if($data['shareid'] == ''){
        $ids = $this->team_password_model->get_tree_cate_ids($cate);
        unset($data['shareid']);
        if(count($ids) > 0){
          foreach($ids as $cgr){
            $normal = $this->team_password_model->get_item_by_cate($cgr,'normal');
            $bank_account = $this->team_password_model->get_item_by_cate($cgr,'bank_account');
            $credit_card = $this->team_password_model->get_item_by_cate($cgr,'credit_card');
            $email = $this->team_password_model->get_item_by_cate($cgr,'email');
            $server = $this->team_password_model->get_item_by_cate($cgr,'server');
            $software_license = $this->team_password_model->get_item_by_cate($cgr,'software_license');


            foreach($normal as $nor){
              $data['type'] = 'normal';
              $data['share_id'] = $nor['id'];
              $add_nor = $this->team_password_model->add_share($data);
              if($add_nor){
                $result++;
              }
            }

            foreach($bank_account as $bnk){
              $data['type'] = 'bank_account';
              $data['share_id'] = $bnk['id'];
              $add_bnk = $this->team_password_model->add_share($data);
              if($add_bnk){
                $result++;
              }
            }

            foreach($credit_card as $cre){
              $data['type'] = 'credit_card';
              $data['share_id'] = $cre['id'];
              $add_cre = $this->team_password_model->add_share($data);
              if($add_cre){
                $result++;
              }
            }

            foreach($email as $em){
              $data['type'] = 'email';
              $data['share_id'] = $em['id'];
              $add_em = $this->team_password_model->add_share($data);
              if($add_em){
                $result++;
              }
            }

            foreach($server as $sev){
              $data['type'] = 'server';
              $data['share_id'] = $sev['id'];
              $add_sev = $this->team_password_model->add_share($data);
              if($add_sev){
                $result++;
              }
            }

            foreach($software_license as $sof){
              $data['type'] = 'software_license';
              $data['share_id'] = $sof['id'];
              $add_sof = $this->team_password_model->add_share($data);
              if($add_sof){
                $result++;
              }
            }

            $data['type'] = 'category';
            $data['share_id'] = $cgr;
            $share_cate = $this->team_password_model->add_share_cate($data);
            if($share_cate){
              $result++;
            }

          }
        }
        if($result > 0){

          $message = _l('added_successfully');
          set_alert('success', $message);
        }else{
          $message = _l('added_failed');
          set_alert('warning', $message);
        }

        redirect(admin_url('team_password/team_password_mgt?cate='.$cate.'&type='.$type));
      }else{
        $data['id'] = $data['shareid'];
        unset($data['shareid']);
        $updated = $this->team_password_model->update_share_cate($data);
        if($updated){
          $message = _l('updated');
          set_alert('success', $message);
        }else{
          $message = _l('update_failed');
          set_alert('warning', $message);
        }
        redirect(admin_url('team_password/team_password_mgt?cate='.$cate.'&type='.$type));
      }
    }

  }

  /**
   * Adds a permission by cate.
   *
   * @param      string  $cate   The cate
   * @param      $type   The type
   */
  public function add_permission_by_cate($cate,$type){
    if(!has_permission('team_password','','create') && !is_admin()){
      access_denied('team_password');
    }

    if($this->input->post()){
      $data = $this->input->post();
      if($data['id'] == ''){
        $ids = $this->team_password_model->get_tree_cate_ids($cate);
        $rs = 0;
        if(count($ids) > 0){
          foreach($ids as $cgr){
            $data['obj_id'] = $cgr;
            $data['type'] = 'category';
            $result = $this->team_password_model->add_permission($data);
            if($result){
              $rs++;
            }
          }

        }else{
          $data['obj_id'] = $cate;
          $data['type'] = 'category';
          $result = $this->team_password_model->add_permission($data);
          if($result){
            $rs++;
          }
        } 

        if($rs > 0){

          $message = _l('added_successfully');
          set_alert('success', $message);
        }else{
          $message = _l('added_failed');
          set_alert('warning', $message);
        }

        redirect(admin_url('team_password/team_password_mgt?cate='.$cate.'&type='.$type));
      }else{
        $id = $data['id'];
        unset($data['id']);
        $updated = $this->team_password_model->update_permission($id,$data);
        if($updated){
          $message = _l('updated');
          set_alert('success', $message);
        }else{
          $message = _l('update_failed');
          set_alert('warning', $message);
        }

        redirect(admin_url('team_password/team_password_mgt?cate='.$cate.'&type='.$type));
      }
    }
  }

  /**
   * { setting }
   */
  public function setting(){
    if(!is_admin()){
      access_denied('team_password');
    }
    $data['title'] = _l('setting_tp');
    $this->load->view('manage_setting',$data);
  }

  /**
   * { setting form }
   */
  public function setting_form(){
    if( !is_admin()){
      access_denied('team_password');
    }

    if($this->input->post()){
      $data = $this->input->post();
      if($data['security_key'] == null || $data['security_key'] == ''){
        $data['security_key'] == 'g8934fuw9843hwe8rf9*5bhv';
      }
    }else{
      $data['security_key'] = get_option('team_password_security');
    }

    $update = $this->team_password_model->team_password_setting($data);

    if($update == true){
      set_alert('success', _l('updated_successfully'));
    }
    redirect(admin_url('team_password/setting'));

  }
  
  /**
     * { file item }
     *
     * @param        $id      The identifier
     * @param        $rel_id  The relative identifier
     */
  public function file_item($id, $rel_id, $type)
  {
    $data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
    $data['current_user_is_admin']             = is_admin();
    $data['file'] = $this->team_password_model->get_file($id, $rel_id);
    $data['types'] = $type;
    if (!$data['file']) {
      header('HTTP/1.0 404 Not Found');
      die;
    }
    $this->load->view('team_password_mgt/_file', $data);
  }

    /**
     * { delete file attachment }
     *
     * @param  $id     The identifier
     */
    public function delete_file_item($id,$type)
    {
      $this->load->model('misc_model');
      $file = $this->misc_model->get_file($id);
      if ($file->staffid == get_staff_user_id() || is_admin()) {
        echo html_entity_decode($this->team_password_model->delete_file_item($id,$type));
      } else {
        header('HTTP/1.0 400 Bad error');
        echo _l('access_denied');
        die;
      }
    }

    /**
     * { dashboard }
     * @return view
     */
    public function dashboard(){
      $data['title'] = _l('tp_dashboard');
      $data['tp_count'] = $this->team_password_model->get_count_password_dashboard();
      $data['password_by_cate'] = json_encode($this->team_password_model->count_password_by_category());
      $data['share_by_type'] = json_encode($this->team_password_model->count_share_by_type());
      $data['your_password_shared'] = $this->team_password_model->get_your_password_shared();
      $data['password_expire'] = $this->team_password_model->get_password_expire();
      $this->load->view('dashboard/tp_dashboard',$data); 
    }

    /**
     * { clear logs }
     *
     * @param        $rel_id    The relative identifier
     * @param       $rel_type  The relative type
     */
    public function clear_logs($rel_id, $rel_type){
      if(!has_permission('team_password','','delete') && !is_admin()){
        access_denied('team_password');
      }

      if(!$rel_id){
        redirect(admin_url('team_password/view_'.$rel_type.'/'.$rel_id));
      }

      $response = $this->team_password_model->clear_logs($rel_id, $rel_type);
      if($response == true){
        set_alert('success', _l('deleted', _l('category')));
      }
      else{
        set_alert('warning', _l('problem_deleting'));            
      }
      redirect(admin_url('team_password/view_'.$rel_type.'/'.$rel_id));
    }

    /**
     * import file xlsx commodity
     * @return json
     */
    public function import_file_xlsx_password() {
      if (!is_admin() && !has_permission('team_password', '', 'create')) {
        access_denied(_l('team_password'));
      }

      $total_row_false = 0;
      $total_rows_data = 0;
      $dataerror = 0;
      $total_row_success = 0;
      $total_rows_data_error = 0;
      $filename='';

      if ($this->input->post()) {
        /*delete file old before export file*/
        $path_before = PASSWORD_ERROR_TP.'FILE_ERROR_PASSWORD'.get_staff_user_id().'.xlsx';
        if(file_exists($path_before)){
          unlink(PASSWORD_ERROR_TP.'FILE_ERROR_PASSWORD'.get_staff_user_id().'.xlsx');
        }

        if (isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
                //do_action('before_import_leads');

                // Get the temp file path
          $tmpFilePath = $_FILES['file_csv']['tmp_name'];
                // Make sure we have a filepath
          if (!empty($tmpFilePath) && $tmpFilePath != '') {
            $tmpDir = TEMP_FOLDER . '/' . time() . uniqid() . '/';

            if (!file_exists(TEMP_FOLDER)) {
              mkdir(TEMP_FOLDER, 0755);
            }

            if (!file_exists($tmpDir)) {
              mkdir($tmpDir, 0755);
            }

                    // Setup our new file path
            $newFilePath = $tmpDir . $_FILES['file_csv']['name'];

            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
              $import_result = true;
              $rows = [];

              $objReader = new PHPExcel_Reader_Excel2007();
              $objReader->setReadDataOnly(true);
              $objPHPExcel = $objReader->load($newFilePath);
              $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
              $sheet = $objPHPExcel->getActiveSheet();

                        //innit  file exel error start

              $dataError = new PHPExcel();
              $dataError->setActiveSheetIndex(0);
                        //create header file

                        // add style to the header
              $styleArray = array(
                'font' => array(
                  'bold' => true,

                ),

                'borders' => array(
                  'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                  ),
                ),
                'fill' => array(

                  'rotation' => 90,
                  'startcolor' => array(
                    'argb' => 'FFA0A0A0',
                  ),
                  'endcolor' => array(
                    'argb' => 'FFFFFFFF',
                  ),
                ),
              );

                        // set the names of header cells
              $dataError->setActiveSheetIndex(0)

              ->setCellValue("A1", "(*)" . _l('name'))
              ->setCellValue("B1", "(*)" . _l('type'))
              ->setCellValue("C1", _l('user_name'))
              ->setCellValue("D1", _l('password'))
              ->setCellValue("E1", _l('url'))
              ->setCellValue("F1", _l('bank_code'))
              ->setCellValue("G1", _l('bank_name'))
              ->setCellValue("H1", _l('pin'))
              ->setCellValue("I1", _l('host'))
              ->setCellValue("J1", _l('port'))
              ->setCellValue("K1", _l('smtp_host'))
              ->setCellValue("L1", _l('smtp_port'))
              ->setCellValue("M1", _l('smtp_user_name'))
              ->setCellValue("N1", _l('smtp_password'))
              ->setCellValue("O1", _l('credit_card_type'))
              ->setCellValue("P1", _l('card_number'))
              ->setCellValue("Q1", _l('card_cvc'))
              ->setCellValue("R1", _l('license_key'))
              ->setCellValue("S1", _l('category'))
              ->setCellValue("T1", _l('email_type'))
              ->setCellValue("U1", _l('auth_method'))
              ->setCellValue("S1", _l('version'));


              /*set style for header*/
              $dataError->getActiveSheet()->getStyle('A1:W1')->applyFromArray($styleArray);

                        // auto fit column to content

              foreach (range('A', 'W') as $columnID) {
                $dataError->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);

              }

              $dataError->getActiveSheet()->getStyle('A1:W1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
              $dataError->getActiveSheet()->getStyle('A1:W1')->getFill()->getStartColor()->setARGB('29bb04');
                        // Add some data
              $dataError->getActiveSheet()->getStyle('A1:W1')->getFont()->setBold(true);
              $dataError->getActiveSheet()->getStyle('A1:W1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

              /*set header middle alignment*/
              $dataError->getActiveSheet()->getStyle('A1:W1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

              $dataError->getActiveSheet()->getStyle('A1:W1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

              /*set row1 height*/
              $dataError->getActiveSheet()->getRowDimension('1')->setRowHeight(40);

                        //init file error end

                        // start row write 2
              $numRow = 2;
              $total_rows = 0;

              $total_rows_actualy = 0;

                        //get data for compare

              foreach ($rowIterator as $row) {
                $rowIndex = $row->getRowIndex();
                if ($rowIndex > 1) {
                  $rd = array();
                  $flag = 0;
                  $flag2 = 0;
                  $string_error = '';

                  $flag_id_cate;

                  $value_cell_name = $sheet->getCell('A' . $rowIndex)->getValue();
                  $value_cell_type = $sheet->getCell('B' . $rowIndex)->getValue();

                  $value_cell_cate = $sheet->getCell('S' . $rowIndex)->getValue();

                  /*check null*/
                  if (is_null($value_cell_name) == true) {
                    $string_error .= _l('name') . _l('not_yet_entered');
                    $flag = 1;
                  }

                  if (is_null($value_cell_type) == true) {
                    $string_error .= _l('type') . _l('not_yet_entered');
                    $flag2 = 1;
                  }else{
                    if($value_cell_type != "bank_account" && $value_cell_type != "normal" && $value_cell_type != "credit_card" && $value_cell_type != "email" && $value_cell_type != "server" && $value_cell_type != "software_license"){
                      $string_error .= _l('type') . _l('invalid');
                      $flag2 = 1;
                    }
                  }

                  if($this->input->post('cate') != 'all'){
                    $flag_id_cate = $this->input->post('cate');
                  }else{
                    $this->db->where('category_name', $value_cell_cate);
                    $category = $this->db->get(db_prefix().'team_password_category')->row();
                    if($category){
                      $flag_id_cate = $category->id;
                    }else{
                      $flag_id_cate = '';
                    }
                  }

                  if (($flag == 1) || ($flag2 == 1)) {
                    $dataError->getActiveSheet()->setCellValue('A' . $numRow, $sheet->getCell('A' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('B' . $numRow, $sheet->getCell('B' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('C' . $numRow, $sheet->getCell('C' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('D' . $numRow, $sheet->getCell('D' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('E' . $numRow, $sheet->getCell('E' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('F' . $numRow, $sheet->getCell('F' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('G' . $numRow, $sheet->getCell('G' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('H' . $numRow, $sheet->getCell('H' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('I' . $numRow, $sheet->getCell('I' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('J' . $numRow, $sheet->getCell('J' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('K' . $numRow, $sheet->getCell('K' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('M' . $numRow, $sheet->getCell('M' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('N' . $numRow, $sheet->getCell('N' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('O' . $numRow, $sheet->getCell('O' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('P' . $numRow, $sheet->getCell('P' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('Q' . $numRow, $sheet->getCell('Q' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('R' . $numRow, $sheet->getCell('R' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('R' . $numRow, $sheet->getCell('R' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('S' . $numRow, $sheet->getCell('S' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('T' . $numRow, $sheet->getCell('T' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('U' . $numRow, $sheet->getCell('U' . $rowIndex)->getValue());
                    $dataError->getActiveSheet()->setCellValue('V' . $numRow, $sheet->getCell('V' . $rowIndex)->getValue());


                    $dataError->getActiveSheet()->setCellValue('W' . $numRow, $string_error)->getStyle('W' . $numRow)->applyFromArray($styleArray);

                    $numRow++;
                    $total_rows_data_error++;
                  }

                  if (($flag == 0) && ($flag2 == 0)) {

                    $rd['name'] = $sheet->getCell('A' . $rowIndex)->getValue();
                    $rd['type'] = $sheet->getCell('B' . $rowIndex)->getValue();
                    $rd['user_name'] = $sheet->getCell('C' . $rowIndex)->getValue();
                    $rd['password'] = $sheet->getCell('D' . $rowIndex)->getValue();
                    $rd['url'] = $sheet->getCell('E' . $rowIndex)->getValue();
                    $rd['bank_code'] = $sheet->getCell('F' . $rowIndex)->getValue();
                    $rd['bank_name'] = $sheet->getCell('G' . $rowIndex)->getValue();
                    $rd['pin'] = $sheet->getCell('H' . $rowIndex)->getValue();
                    $rd['host'] = $sheet->getCell('I' . $rowIndex)->getValue();
                    $rd['port'] = $sheet->getCell('J' . $rowIndex)->getValue();
                    $rd['smtp_host'] = $sheet->getCell('K' . $rowIndex)->getValue();
                    $rd['smtp_port'] = $sheet->getCell('L' . $rowIndex)->getValue();
                    $rd['smtp_user_name'] = $sheet->getCell('M' . $rowIndex)->getValue();
                    $rd['smtp_password'] = $sheet->getCell('N' . $rowIndex)->getValue();
                    $rd['credit_card_type'] = $sheet->getCell('O' . $rowIndex)->getValue();
                    $rd['card_number'] = $sheet->getCell('P' . $rowIndex)->getValue();
                    $rd['card_cvc'] = $sheet->getCell('Q' . $rowIndex)->getValue();
                    $rd['license_key'] = $sheet->getCell('R' . $rowIndex)->getValue();
                    $rd['mgt_id'] = isset($flag_id_cate) ? $flag_id_cate : '';
                    $rd['email_type'] = $sheet->getCell('T' . $rowIndex)->getValue();
                    $rd['auth_method'] = $sheet->getCell('U' . $rowIndex)->getValue();
                    $rd['version'] = $sheet->getCell('V' . $rowIndex)->getValue();
                  }

                  if (get_staff_user_id() != '' && $flag == 0 && $flag2 == 0) {
                    $rows[] = $rd;
                    $result_value = $this->team_password_model->import_xlsx_password($rd);
                    if ($result_value) {
                      $total_rows_actualy++;
                    }
                  }

                  $total_rows++;
                  $total_rows_data++;
                }

              }

              if ($total_rows_actualy != $total_rows) {
                $total_rows = $total_rows_actualy;
              }


              $total_rows = $total_rows;
              $data['total_rows_post'] = count($rows);
              $total_row_success = count($rows);
              $total_row_false = $total_rows - (int) count($rows);
              $dataerror = $dataError;
              $message = 'Not enought rows for importing';

              if(($total_rows_data_error > 0) || ($total_row_false != 0)){
                $objWriter = new PHPExcel_Writer_Excel2007($dataError);

                $filename = 'FILE_ERROR_PASSWORD' .get_staff_user_id().strtotime(date('Y-m-d H:i:s')). '.xlsx';
                $objWriter->save(str_replace($filename, PASSWORD_IMPORT_ITEM_ERROR.$filename, $filename));

                $filename = PASSWORD_IMPORT_ITEM_ERROR.$filename;


              }

              $import_result = true;
              @delete_dir($tmpDir);

            }

          } else {
            set_alert('warning', _l('import_upload_failed'));
          }
        }

      }
      echo json_encode([
        'message' =>'Not enought rows for importing',
        'total_row_success' => $total_row_success,
        'total_row_false' => $total_rows_data_error,
        'total_rows' => $total_rows_data,
        'site_url' => site_url(),
        'staff_id' => get_staff_user_id(),
        'total_rows_data_error' => $total_rows_data_error,
        'filename' => $filename,
      ]);

    }


  /**
     * team password delete bulk action
     * @return
     */
  public function tp_delete_bulk_action()
  {
    if (!is_staff_member()) {
      ajax_access_denied();
    }
    $total_deleted = 0;
    if ($this->input->post()) {

      $ids                   = $this->input->post('ids');
      $rel_type                   = $this->input->post('rel_type');
      /*check permission*/
      switch ($rel_type) {
        case 'share':
        if (!has_permission('team_password', '', 'delete') && !is_admin()) {
          access_denied('share');
        }
        break;

        default:
        break;
      }

      /*delete data*/
      if ($this->input->post('mass_delete')) {
        if (is_array($ids)) {
          foreach ($ids as $id) {

            switch ($rel_type) {
              case 'share':
              if ($this->team_password_model->delete_share($id)) {
                $total_deleted++;
                break;
              }else{
                break;
              }

              default:

              break;
            }
          }
        }
        /*return result*/
        switch ($rel_type) {
          case 'share':
          set_alert('success', _l('total_deleted'). ": " .$total_deleted);
          break;   

          default:
          break;

        }
      }
    }
  }
}