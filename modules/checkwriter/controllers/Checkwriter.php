<?php

header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Checkwriter extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('checkwriter_modal');
    }

    public function banklist()
    {
        if (!has_permission('checkwriter', '', 'view')) {
            access_denied('checkwriter');
        }
        $this->load->view('checkwriter/banklist');
    }

    public function checkIssuance() {
        if (!has_permission('checkwriter', '', 'view')) {
            access_denied('checkwriter');
        }
        $this->load->view('checkwriter/checkissuance');
    }

    public function table()
    {
        if (!has_permission('view', 'checkwriter')) {
            ajax_access_denied();
        }
        $data = $this->checkwriter_modal->get_bank_data();
        $this->app->get_table_data(module_views_path('checkwriter', 'table/banklist'));
    }

    public function checkIssuancelisttable()
    {
        if (!has_permission('view', 'checkwriter')) {
            ajax_access_denied();
        }
        $data = $this->checkwriter_modal->get_bank_data();
        $this->app->get_table_data(module_views_path('checkwriter', 'table/checkissuance'));
    }


    public function add_update_bank()
    {

        if (!has_permission('create', 'checkwriter')) {
            ajax_access_denied();
        }

        $data = $this->input->post();
        $id = $data['bank_id'];

        if ($id != '') {
            $success = $this->checkwriter_modal->update_bank_details($data);
            if ($success == true) {
                $message = _l('updated_successfully', _l('bank_list'));
                set_alert('success', $message);
            }
            redirect(admin_url('checkwriter/banklist'));
        } else {
            $success = $this->checkwriter_modal->add_bank_details($data);
            if ($success == true) {
                $message = _l('added_successfully', _l('bank_list'));
                set_alert('success', $message);
            }
            redirect(admin_url('checkwriter/banklist'));
        }
    }

    public function update_check_historys() {

        if (!has_permission('update', 'checkwriter')) {
            ajax_access_denied();
        }


        $data = $this->input->post();
        
        $id = $data['history_id'];
        if ($id != '') {
            $success = $this->checkwriter_modal->update_check_historys($data);
            if ($success == true) {
                $message = _l('updated_successfully', _l('check_history'));
                set_alert('success', $message);
            }
            redirect(admin_url('checkwriter/checkIssuance'));
        }
    }

    public function edit_bank_details($id)
    {
        if ($id != '') {
            $data = $this->checkwriter_modal->get_bank_data($id);
        }
        echo json_encode($data);
        die;
    }


    public function edit_check_historys($id)
    {
        if ($id != '') {
            $data = $this->checkwriter_modal->get_history_data($id);
        }
        echo json_encode($data);
        die;
    }

    public function  delete_bank_details($id)
    {
        if (!$id) {
            redirect(admin_url('checkwriter/banklist'));
        }
        $response = $this->checkwriter_modal->delete_bank_details($id);
        if ($response == true) {
            set_alert('success', _l('deleted'));
        } else {
            set_alert('warning', _l('problem_deleting'));
        }
        redirect(admin_url('checkwriter/banklist'));
    }

    public function printcheck()
    {
        $data = $this->input->get();

        $id = $data['id'];
        if ($id != '') {
            $success = $this->checkwriter_modal->update_expensive($data, $id);
            $data['expensive_id'] = $data['id'];
            $this->checkwriter_modal->add_expensive_history($data);
            unset($data['id']);
        }
        $expense = $this->expenses_model->get($id);


        try {
            $pdf = expense_note_pdf($expense);
           
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
        $type = 'I';
        $pdf_name = '_check';
        ob_end_clean();
        $pdf->Output($pdf_name . '.pdf', $type);
    }


    public function printcheckhistory($id)
    {
        // $expense = $this->expenses_model->get($id);
        $expense = $this->checkwriter_modal->get_check_history_data($id);
        try {
            $pdf = expense_note_pdf($expense);
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
        $type = 'I';
        $pdf_name = '_check';
        ob_end_clean();
        $pdf->Output($pdf_name . '.pdf', $type);
    }

    public function deletecheckhistory($id){
        $response = $this->checkwriter_modal->delete_check_history($id);
        if ($response == true) {
            set_alert('success', _l('deleted_check_history'));
        } else {
            set_alert('warning', _l('problem_deleting'));
        }
        redirect(admin_url('checkwriter/checkIssuance'));
    }

    public function check_no_exists()
    {
        $check_no = $this->input->post('check_no');
        $id = $this->input->post('id');
      
        if ($check_no) {
            $this->db->where('check_no', $check_no);
            $this->db->where_not_in('id', $id);
            $_current_check_no = $this->db->get(db_prefix() . 'expensive_history')->row();
            if($_current_check_no){
                echo json_encode(false);
                die();
            }else{
                echo json_encode(true);
                die();
            }
        }
    }

    public function managesetting() {
        if (!has_permission('checkwriter', '', 'view')) {
            access_denied('checkwriter');
        }
        $data['group'] = $this->input->get('group');
		$data['title'] = _l('setting');
		$data['tab'][] = 'marginset';

        if ($data['group'] == '') {
			$data['group'] = 'marginset';
		}

        $data['tabs']['view'] = 'includes/' . $data['group'];

        $this->load->view('checkwriter/managesetting',$data);
    }

    public function checkwriterpdfmarginsetting(){

        $data = $this->input->post();
        if($data) {
            $pdf_margin_right = $this->input->post('right_side_pdf_margin');
            $pdf_margin_left = $this->input->post('left_side_pdf_margin');
            update_option('right_side_pdf_margin', $pdf_margin_right);
            update_option('left_side_pdf_margin', $pdf_margin_left);
        }
        set_alert('success', _l('margin_data_updated'));
        redirect(admin_url('checkwriter/managesetting'));
    }
}
