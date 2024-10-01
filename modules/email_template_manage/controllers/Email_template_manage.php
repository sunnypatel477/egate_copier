<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Email_template_manage_model $email_template_manage_model
 */
class Email_template_manage extends AdminController
{
    public $table       = 'email_template_manage_templates';

    public function __construct()
    {

        parent::__construct();

        $this->table = db_prefix().'email_template_manage_templates';

        $this->check_template_manage_db();

        $path = get_upload_path_by_type('email_template');

        _maybe_create_upload_path($path);

        $this->load->model('email_template_manage_model');

    }


    public function test()
    {
        $this->email_template_manage_model->send_templates();
    }

    public function index()
    {

        $template_id = $this->input->get("template_id");

        $data["template_id"] = $template_id;
        $data["title"] = _l('email_template_manage');

        $this->load->view('v_manage',$data);

    }

    public function lists()
    {

        if( $this->input->is_ajax_request() )
        {

            $sTable = $this->table;

            $select = [

                'id' ,

                'template_name' ,

                'template_subject' ,

                'related_type' ,

                'status' ,

            ];

            $where = [];

            $join = [];

            $sIndexColumn = 'id';

            if ( !is_admin() )
            {
                $where[] = " AND ( is_public = 1 OR added_staff_id = ".get_staff_user_id()." ) ";
            }


            $result = data_tables_init($select, $sIndexColumn, $sTable, $join, $where );

            $output  = $result['output'];

            $rResult = $result['rResult'];


            foreach ($rResult as $aRow){

                $row = [];

                $numberOutput = '<div class="row-options">';

                    $numberOutput .= '<a href="#" onclick="fnc_template_dlg( '. $aRow['id'] .' , 0 ); return false;" >' . _l('edit') . '</a>';

                    $numberOutput .= ' | <a class="text-success" href="#" onclick="fnc_template_attach( '. $aRow['id'] .' ); return false;"> '. _l('email_template_manage_add_file') .' </a>';

                    $numberOutput .= ' | <a class="text-warning" href="#" onclick="fnc_template_dlg( '. $aRow['id'] .' , 1 ); return false;"> '. _l('email_template_manage_duplicate') .' </a>';

                    $numberOutput .= ' | <a class="text-danger _delete" href="'.admin_url('email_template_manage/delete/'.$aRow['id']).'" > '._l('delete').' </a>';

                $numberOutput .= '</div>';

                $row[] = '<a>' . $aRow['id'] . '</a>';

                $row[]  = $aRow['template_name'].$numberOutput;

                $row[]  = $aRow['template_subject'];

                $row[]  = _l( $aRow['related_type'] );

                $toggleActive = '<div class="onoffswitch" data-toggle="tooltip" data-title="Status">

                            <input type="checkbox" data-switch-url="' . admin_url() . 'email_template_manage/change_status" name="onoffswitch" 
                                    class="onoffswitch-checkbox" id="snack_' . $aRow['id'] . '" 
                                    data-id="' . $aRow['id'] . '" ' . ($aRow['status'] == 1 ? 'checked' : '') . '>
                        
                            <label class="onoffswitch-label" for="snack_' . $aRow['id'] . '"></label>
                        
                            </div>';



                // For exporting

                $toggleActive .= '<span class="hide">' . ($aRow['status'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';


                $row[] = $toggleActive;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);

            die;

        }

    }

    public function detail()
    {

        if( $this->input->is_ajax_request() )
        {

            $detail_id = $this->input->post('record_id');

            $data = [] ;

            $data["template_id"]    = 0 ;

            $this->email_template_manage_model->include_merge_libraries();

            $data["data_merge_fields"] = $this->email_template_manage_model->get_libraries_merge_fields();


            $data['trigger_merge'] = [

                'client'        => [ 'client' ] ,
                'lead'          => [ 'lead' ] ,
                'invoice'       => [ 'invoice' , 'client' , 'staff' ] ,
                'staff'         => [ 'task' , 'project' , 'invoice' , 'client' , 'estimate' , 'contract' , 'lead' , 'proposal' , 'staff' ] ,
                'project'       => [ 'project' , 'client' ] ,
                'task'          => [ 'task' , 'project' , 'invoice' , 'client' , 'estimate' , 'contract' , 'lead' , 'proposal' , 'staff' ] ,
                'proposal'      => [ 'proposal' , 'client' , 'lead' ] ,
                'contract'      => [ 'contract' , 'client' ] ,
                'estimate'      => [ 'estimate' , 'client' , 'staff' ] ,
                'estimate_request'  => [ 'estimate_request' , 'staff' ] ,

            ];

            if ( !is_admin() )
                $this->db->where(" ( is_public = 1 OR added_staff_id = ".get_staff_user_id()." ) ",null,false);

            $data["data"] = $this->db->select('*')
                                    ->from( $this->table )
                                    ->where('id',$detail_id)
                                    ->get()
                                    ->row();

            if( !empty( $data["data"] ) )
            {

                $data["template_id"]    = $detail_id ;

                $data["data"]->attachments = $this->get_attachments( $detail_id );

            }

            $content = $this->load->view('v_dialog',$data,true);

            echo json_encode( [ 'content_html' => $content , 'data' => $data["data"] ]);

            die;

        }

    }

    public function save()
    {

        if( $this->input->post() )
        {

            $id   = $this->input->post('id');

            $post_data  = $this->input->post();
            $data       = [];

            $data["template_name"]      = $post_data["template_name"];
            $data["template_content"]   = $this->input->post('template_content',false);
            $data["template_subject"]   = $post_data["template_subject"];
            $data["related_type"]       = $post_data["related_type"];

            if ( !empty( $post_data['is_public'] ) )
                $data['is_public'] = 1;
            else
                $data['is_public'] = 0;

            if( empty( $id ) )
            {

                $data['date'] = date('Y-m-d H:i:s');

                $data['added_staff_id'] = get_staff_user_id();

                $this->db->insert($this->table,$data);

                $message = _l('added_successfully', _l('email_template_manage') );

            }
            else
            {

                $this->db->where('id',$id)->update($this->table,$data);

                $message = _l('updated_successfully', _l('email_template_manage' ) );

            }

            set_alert( 'success' , $message );

        }

        redirect( admin_url('email_template_manage') );

    }

    /**
     * change template status
     */
    public function change_status($id, $status)
    {

        if ($this->input->is_ajax_request()) {

            $this->db->where('id', $id);

            $this->db->update($this->table, [

                'status' => $status,

            ]);

            if ($this->db->affected_rows() > 0)
                return true;
            else
                return false;

        }

    }

    public function delete( $id = 0 )
    {

        $this->db->where('id',$id)->delete($this->table);

        set_alert('success' , _l('deleted', _l('email_template_manage') ) );

        redirect( admin_url('email_template_manage') );

    }


    public function delete_attachment($id)
    {
        $file = $this->misc_model->get_file($id);

        if ($file->staffid == get_staff_user_id() || is_admin())
        {

            $attachment = $this->get_attachments('', $id);

            $deleted    = false;

            if ($attachment)
            {

                if (empty($attachment->external))
                {
                    unlink(get_upload_path_by_type('email_template') . $attachment->rel_id . '/' . $attachment->file_name);
                }

                $this->db->where('id', $attachment->id);
                $this->db->delete(db_prefix() . 'files');

                if ($this->db->affected_rows() > 0)
                {
                    $deleted = true;
                }

                if (is_dir(get_upload_path_by_type('email_template') . $attachment->rel_id))
                {
                    // Check if no attachments left, so we can delete the folder also
                    $other_attachments = list_files(get_upload_path_by_type('email_template') . $attachment->rel_id);
                    if (count($other_attachments) == 0) {
                        // okey only index.html so we can delete the folder also
                        delete_dir(get_upload_path_by_type('email_template') . $attachment->rel_id);
                    }

                }

            }

            echo $deleted;

        }
        else
            ajax_access_denied();


    }

    public function get_attachments($template_id, $id = '')
    {

        return $this->email_template_manage_model->get_attachments( $template_id , $id);

    }



    /**
     * Mail timer functions
     */
    public function timer()
    {

        $data["title"] = _l('email_template_manage_timer');

        $this->load->view('v_mail_timer',$data);

    }

    public function timer_detail()
    {

        $record_id = $this->input->post('record_id') ;

        $data['duplicate']  = $this->input->post('is_duplicate');

        $data['templates']  = $this->email_template_manage_model->get_all_templates_array();

        // Customer groups
        $this->load->model('clients_model');
        $data['groups'] = $this->clients_model->get_groups();


        $this->load->model('leads_model');
        $data['statuses'] = $this->leads_model->get_status();
        $data['sources']  = $this->leads_model->get_source();


        if( !empty( $record_id ) )
            $data['data']   = $this->db->select('*')->from(db_prefix().'email_template_manage_timer')->where('id',$record_id)->get()->row();

        $content = $this->load->view('v_mail_setting' , $data , true );

        echo json_encode( [ 'content_html' => $content ] );

        die();

    }

    public function timer_list()
    {

        if( $this->input->is_ajax_request() )
        {

            $sTable = db_prefix().'email_template_manage_timer';

            $select = [

                $sTable.'.id as id' ,

                'setting_name' ,

                'template_name' ,

                'sending_date' ,

                'sending_hour' ,

                'send_status' ,

                $sTable.'.status as status' ,

            ];

            $where = [];

            $join = [
                'LEFT JOIN ' . $this->table . ' ON ' . $sTable . '.template_id = ' . $this->table . '.id',
            ];

            $sIndexColumn = 'id';

            $result = data_tables_init($select, $sIndexColumn, $sTable, $join, $where , [ 'sending_hour' , 'template_id ' ] );

            $output  = $result['output'];

            $rResult = $result['rResult'];


            foreach ($rResult as $aRow){

                $row = [];

                $numberOutput = '<div class="row-options">';

                    $numberOutput .= '<a href="#" onclick="fnc_template_dlg( '. $aRow['id'] .' , 0 ); return false;" >' . _l('edit') . '</a>';

                    $numberOutput .= ' | <a class="text-warning" href="#" onclick="fnc_template_dlg( '. $aRow['id'] .' , 1 ); return false;"> '._l('email_template_manage_duplicate').' </a>';

                    $numberOutput .= ' | <a class="text-danger _delete" href="'.admin_url('email_template_manage/timer_delete/'.$aRow['id']).'" > '._l('delete').' </a>';

                $numberOutput .= '</div>';

                $row[] = '<a>' . $aRow['id'] . '</a>';

                $row[]  = $aRow['setting_name'].$numberOutput;

                $template_link = admin_url("email_template_manage?template_id=".$aRow["template_id"]);
                $row[]  = "<a href='$template_link' target='_blank'>".$aRow['template_name']."</a>";

                $row[]  = _d($aRow['sending_date']);

                $row[]  = $aRow['sending_hour'];

                $send_status_text = $this->get_send_status_text( $aRow["send_status"] );

                if ( !empty( $aRow["send_status"] ) && $aRow["send_status"] != 9 )
                    $send_status_text .= ' | <a class="text-primary" href="#" onclick="fnc_template_sending_dlg( '. $aRow['id'] .' ); return false;"> '._l('email_template_manage_view_logs').' </a>';
                else
                    $send_status_text .= ' | <a class="text-success" href="#" onclick="fnc_template_send_now( '. $aRow['id'] .' ); return false;"> '._l('email_template_manage_send_now').' </a>';

                $row[]  = $send_status_text;

                $toggleActive = '<div class="onoffswitch" data-toggle="tooltip" data-title="Status">

                                    <input type="checkbox" data-switch-url="' . admin_url() . 'email_template_manage/timer_change_status" name="onoffswitch" 
                                            class="onoffswitch-checkbox" id="snack_' . $aRow['id'] . '" 
                                            data-id="' . $aRow['id'] . '" ' . ($aRow['status'] == 1 ? 'checked' : '') . '>
                                
                                    <label class="onoffswitch-label" for="snack_' . $aRow['id'] . '"></label>
                            
                                </div>';



                // For exporting

                $toggleActive .= '<span class="hide">' . ($aRow['status'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';


                $row[] = $toggleActive;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);

            die;

        }

    }

    public function get_send_status_text( $status_id = 0 )
    {

        if ( empty( $status_id )  )
            return '<span class="text-warning">'. _l('email_template_manage_status_pending') .'</span>';

        else if ( $status_id == 1 )
            return '<span class="text-success">'. _l('email_template_manage_status_send') .'</span>';

        else if ( $status_id == 2 )
            return '<span class="text-warning">'. _l('email_template_manage_status_partially_send') .'</span>';

        else if ( $status_id == 3 )
            return '<span class="text-danger">'. _l('email_template_manage_status_error') .'</span>';

        else if ( $status_id == 9 )
            return _l('email_template_manage_status_not_found');


    }

    /**
     * @note mail timer setting saving
     */
    public function save_mail_setting()
    {

        if( $this->input->post() )
        {

            $table = db_prefix().'email_template_manage_timer';

            $id   = $this->input->post('id');

            $post_data  = $this->input->post();

            $post_data['send_status']   = 0;
            $post_data["client_groups"] = !empty( $post_data["client_groups"] ) ? json_encode( $post_data["client_groups"] ): null ;
            $post_data["lead_statuses"] = !empty( $post_data["lead_statuses"] ) ? json_encode( $post_data["lead_statuses"] ): null ;
            $post_data["lead_sources"]  = !empty( $post_data["lead_sources"] )  ? json_encode( $post_data["lead_sources"] ) : null ;
            $post_data["clients"]       = !empty( $post_data["clients"] )       ? json_encode( $post_data["clients"] )      : null ;
            $post_data["not_clients"]   = !empty( $post_data["not_clients"] )   ? json_encode( $post_data["not_clients"] )  : null ;
            $post_data["leads"]         = !empty( $post_data["leads"] )         ? json_encode( $post_data["leads"] )        : null ;

            $post_data['sending_date']  = to_sql_date( $post_data['sending_date'] );

            if( empty( $id ) )

                $this->db->insert( $table , $post_data );

            else

                $this->db->where('id',$id)->update( $table , $post_data );

        }

        redirect( admin_url('email_template_manage/timer') );

    }

    public function timer_change_status($id, $status)

    {

        if ($this->input->is_ajax_request()) {

            $this->db->where('id', $id);

            $this->db->update(db_prefix().'email_template_manage_timer', [

                'status' => $status,

            ]);

            if ($this->db->affected_rows() > 0)
                return true;
            else
                return false;

        }

    }

    public function timer_delete( $id = 0 )
    {

        $this->db->where('id',$id)->delete(db_prefix().'email_template_manage_timer');

        set_alert('success' , _l('deleted', _l('email_template_manage_mail_timer') ) );

        redirect(admin_url('email_template_manage/timer') );

    }

    public function mail_send_logs()
    {

        $record_id = $this->input->post('record_id') ;

        $content = $this->load->view('v_mail_sending_logs' , [ 'record_id' => $record_id ] , true );

        echo json_encode( [ 'content_html' => $content ] );

        die();

    }


    public function mail_send_now()
    {


        if ($this->input->is_ajax_request())
        {

            $timer_id = $this->input->post('record_id');

            $this->email_template_manage_model->send_templates( $timer_id );

        }


    }

    public function mail_send_now_iframe($timer_id){

        $this->email_template_manage_model->send_templates( $timer_id , true);

        echo _l("email_template_manage_finish");
    }


    public function mail_clear_record()
    {


        if ($this->input->is_ajax_request())
        {

            $template_id= $this->input->post('template_id');

            $clear_type = $this->input->post('clear_type');

            $send_date  = to_sql_date( $this->input->post('send_date') );

            $this->db->set('send_status',0)->set('sending_date',$send_date)->where('id',$template_id)->update(db_prefix().'email_template_manage_timer');

            if ( $clear_type == 2 ) // only error

                $this->db->where('mail_id',$template_id)->where('status',2)->delete(db_prefix().'email_template_manage_sending_logs');

            else // all

                $this->db->where('mail_id',$template_id)->delete(db_prefix().'email_template_manage_sending_logs');

        }


    }


    /**
     * Version 1.0.2
     */
    public function send_mail_modal( $rel_type = '' , $rel_id = 0 )
    {


        if ($this->input->is_ajax_request())
        {

            $data["rel_type"]   = $rel_type;

            $data["rel_id"]     = $rel_id;

            $data['email']      = "";

            $data["templates"] = $this->email_template_manage_model->get_templates( $rel_type );

            $data["smtp_settings"]  = $this->email_template_manage_model->get_smtp_settings();

            list( $data['emails'] ) = $this->email_template_manage_model->send_mail_modal_info( $rel_type , $rel_id );

            $this->load->view('v_send_mail_modal', $data);

        }

    }

    public function send_mail()
    {

        if ( $this->input->post() )
        {

            $mail_to_arr    = $this->input->post('mail_to_arr');

            if ( !empty( $mail_to_arr ) )
            {

                foreach ( $mail_to_arr as $email )
                {

                    $success = $this->email_template_manage_model->send_mail( $email );

                }

            }
            else
                $success = $this->email_template_manage_model->send_mail();


            if ( $success )
                set_alert('success',_l('email_template_manage_send_mail_successful'));
            else
                set_alert('danger',_l('email_template_manage_send_mail_failed'));


        }

        redirect($_SERVER['HTTP_REFERER']);

    }


    public function template_content()
    {

        $template_id= $this->input->post('template_id');

        $rel_type  = $this->input->post('rel_type');

        $rel_id    = $this->input->post('rel_id');


        $template_data = $this->db->select('')->from($this->table)->where('id',$template_id)->get()->row();


        if( !empty( $template_data ) )
        {

            $this->email_template_manage_model->include_merge_libraries();

            $rel_data = $this->email_template_manage_model->get_merge_rel_data( $rel_type , $rel_id );

            $merge_fields = $this->email_template_manage_model->get_merge_fields( $rel_type , $rel_data , true );

            foreach ( $merge_fields as $key => $val )
            {

                if ( stripos( $template_data->template_content , $key) !== false && !empty( $val ) )
                {

                    $template_data->template_content = str_ireplace($key, $val, $template_data->template_content );

                }
                else
                {

                    $template_data->template_content = str_ireplace($key, '', $template_data->template_content );

                }

                if ( stripos( $template_data->template_subject , $key) !== false && !empty( $val ) )
                {

                    $template_data->template_subject = str_ireplace($key, $val, $template_data->template_subject );

                }
                else
                {
                    $template_data->template_subject = str_ireplace($key, '', $template_data->template_subject );

                }

            }

            $template_data->attachment_html = "";

            $attachments = $this->get_attachments( $template_id );

            if( !empty( $attachments ) )
            {

                $attachment_html = "<hr class='hr-panel-separator' />";

                foreach ( $attachments as $attachment )
                {

                    $attachment_html .= '<div class="mbot15 row" data-attachment-id="'. $attachment['id'].'">';

                    $attachment_html .= '<input type="hidden" name="attachment_template[]" value="'.$attachment['id'].'">';

                    $attachment_html .= '<div class="col-md-8"> <div class="pull-left">';

                    $attachment_html .= '<i class="'. get_mime_class($attachment['filetype']).'"> </i>';

                    $attachment_html .= '</div>'.$attachment['file_name'].'<br />';

                    $attachment_html .= '<small class="text-muted">'. $attachment['filetype'] .'</small>';

                    $attachment_html .= '</div>';

                    $attachment_html .= '</div>';

                }

                $template_data->attachment_html = $attachment_html;

            }


            echo json_encode( [
                'success' => true ,
                'template_data' => $template_data
            ] );

        }
        else
            echo json_encode( [ 'success' => false ] );

    }


    #mail log
    public function mail_log()
    {

        $data["title"] = _l('email_template_manage_email_log');

        $data["templates"]  = $this->db->select("id , template_name")->from($this->table)->where("status" , 1)->get()->result_array();

        $data['staff']      = $this->db->select('staffid, firstname, lastname')->from(db_prefix().'staff')->where('active',1)->get()->result_array();

        $data['related_types']  = $this->email_template_manage_model->get_rel_types();

        $this->load->view('v_mail_log',$data);

    }

    public function mail_log_lists(){

        if( $this->input->is_ajax_request() )
        {

            $rel_id   = $this->input->post("rel_id");
            $rel_type = $this->input->post("rel_type");

            $sTable = db_prefix().'email_template_manage_mail_logs';

            $select = [

                'id' ,

                'CONCAT(firstname," ", lastname) as staff',

            ];

            if ( empty( $rel_id ) )
            {

                $select = array_merge( $select ,  [

                    'company_name' ,

                    'company_email' ,

                    '( SELECT smtp.company_name FROM '.db_prefix().'email_template_smtp_settings smtp WHERE smtp.id = '.$sTable.'.smtp_setting_id ) as smtp_name' ,

                    '( SELECT temp.template_name FROM '.db_prefix().'email_template_manage_templates temp WHERE temp.id = '.$sTable.'.template_id ) as template_name' ,

                    'send_rel_type' ,

                ] ) ;

            }

            $select = array_merge( $select ,  [

                'mail_subject' ,

                'date' ,

                'status' ,

                'opened'

            ] );

            $where = [];


            if (!empty($rel_id))
                $where[] = "AND $sTable.rel_id = $rel_id";


            if (!empty($rel_type))
                $where[] = "AND $sTable.rel_type = '$rel_type'";

            $from_date  = to_sql_date( $this->input->post('from_date') );
            $to_date    = to_sql_date( $this->input->post('to_date') );
            $template_id    = $this->input->post('template_id');
            $staff_id       = $this->input->post('staff_id');
            $send_rel_type  = $this->input->post('send_rel_type');

            if( !empty( $from_date ) )
                $where[] = "AND DATE( date ) >= '$from_date'";

            if( !empty( $to_date ) )
                $where[] = "AND DATE( date ) <= '$to_date'";

            if( !empty( $template_id ) )
                $where[] = "AND template_id = $template_id";

            if( !empty( $staff_id ) )
                $where[] = "AND $sTable.added_staff_id = $staff_id";

            if( !empty( $send_rel_type ) )
                $where[] = "AND $sTable.send_rel_type = '$send_rel_type' ";

            $join = ['LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . $sTable . '.added_staff_id'];


            $sIndexColumn = 'id';

            $result = data_tables_init($select, $sIndexColumn, $sTable, $join, $where , [ 'rel_type' , 'rel_id' , 'date_opened' , 'send_rel_id' , 'template_id']);

            $output  = $result['output'];

            $rResult = $result['rResult'];


            $all_list = 0;

            if ( empty( $rel_id ) )
                $all_list = 1;

            foreach ($rResult as $aRow){

                $row = [];

                $href_delete = admin_url("email_template_manage/log_delete/" . $aRow['id']);

                $numberOutput = '<div class="row-options" data-id=" '.$aRow['id'].' ">';

                $numberOutput .= '<a href="#" onclick="fnc_mail_log_detail( '. $aRow['id'] .' , '.$all_list.' ); return false;" >' . _l('view') . '</a>';

                $numberOutput .= ' | <a href="' . $href_delete . '" class="_delete text-danger" >' . _l('delete') . '</a>';

                $numberOutput .= '</div>';

                $row[] =  $aRow['id'].$numberOutput;

                $row[] = $aRow['staff'];

                if ( empty( $rel_id ) )
                {

                    if ($aRow['rel_type'] == "lead")

                        $href = admin_url('leads/index/' . $aRow['rel_id']);

                    elseif ($aRow['rel_type'] == "customer")

                        $href = admin_url('clients/client/' . $aRow['rel_id']);


                    elseif ($aRow['rel_type'] == "staff")

                        $href = admin_url('staff/member/' . $aRow['rel_id']);


                    $row[]  = '<a href="'.$href.'">' . $aRow['company_name'] . '</a>';

                    $row[]  = $aRow['company_email'];


                    /**
                     * @Version 1.1.5
                     */

                    $row[] = $aRow['smtp_name'];

                    $template_link = admin_url("email_template_manage?template_id=".$aRow["template_id"]);
                    $row[]  = "<a href='$template_link' target='_blank'>".$aRow['template_name']."</a>";

                    $send_rel_type = '';

                    if ( !empty( $aRow['send_rel_id'] ) )
                    {

                        if ( $aRow['send_rel_type'] == 'customer' )
                            $send_rel_type = _l( 'client' );
                        else
                            $send_rel_type = _l( $aRow['send_rel_type'] );


                        $rel_type_link = $this->email_template_manage_model->get_rel_type_links( $aRow['send_rel_type'] , $aRow['send_rel_id'] );

                        if ( !empty( $rel_type_link ) )
                        {

                            $send_rel_type = "<a $rel_type_link  target='_blank' > $send_rel_type </a>";

                        }

                    }

                    $row[] = $send_rel_type;

                    // smtp_setting_id template_id send_rel_type send_rel_id


                }

                $row[]  = $aRow['mail_subject'];

                $row[] = _d($aRow['date']);

                $row[] = $aRow["status"] == 1 ? "<span class='text-success'>"._l('email_template_manage_success')."</span>" : "<span class='text-danger'>"._l('email_template_manage_status_error')."</span>" ;

                if ( $aRow['opened'] == 1 )
                    $row[] = '<span class="label label-success">
                                <i class="fa-regular fa-clock text-has-action tw-mr-1" data-toggle="tooltip" data-title="'._dt( $aRow['date_opened'] ).'" data-original-title="" title=""></i> 
                                '._l('email_template_manage_opened').'
                            </span>';
                else
                    $row[] = '';

                $output['aaData'][] = $row;
            }

            echo json_encode($output);

            die;

        }

    }

    public function mail_log_detail(){

        if( $this->input->is_ajax_request() )
        {

            $detail_id = $this->input->post('record_id');

            $data = [] ;

            $data["data"] = $this->db->select('*')
                ->from( db_prefix().'email_template_manage_mail_logs' )
                ->where('id',$detail_id)
                ->get()
                ->row();

            $data["all_list"] = $this->input->post('all_list');

            if (!empty($data))
                $data["data"]->status = $data["data"]->status == 1 ? "<span class='text-success'>"._l('email_template_manage_success')."</span>" : "<span class='text-danger'>"._l('email_template_manage_status_error')."</span>" ;

            $content = $this->load->view('v_mail_log_dialog',$data,true);

            echo json_encode( [ 'content_html' => $content , 'data' => $data["data"] ]);

            die;

        }

    }

    public function mail_log_detail_frame( $detail_id = 0 )
    {

        $data = $this->db->select('content')
                                ->from( db_prefix().'email_template_manage_mail_logs' )
                                ->where('id',$detail_id)
                                ->get()
                                ->row();

        echo "<p>". ( !empty( $data->content ) ? $data->content : '' ) ."</p>";

    }

    public function log_delete($id = 0){

        $this->db->where('id',$id)->delete(db_prefix().'email_template_manage_mail_logs');

        set_alert('success' , _l('deleted', _l('email_template_manage_email_log') ) );

        redirect(admin_url('email_template_manage/mail_log') );

    }

    public function mail_log_clear(){

        if (!is_admin())
        {
            access_denied('Clear activity log');
        }

        $this->db->empty_table(db_prefix() . 'email_template_manage_mail_logs');

        redirect(admin_url('email_template_manage/mail_log'));

    }

    /**
     * @Version 1.1.0
     * @note Perfex System Mail Settings
     */
    public function system_templates()
    {

        $data["title"] = _l('email_template_manage_system_templates');

        $data["system_templates"] = $this->db->select('type,slug,name')
                                            ->where('active',1)
                                            ->where('language', 'english')
                                            ->order_by('type')
                                            ->get(db_prefix() . 'emailtemplates')
                                            ->result();


        $data["active_templates"] = [];

        $active_templates = $this->db->select('template_slug')->from(db_prefix().'email_template_manage_system_templates')->where('status',1)->get()->result();

        if ( !empty( $active_templates ) )
        {

            foreach ( $active_templates as $active_template)
            {

                $data["active_templates"][] = $active_template->template_slug;

            }

        }

        $this->load->view('v_system_templates',$data);

    }


    public function system_template_all( $status = 0 )
    {

        $table_name = db_prefix().'email_template_manage_system_templates';

        if ( $status == 1 ) // active all
        {

            $system_templates = $this->db->select('type,slug,name')
                                        ->where('active',1)
                                        ->where('language', 'english')
                                        ->order_by('type')
                                        ->get(db_prefix() . 'emailtemplates')
                                        ->result();


            foreach ( $system_templates as $system_template )
            {


                if ( total_rows( $table_name , [ 'template_slug' => $system_template->slug ] ) )
                {

                    $this->db->set('status',1)
                            ->where('template_slug',$system_template->slug)
                            ->update( $table_name );

                }
                else
                {

                    $this->db->set('status',1)
                            ->set('template_slug',$system_template->slug)
                            ->insert( $table_name );
                }


            }

        }
        else
        {

            $this->db->set('status',0)
                ->where('status',1)
                ->update( db_prefix().'email_template_manage_system_templates' );

        }


        redirect($_SERVER['HTTP_REFERER']);

    }


    public function system_template_change( $status = 0 )
    {

        $slug = $this->input->get('slug');

        if ( !empty( $slug ) )
        {

            $table_name = db_prefix().'email_template_manage_system_templates';


            if ( $status == 1 )
            {

                if ( total_rows( $table_name , [ 'template_slug' => $slug ] ) )
                {

                    $this->db->set('status',1)
                        ->where('template_slug',$slug)
                        ->update( $table_name );

                }
                else
                {

                    $this->db->set('status',1)
                        ->set('template_slug',$slug)
                        ->insert( $table_name );
                }

            }
            else
            {

                $this->db->set('status',0)
                    ->where('template_slug',$slug)
                    ->update( db_prefix().'email_template_manage_system_templates' );

            }

        }

        redirect($_SERVER['HTTP_REFERER']);

    }


    /**
     * @version 1.1.1
     * Mail Triggers
     */
    public function trigger()
    {

        $data["title"] = _l('email_template_manage_reminder');

        $this->load->view('v_mail_trigger',$data);

    }

    public function trigger_detail()
    {

        $record_id = $this->input->post('record_id') ;

        $data['duplicate']  = $this->input->post('is_duplicate');

        $data['templates']  = $this->email_template_manage_model->get_all_templates_array();;

        if( !empty( $record_id ) )
            $data['data']   = $this->db->select('*')->from(db_prefix().'email_template_manage_triggers')->where('id',$record_id)->get()->row();

        if ( empty( $data['data'] ) )
            $record_id = 0;

        $data['record_id'] = $record_id;

        $content = $this->load->view('v_mail_trigger_detail' , $data , true );

        echo json_encode( [ 'content_html' => $content ] );

        die();

    }

    public function trigger_list()
    {

        if( $this->input->is_ajax_request() )
        {

            $sTable = db_prefix().'email_template_manage_triggers';

            $select = [

                $sTable.'.id as id' ,

                'trigger_name' ,

                'template_name' ,

                'rel_type' ,

                $sTable.'.status as status' ,

            ];

            $where = [];

            $join = [
                'LEFT JOIN ' . $this->table . ' ON ' . $sTable . '.template_id = ' . $this->table . '.id',
            ];

            $sIndexColumn = 'id';

            $result = data_tables_init($select, $sIndexColumn, $sTable, $join, $where , [ 'template_id' ] );

            $output  = $result['output'];

            $rResult = $result['rResult'];


            foreach ($rResult as $aRow){

                $row = [];

                $numberOutput = '<div class="row-options">';

                $numberOutput .= '<a href="#" onclick="fnc_template_dlg( '. $aRow['id'] .' , 0 ); return false;" >' . _l('edit') . '</a>';

                $numberOutput .= ' | <a class="text-warning" href="#" onclick="fnc_template_dlg( '. $aRow['id'] .' , 1 ); return false;"> '._l('email_template_manage_duplicate').' </a>';

                $numberOutput .= ' | <a class="text-danger _delete" href="'.admin_url('email_template_manage/trigger_delete/'.$aRow['id']).'" > '._l('delete').' </a>';

                $numberOutput .= '</div>';

                $row[] = '<a>' . $aRow['id'] . '</a>';

                $row[]  = $aRow['trigger_name'].$numberOutput;

                $template_link = admin_url("email_template_manage?template_id=".$aRow["template_id"]);
                $row[]  = "<a href='$template_link' target='_blank'>".$aRow['template_name']."</a>";

                $row[]  = _l($aRow['rel_type']);


                $toggleActive = '<div class="onoffswitch" data-toggle="tooltip" data-title="Status">

                                    <input type="checkbox" data-switch-url="' . admin_url() . 'email_template_manage/trigger_change_status" name="onoffswitch" 
                                            class="onoffswitch-checkbox" id="snack_' . $aRow['id'] . '" 
                                            data-id="' . $aRow['id'] . '" ' . ($aRow['status'] == 1 ? 'checked' : '') . '>
                                
                                    <label class="onoffswitch-label" for="snack_' . $aRow['id'] . '"></label>
                            
                                </div>';



                // For exporting

                $toggleActive .= '<span class="hide">' . ($aRow['status'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';


                $row[] = $toggleActive;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);

            die;

        }

    }

    public function trigger_change_status($id, $status)

    {

        if ($this->input->is_ajax_request()) {

            $this->db->where('id', $id);

            $this->db->update(db_prefix().'email_template_manage_triggers', [

                'status' => $status,

            ]);

            if ($this->db->affected_rows() > 0)
                return true;
            else
                return false;

        }

    }

    public function trigger_delete( $id = 0 )
    {

        $this->db->where('id',$id)->delete(db_prefix().'email_template_manage_triggers');

        set_alert('success' , _l('deleted', _l('email_template_manage_reminder_field') ) );

        redirect(admin_url('email_template_manage/trigger') );

    }

    /**
     * Trigger save
     */
    public function save_trigger()
    {

        if ( $this->input->post() )
        {

            $data = $this->input->post();

            $id = $data['id'];

            $db_data = [
                'trigger_name'  => $data['trigger_name'] ,

                'template_id'   => $data['template_id'] ,

                'sending_hour'  => $data['sending_hour'] ,

                'rel_type'      => $data['rel_type'] ,
            ];

            if ( !empty( $data['send_to_staff'] ) )
                $db_data['staff_active'] = 1;
            else
                $db_data['staff_active'] = 0;

            if ( !empty( $data['send_to_client'] ) )
                $db_data['client_active'] = 1;
            else
                $db_data['client_active'] = 0;


            $options = [];

            if ( !empty( $data['status'] ) )
                $options['status'] =  $data['status'] ;

            if ( !empty( $data['priority'] ) )
                $options['priority'] =  $data['priority'] ;

            if ( isset( $data['record_date'] ) )
                $options['record_date'] =  $data['record_date'] ;

            if ( isset( $data['record_day'] ) )
                $options['record_day'] =  $data['record_day'] ;


            $db_data['options'] = json_encode( $options );

            if ( !empty( $id ) )
            {

                $this->db->where('id',$id)->update( db_prefix().'email_template_manage_triggers' , $db_data );

                $message = _l('updated_successfully', _l('email_template_manage_reminder'));

            }
            else
            {

                $this->db->insert( db_prefix().'email_template_manage_triggers' , $db_data );

                $message = _l('added_successfully', _l('email_template_manage_reminder'));

            }


            set_alert( 'success' , $message );

        }

        redirect( admin_url('email_template_manage/trigger') );

    }

    /**
     * Trigger rel type contents
     */
    public function rel_type_content( $record_id = 0 , $rel_type = "" )
    {

        $data['rel_type'] = $rel_type;

        $data['record_data'] = $this->db->select('*')
                                        ->from(db_prefix().'email_template_manage_triggers')
                                        ->where('id',$record_id)
                                        ->where('rel_type',$rel_type)
                                        ->get()->row();

        $data['date_option_days']   = [ 60 , 45 , 30 , 15 , 7 , 3 , 1 ];



        if ( $rel_type == 'invoice' )
        {

            $this->load->model('invoices_model');

            $data['status']     = $this->invoices_model->get_statuses();

            $this->load->view( 'v_mail_trigger_rel_type_invoice' , $data );

        }
        elseif ( $rel_type == 'project' )
        {

            $this->load->model('projects_model');

            $data['status']     = $this->projects_model->get_project_statuses();

            $this->load->view( 'v_mail_trigger_rel_type_project' , $data );

        }
        elseif ( $rel_type == 'task' )
        {

            $this->load->model('tasks_model');

            $data['status']     = $this->tasks_model->get_statuses();

            $data['priorities'] = get_tasks_priorities();

            $this->load->view( 'v_mail_trigger_rel_type_task' , $data );

        }
        elseif ( $rel_type == 'proposal' )
        {

            $this->load->model('proposals_model');

            $data['status']     = $this->proposals_model->get_statuses();

            $this->load->view( 'v_mail_trigger_rel_type_proposal' , $data );

        }
        elseif ( $rel_type == 'estimate' )
        {

            $this->load->model('estimates_model');

            $data['status']     = $this->estimates_model->get_statuses();

            $this->load->view( 'v_mail_trigger_rel_type_estimate' , $data );

        }
        elseif ( $rel_type == 'contract' )
        {

            $this->load->model('contracts_model');

            $data['status']     = $this->contracts_model->get_contract_types();

            $this->load->view( 'v_mail_trigger_rel_type_contract' , $data );

        }
        else
            echo $rel_type;


    }


    /**
     * @Version 1.1.5
     * Smtp settings
     */
    public function smtp_settings()
    {

        $data["title"] = _l('settings_smtp_settings_heading');

        $this->load->view('v_smtp_manage',$data);

    }

    public function smtp_setting_lists()
    {

        if( $this->input->is_ajax_request() )
        {

            $sTable = db_prefix().'email_template_smtp_settings';

            $select = [

                'id' ,

                'company_name' ,

                'smtp_email' ,

                'status' ,

            ];

            $where = [];

            $join = [];

            $sIndexColumn = 'id';

            $result = data_tables_init($select, $sIndexColumn, $sTable, $join, $where );

            $output  = $result['output'];

            $rResult = $result['rResult'];


            foreach ($rResult as $aRow){

                $row = [];

                $numberOutput = '<div class="row-options">';

                $numberOutput .= '<a href="#" onclick="fnc_smtp_setting_dlg( '. $aRow['id'] .'  ); return false;" >' . _l('edit') . '</a>';

                $numberOutput .= ' | <a class="text-danger _delete" href="'.admin_url('email_template_manage/smtp_delete/'.$aRow['id']).'" > '._l('delete').' </a>';

                $numberOutput .= '</div>';

                $row[] = '<a>' . $aRow['id'] . '</a>';

                $row[]  = $aRow['company_name'].$numberOutput;

                $row[]  = $aRow['smtp_email'];

                $toggleActive = '<div class="onoffswitch" data-toggle="tooltip" data-title="Status">

                            <input type="checkbox" data-switch-url="' . admin_url() . 'email_template_manage/smtp_change_status" name="onoffswitch" 
                                    class="onoffswitch-checkbox" id="snack_' . $aRow['id'] . '" 
                                    data-id="' . $aRow['id'] . '" ' . ($aRow['status'] == 1 ? 'checked' : '') . '>
                        
                            <label class="onoffswitch-label" for="snack_' . $aRow['id'] . '"></label>
                        
                            </div>';



                // For exporting

                $toggleActive .= '<span class="hide">' . ($aRow['status'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';


                $row[] = $toggleActive;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);

            die;
        }

    }

    public function smtp_detail( $detail_id = 0 )
    {

        if( $this->input->is_ajax_request() )
        {

            $data = [] ;

            $data['detail_id'] = $detail_id;

            $data['data'] = $this->email_template_manage_model->get_smtp_detail($detail_id);

            if ( empty( $data['data'] ) )
            {

                $data['data'] = new stdClass();

                $data['data']->id = 0;
                $data['data']->company_name = '';
                $data['data']->mail_engine = 'phpmailer';
                $data['data']->email_protocol = 'smtp';
                $data['data']->smtp_encryption = 'ssl';
                $data['data']->smtp_host = '';
                $data['data']->smtp_port = '';
                $data['data']->smtp_email = '';
                $data['data']->smtp_username = '';
                $data['data']->smtp_password = '';
                $data['data']->smtp_email_charset = 'utf-8';
                $data['data']->is_public = 1;
                $data['data']->active_staff = [];

            }
            else
            {

                if( !empty( $data['data']->active_staff ) )
                    $data['data']->active_staff = json_decode( $data['data']->active_staff , 1 );
                else
                    $data['data']->active_staff = [];

            }

            $data['staff'] = $this->db->select('staffid,firstname,lastname')->from(db_prefix().'staff')->where('active',1)->where('admin',0)->get()->result_array();

            $this->load->view('v_smtp_detail',$data );

        }

    }

    public function smtp_save()
    {

        if ( $this->input->post() )
        {

            $data = $this->input->post();

            if ( !empty( $data['is_public'] ) )
                $data['is_public'] = 1;
            else
                $data['is_public'] = 0;


            if ( empty( $data['staff'] ) )
                $data['active_staff'] = null;
            else
                $data['active_staff'] = json_encode( $data['staff'] );

            $id = $data['id'];

            unset($data['id']);
            unset($data['staff']);

            if ( !empty( $id ) )
            {

                $this->db->where('id',$id)->update( db_prefix().'email_template_smtp_settings' , $data );

                $message = _l('updated_successfully', _l('email_template_manage_smtp'));

            }
            else
            {

                $this->db->insert( db_prefix().'email_template_smtp_settings' , $data );

                $message = _l('added_successfully', _l('email_template_manage_smtp'));

            }


            set_alert( 'success' , $message );

        }

        redirect( admin_url('email_template_manage/smtp_settings') );

    }

    public function smtp_delete( $id )
    {

        $this->db->where('id',$id)->delete(db_prefix().'email_template_smtp_settings');

        set_alert('success' , _l('deleted', _l('email_template_manage_smtp')) );

        redirect( admin_url('email_template_manage/smtp_settings') );

    }

    public function smtp_change_status($id, $status)
    {

        if ($this->input->is_ajax_request())
        {

            $this->db->where('id', $id);

            $this->db->update(db_prefix().'email_template_smtp_settings' , [

                'status' => $status,

            ]);

            if ($this->db->affected_rows() > 0)
                return true;
            else
                return false;

        }

    }


    /**
     * @Version 1.1.6
     * Webhooks
     */
    public function webhooks()
    {
        $data["title"] = _l('email_template_manage_webhook');

        $this->load->view('v_mail_webhook',$data);
    }

    public function webhook_list()
    {

        if( $this->input->is_ajax_request() )
        {

            $sTable = db_prefix().'email_template_manage_webhooks';

            $select = [

                $sTable.'.id as id' ,

                'webhook_name' ,

                'template_name' ,

                'webhook_trigger' ,

                $sTable.'.status as status' ,

            ];

            $where = [];

            $join = [
                'LEFT JOIN ' . $this->table . ' ON ' . $sTable . '.template_id = ' . $this->table . '.id',
            ];

            $sIndexColumn = 'id';

            $result = data_tables_init($select, $sIndexColumn, $sTable, $join, $where , [ 'template_id' ] );

            $output  = $result['output'];

            $rResult = $result['rResult'];


            foreach ($rResult as $aRow){

                $row = [];

                $numberOutput = '<div class="row-options">';

                $numberOutput .= '<a href="#" onclick="fnc_template_dlg( '. $aRow['id'] .' , 0 ); return false;" >' . _l('edit') . '</a>';

                $numberOutput .= ' | <a class="text-warning" href="#" onclick="fnc_template_dlg( '. $aRow['id'] .' , 1 ); return false;"> '._l('email_template_manage_duplicate').' </a>';

                $numberOutput .= ' | <a class="text-danger _delete" href="'.admin_url('email_template_manage/webhook_delete/'.$aRow['id']).'" > '._l('delete').' </a>';

                $numberOutput .= '</div>';

                $row[] = '<a>' . $aRow['id'] . '</a>';

                $row[]  = $aRow['webhook_name'].$numberOutput;

                $template_link = admin_url("email_template_manage?template_id=".$aRow["template_id"]);
                $row[]  = "<a href='$template_link' target='_blank'>".$aRow['template_name']."</a>";

                $row[]  = _l('email_template_manage_'.$aRow['webhook_trigger']);


                $toggleActive = '<div class="onoffswitch" data-toggle="tooltip" data-title="Status">

                                    <input type="checkbox" data-switch-url="' . admin_url() . 'email_template_manage/webhook_change_status" name="onoffswitch" 
                                            class="onoffswitch-checkbox" id="snack_' . $aRow['id'] . '" 
                                            data-id="' . $aRow['id'] . '" ' . ($aRow['status'] == 1 ? 'checked' : '') . '>
                                
                                    <label class="onoffswitch-label" for="snack_' . $aRow['id'] . '"></label>
                            
                                </div>';



                // For exporting

                $toggleActive .= '<span class="hide">' . ($aRow['status'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';


                $row[] = $toggleActive;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);

            die;

        }

    }


    public function webhook_detail( $record_id = 0 , $is_duplicate = 0 )
    {

        $data['duplicate']  = $is_duplicate;

        $data['templates']  = $this->email_template_manage_model->get_all_templates_array();
        $data['webhooks']   = $this->email_template_manage_model->get_webhooks();

        if( !empty( $record_id ) )
            $data['data']   = $this->db->select('*')->from(db_prefix().'email_template_manage_webhooks')->where('id',$record_id)->get()->row();

        if ( empty( $data['data'] ) )
            $record_id = 0;

        $data['record_id'] = $record_id;

        $this->load->view('v_mail_webhook_detail' , $data );

    }

    public function webhook_type_content( $record_id = 0 , $webhook_trigger = "" )
    {

        $data['webhook_trigger'] = $webhook_trigger;

        $data['record_data'] = $this->db->select('*')
                                        ->from(db_prefix().'email_template_manage_webhooks')
                                        ->where('id',$record_id)
                                        ->where('webhook_trigger',$webhook_trigger)
                                        ->get()
                                        ->row();


        if ( in_array( $webhook_trigger , [ 'project_status_changed' ] ) )
        {

            $this->load->model('projects_model');

            $data['status']     = $this->projects_model->get_project_statuses();

            $this->load->view( 'v_mail_webhook_type_project' , $data );

        }
        elseif ( in_array( $webhook_trigger , [ 'task_status_changed' , 'after_add_task' ] ) )
        {

            $this->load->model('tasks_model');

            if ( $webhook_trigger == 'task_status_changed' )
                $data['status']     = $this->tasks_model->get_statuses();

            $data['priorities'] = get_tasks_priorities();

            $this->load->view( 'v_mail_webhook_type_task' , $data );

        }
        elseif ( in_array( $webhook_trigger , [ 'invoice_status_changed' ] ) )
        {

            $this->load->model('invoices_model');

            $data['status']     = $this->invoices_model->get_statuses();

            $this->load->view( 'v_mail_webhook_type_invoice' , $data );

        }
        elseif ( in_array( $webhook_trigger , [ 'lead_status_changed' , 'lead_created' ] ) )
        {

            $this->load->model('leads_model');

            $data['status']     = $this->leads_model->get_status();

            $data['sources']    = $this->leads_model->get_source();

            $this->load->view( 'v_mail_webhook_type_lead' , $data );

        }
        else
            echo '';


    }

    public function webhook_change_status($id, $status)

    {

        if ($this->input->is_ajax_request()) {

            $this->db->where('id', $id);

            $this->db->update(db_prefix().'email_template_manage_webhooks', [

                'status' => $status,

            ]);

            if ($this->db->affected_rows() > 0)
                return true;
            else
                return false;

        }

    }

    public function webhook_delete( $id = 0 )
    {

        $this->db->where('id',$id)->delete(db_prefix().'email_template_manage_webhooks');

        set_alert('success' , _l('deleted', _l('email_template_manage_webhook_field') ) );

        redirect(admin_url('email_template_manage/webhooks') );

    }

    public function webhook_save()
    {

        if ( $this->input->post() )
        {

            $data = $this->input->post();

            $id = $data['id'];

            $db_data = [
                'webhook_name'  => $data['webhook_name'] ,

                'template_id'   => $data['template_id'] ,

                'webhook_trigger'      => $data['webhook_trigger'] ,
            ];


            if ( !empty( $data['send_to_staff'] ) )
                $db_data['staff_active'] = 1;
            else
                $db_data['staff_active'] = 0;

            if ( !empty( $data['send_to_client'] ) )
                $db_data['client_active'] = 1;
            else
                $db_data['client_active'] = 0;


            $options = [];

            if ( !empty( $data['status'] ) )
                $options['status'] =  $data['status'] ;

            if ( !empty( $data['sources'] ) )
                $options['sources'] =  $data['sources'] ;

            if ( !empty( $data['priority'] ) )
                $options['priority'] =  $data['priority'] ;

            $db_data['options'] = json_encode( $options );

            if ( !empty( $id ) )
            {

                $this->db->where('id',$id)->update( db_prefix().'email_template_manage_webhooks' , $db_data );

                $message = _l('updated_successfully', _l('email_template_manage_webhook_field'));

            }
            else
            {

                $this->db->insert( db_prefix().'email_template_manage_webhooks' , $db_data );

                $message = _l('added_successfully', _l('email_template_manage_webhook_field'));

            }


            set_alert( 'success' , $message );

        }

        redirect( admin_url('email_template_manage/webhooks') );

    }


    /**
     * Special mails
     */

    public function special()
    {
        $data["title"] = _l('email_template_manage_specials');

        $this->load->view('v_mail_special',$data);
    }

    public function special_list()
    {

        if( $this->input->is_ajax_request() )
        {

            $sTable = db_prefix().'email_template_manage_special';

            $select = [

                $sTable.'.id as id' ,

                'special_name' ,

                'template_name' ,

                'rel_type' ,

                $sTable.'.status as status' ,

            ];

            $where = [];

            $join = [
                'LEFT JOIN ' . $this->table . ' ON ' . $sTable . '.template_id = ' . $this->table . '.id',
            ];

            $sIndexColumn = 'id';

            $result = data_tables_init($select, $sIndexColumn, $sTable, $join, $where , [ 'template_id' ] );

            $output  = $result['output'];

            $rResult = $result['rResult'];


            foreach ($rResult as $aRow){

                $row = [];

                $numberOutput = '<div class="row-options">';

                $numberOutput .= '<a href="#" onclick="fnc_template_dlg( '. $aRow['id'] .' , 0 ); return false;" >' . _l('edit') . '</a>';

                $numberOutput .= ' | <a class="text-warning" href="#" onclick="fnc_template_dlg( '. $aRow['id'] .' , 1 ); return false;"> '._l('email_template_manage_duplicate').' </a>';

                $numberOutput .= ' | <a class="text-danger _delete" href="'.admin_url('email_template_manage/special_delete/'.$aRow['id']).'" > '._l('delete').' </a>';

                $numberOutput .= '</div>';

                $row[] = '<a>' . $aRow['id'] . '</a>';

                $row[]  = $aRow['special_name'].$numberOutput;

                $template_link = admin_url("email_template_manage?template_id=".$aRow["template_id"]);
                $row[]  = "<a href='$template_link' target='_blank'>".$aRow['template_name']."</a>";

                $row[]  = _l($aRow['rel_type']);


                $toggleActive = '<div class="onoffswitch" data-toggle="tooltip" data-title="Status">

                                    <input type="checkbox" data-switch-url="' . admin_url() . 'email_template_manage/special_change_status" name="onoffswitch" 
                                            class="onoffswitch-checkbox" id="snack_' . $aRow['id'] . '" 
                                            data-id="' . $aRow['id'] . '" ' . ($aRow['status'] == 1 ? 'checked' : '') . '>
                                
                                    <label class="onoffswitch-label" for="snack_' . $aRow['id'] . '"></label>
                            
                                </div>';



                // For exporting

                $toggleActive .= '<span class="hide">' . ($aRow['status'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';


                $row[] = $toggleActive;

                $output['aaData'][] = $row;
            }

            echo json_encode($output);

            die;

        }

    }


    public function special_detail( $record_id = 0 , $is_duplicate = 0 )
    {

        $data['duplicate']  = $is_duplicate;

        $data['templates']  = $this->email_template_manage_model->get_all_templates_array( [ 'client' , 'staff' ] );

        $data['special_rel_types']  = $this->email_template_manage_model->get_special_reltypes();

        $data['staff_options']      = $this->email_template_manage_model->get_special_staff_options();

        $data['contact_options']    = $this->email_template_manage_model->get_special_contact_options();

        $data['reply_data']         = $this->email_template_manage_model->get_special_reply_data();

        if( !empty( $record_id ) )
            $data['data']   = $this->db->select('*')->from(db_prefix().'email_template_manage_special')->where('id',$record_id)->get()->row();

        if ( empty( $data['data'] ) )
            $record_id = 0;

        $data['record_id'] = $record_id;

        $this->load->view('v_mail_special_detail' , $data );

    }

    public function special_change_status($id, $status)

    {

        if ($this->input->is_ajax_request()) {

            $this->db->where('id', $id);

            $this->db->update(db_prefix().'email_template_manage_special', [

                'status' => $status,

            ]);

            if ($this->db->affected_rows() > 0)
                return true;
            else
                return false;

        }

    }

    public function special_delete( $id = 0 )
    {

        $this->db->where('id',$id)->delete(db_prefix().'email_template_manage_special');

        set_alert('success' , _l('deleted', _l('email_template_manage_special') ) );

        redirect(admin_url('email_template_manage/special') );

    }

    public function special_save()
    {

        if ( $this->input->post() )
        {

            $data = $this->input->post();

            $id = $data['id'];

            $db_data = [
                'special_name'  => $data['special_name'] ,

                'template_id'   => $data['template_id'] ,

                'rel_type'      => $data['rel_type'] ,

                'sending_hour'  => $data['sending_hour'] ,

                'repeat_every'  => $data['repeat_every'] ,
            ];


            if ( $data['rel_type'] == 'staff' )
                $db_data['date_field_name'] = $data['staff_option'];

            if ( $data['rel_type'] == 'contact' )
                $db_data['date_field_name'] = $data['contact_option'];

            $db_data['is_custom_field'] = 0;

            if ( !in_array( $db_data['date_field_name'] , [ 'datecreated' ] ) )
                $db_data['is_custom_field'] = 1;



            if ( !empty( $id ) )
            {

                $this->db->where('id',$id)->update( db_prefix().'email_template_manage_special' , $db_data );

                $message = _l('updated_successfully', _l('email_template_manage_special'));

            }
            else
            {

                $this->db->insert( db_prefix().'email_template_manage_special' , $db_data );

                $message = _l('added_successfully', _l('email_template_manage_special'));

            }


            set_alert( 'success' , $message );

        }

        redirect( admin_url('email_template_manage/special') );

    }


    /**
     * Checking db record
     */

    public function check_template_manage_db()
    {

        $CI = &get_instance();


        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_templates' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_templates` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `template_name` varchar(150) DEFAULT NULL,
                      `template_content` text DEFAULT NULL,
                      `template_subject` varchar(255) DEFAULT NULL,
                      `status` tinyint(4) DEFAULT 1,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }



        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_timer' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_timer` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `setting_name` varchar(255) DEFAULT NULL,
                      `template_id` int(11) DEFAULT NULL,
                      `client_groups` varchar(100) DEFAULT NULL,
                      `leads` varchar(255) DEFAULT NULL,
                      `clients` varchar(255) DEFAULT NULL,
                      `sending_date` date DEFAULT NULL,
                      `status` tinyint(4) DEFAULT 1,
                      `send_status` tinyint(4) DEFAULT 0,
                      `sending_hour` tinyint(4) DEFAULT 0,
                      `not_clients` varchar(255) DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `template_id` (`template_id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }



        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_sending_logs' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_sending_logs` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `mail_id` int(11) DEFAULT NULL,
                      `client_id` int(11) DEFAULT NULL,
                      `lead_id` int(11) DEFAULT NULL,
                      `status` tinyint(4) DEFAULT 0,
                      `error_message` varchar(500) DEFAULT NULL,
                      `date` datetime DEFAULT NULL,
                      `mail_address` varchar(255) DEFAULT NULL,
                      `mail_company` varchar(255) DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `mail_id` (`mail_id`),
                      KEY `client_id` (`client_id`),
                      KEY `lead_id` (`lead_id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }


        /**
         * Version 1.0.2
         */

        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_mail_logs' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_mail_logs` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `rel_type` varchar(50) DEFAULT NULL,
                      `rel_id` int(11) DEFAULT NULL,
                      `template_id` int(11) DEFAULT NULL,
                      `company_name` varchar(150) DEFAULT NULL,
                      `company_email` varchar(250) DEFAULT NULL,
                      `company_cc` varchar(250) DEFAULT NULL,
                      `error_message` text DEFAULT NULL,
                      `mail_subject` varchar(255) DEFAULT NULL,
                      `content` text DEFAULT NULL,
                      `date` datetime DEFAULT NULL,
                      `status` tinyint(4) DEFAULT 1,
                      PRIMARY KEY (`id`),
                      KEY `rel_type` (`rel_type`),
                      KEY `rel_id` (`rel_id`),
                      KEY `template_id` (`template_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }


        if( !$CI->db->field_exists('opened', db_prefix() .'email_template_manage_mail_logs') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_mail_logs`
                                ADD COLUMN `opened` tinyint NULL DEFAULT 0 AFTER `status`,
                                ADD COLUMN `date_opened` datetime NULL AFTER `opened`;');


        }



        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_mail_attachments' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_mail_attachments` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `mail_id` int(11) DEFAULT NULL,
                      `file_name` varchar(255) DEFAULT NULL,
                      `file_type` varchar(255) DEFAULT NULL,
                      `file_path` varchar(300) DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `mail_id` (`mail_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }


        /**
         *
         * @version System mail templates
         *
         */

        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_system_templates' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_system_templates` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `template_slug` varchar(100) DEFAULT NULL,
                      `status` tinyint(1) DEFAULT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }


        if( !$CI->db->field_exists('system_template_id', db_prefix() .'email_template_manage_mail_logs') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_mail_logs`
                                ADD COLUMN `system_template_id` varchar(100) NULL AFTER `date_opened`,
                                ADD COLUMN `system_template_slug` varchar(100) NULL AFTER `system_template_id`;');

        }


        /**
         *
         * @version 1.1.1 Triggers
         *
         */

        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_triggers' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_triggers` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `trigger_name` varchar(255) DEFAULT NULL,
                    `template_id` int(11) DEFAULT NULL,
                    `rel_type` varchar(20) DEFAULT NULL,
                    `options` varchar(255) DEFAULT NULL,
                    `staff_active` tinyint(4) DEFAULT 0,
                    `client_active` tinyint(4) DEFAULT 0,
                    `status` tinyint(4) DEFAULT 1,
                    `sending_hour` tinyint(4) DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `template_id` (`template_id`),
                    KEY `rel_type` (`rel_type`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }


        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_trigger_logs' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_trigger_logs` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `trigger_id` int(11) DEFAULT NULL,
                      `trigger_rel_id` int(11) DEFAULT NULL,
                      `send_rel_type` varchar(25) DEFAULT NULL,
                      `send_rel_id` int(11) DEFAULT 0,
                      `mail_id` int(11) DEFAULT NULL,
                      `date` datetime DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `trigger_id` (`trigger_id`),
                      KEY `send_rel_type` (`send_rel_type`),
                      KEY `send_rel_id` (`send_rel_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }


        if( !$CI->db->field_exists('trigger_id', db_prefix() .'email_template_manage_mail_logs') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_mail_logs`
                               ADD COLUMN `trigger_id` int NULL DEFAULT 0 AFTER `system_template_slug`, ADD INDEX(`trigger_id`);');

        }


        if( !$CI->db->field_exists('send_rel_type', db_prefix() .'email_template_manage_mail_logs') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_mail_logs`
                                ADD COLUMN `send_rel_type` varchar(50) NULL AFTER `trigger_id`,
                                ADD COLUMN `send_rel_id` int NULL AFTER `send_rel_type`;');

        }


        if( !$CI->db->field_exists('lead_statuses', db_prefix() .'email_template_manage_timer') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_timer`
                                MODIFY COLUMN `client_groups` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `template_id`,
                                ADD COLUMN `lead_statuses` varchar(150) NULL AFTER `client_groups`,
                                ADD COLUMN `lead_sources` varchar(150) NULL AFTER `lead_statuses`;');

        }


        /**
         * @version 1.1.2
         */
        if( !$CI->db->field_exists('added_staff_id', db_prefix() .'email_template_manage_mail_logs') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_mail_logs`
                                ADD COLUMN `added_staff_id` int NULL DEFAULT 0 AFTER `date`');

        }


        if( !$CI->db->field_exists('added_staff_id', db_prefix() .'email_template_manage_templates') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_templates`
                                ADD COLUMN `date` datetime NULL AFTER `status`,
                                ADD COLUMN `added_staff_id` int NULL DEFAULT 0 AFTER `date`,
                                ADD COLUMN `is_public` tinyint NULL DEFAULT 1 AFTER `added_staff_id`;');

        }

        /**
         * @version 1.1.4
         */

        if( !$CI->db->field_exists('related_type', db_prefix() .'email_template_manage_templates') )
        {

            $CI->db->query("ALTER TABLE `".db_prefix()."email_template_manage_templates`
                                ADD COLUMN `related_type` varchar(30) NULL DEFAULT 'all' AFTER `template_subject`;");

        }




        /**
         * @Version 1.1.5
         * New Smtp Settings
         */
        if ( !$CI->db->table_exists( db_prefix() . 'email_template_smtp_settings' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_smtp_settings` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `company_name` varchar(200) DEFAULT NULL,
                      `mail_engine` varchar(30) DEFAULT NULL,
                      `email_protocol` varchar(10) DEFAULT NULL,
                      `smtp_encryption` varchar(10) DEFAULT NULL,
                      `smtp_host` varchar(150) DEFAULT NULL,
                      `smtp_port` varchar(20) DEFAULT NULL,
                      `smtp_email` varchar(150) DEFAULT NULL,
                      `smtp_username` varchar(150) DEFAULT NULL,
                      `smtp_password` varchar(100) DEFAULT NULL,
                      `smtp_email_charset` varchar(20) DEFAULT NULL,
                      `is_public` tinyint(4) DEFAULT 1,
                      `status` tinyint(4) DEFAULT 1,
                      `active_staff` varchar(255) DEFAULT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }



        if( !$CI->db->field_exists('smtp_setting_id', db_prefix() .'email_template_manage_mail_logs') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_mail_logs`
                                ADD COLUMN `smtp_setting_id` int NULL DEFAULT 0 AFTER `send_rel_id`;');

        }


        /**
         * @Version 1.2.1
         * Webhook
         */

        if( !$CI->db->field_exists('webhook_id', db_prefix() .'email_template_manage_mail_logs') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_mail_logs`
                                ADD COLUMN `webhook_id` int NULL DEFAULT 0 AFTER `smtp_setting_id`,
                                ADD INDEX(`smtp_setting_id`),
                                ADD INDEX(`webhook_id`);');

        }



        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_webhooks' ) )
        {

            $CI->db->query("
                    CREATE TABLE `".db_prefix()."email_template_manage_webhooks` (
                       `id` int(11) NOT NULL AUTO_INCREMENT,
                      `webhook_name` varchar(255) DEFAULT NULL,
                      `webhook_trigger` varchar(255) DEFAULT NULL,
                      `template_id` int(11) DEFAULT NULL,
                      `staff_active` tinyint(4) DEFAULT 0,
                      `client_active` tinyint(4) DEFAULT 0,
                      `options` varchar(255) DEFAULT NULL,
                      `status` tinyint(4) DEFAULT 1,
                      PRIMARY KEY (`id`),
                      KEY `template_id` (`template_id`),
                      KEY `webhook_trigger` (`webhook_trigger`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ");

        }


        /**
         * @Version  1.2.2
         */
        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_special' ) )
        {

            $CI->db->query("CREATE TABLE `".db_prefix()."email_template_manage_special` (
                                  `id` int(11) NOT NULL AUTO_INCREMENT,
                                  `special_name` varchar(255) DEFAULT NULL,
                                  `template_id` int(11) DEFAULT NULL,
                                  `rel_type` varchar(20) DEFAULT NULL,
                                  `date_field_name` varchar(100) DEFAULT NULL,
                                  `is_custom_field` tinyint(4) DEFAULT 0,
                                  `status` tinyint(4) DEFAULT 1,
                                  `sending_hour` tinyint(4) DEFAULT NULL,
                                  PRIMARY KEY (`id`),
                                  KEY `template_id` (`template_id`),
                                  KEY `rel_type` (`rel_type`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        }


        if( !$CI->db->field_exists('repeat_every', db_prefix() .'email_template_manage_special') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_special`
                                ADD COLUMN `repeat_every` varchar(15) DEFAULT NULL AFTER `sending_hour` ');

        }

        if ( !$CI->db->table_exists( db_prefix() . 'email_template_manage_special_logs' ) )
        {

            $CI->db->query("CREATE TABLE `".db_prefix()."email_template_manage_special_logs` ( 
                                  `id` int(11) NOT NULL AUTO_INCREMENT,
                                  `special_id` int(11) DEFAULT NULL,
                                  `send_rel_type` varchar(25) DEFAULT NULL,
                                  `send_rel_id` int(11) DEFAULT 0,
                                  `date` date DEFAULT NULL,
                                  PRIMARY KEY (`id`),
                                  KEY `special_id` (`special_id`),
                                  KEY `send_rel_type` (`send_rel_type`),
                                  KEY `send_rel_id` (`send_rel_id`)
                                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                        ");

        }


        if( !$CI->db->field_exists('special_id', db_prefix() .'email_template_manage_mail_logs') )
        {

            $CI->db->query('ALTER TABLE `'.db_prefix().'email_template_manage_mail_logs`
                                ADD COLUMN `special_id` int NULL DEFAULT 0 AFTER `webhook_id`; ');

        }


    }

}
