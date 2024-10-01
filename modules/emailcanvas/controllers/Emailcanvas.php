<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Emailcanvas extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('emailcanvas_model');
        hooks()->do_action('emailcanvas_init');
    }

    public function index()
    {
        show_404();
    }

    public function manage()
    {
        if (!has_permission('emailcanvas', '', 'view')) {
            access_denied('emailcanvas');
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('emailcanvas', 'table'));
        }

        $data['title'] = _l('emailcanvas');
        $this->load->view('manage', $data);
    }

    public function create($id = '')
    {
        if (!has_permission('emailcanvas', '', 'create')) {
            access_denied('emailcanvas');
        }

        if ($this->input->post() && $id === '') {

            $response = $this->emailcanvas_model->create($this->input->post() + ['created_at' => date('Y-m-d H:i:s')]);

            if ($response == true) {
                set_alert('success', _l('emailcanvas_created_successfully'));
            } else {
                set_alert('warning', _l('emailcanvas_not_created_successfully'));
            }

            redirect(admin_url('emailcanvas/manage'));

        } elseif ($this->input->post() && $id !== '') {
            $response = $this->emailcanvas_model->update($id, $this->input->post());

            if ($response == true) {
                set_alert('success', _l('emailcanvas_updated_successfully'));
            }

            redirect(admin_url('emailcanvas/create/'. $id));
        }

        $data['title'] = _l('emailcanvas');
        if ($id) {
            $data['element_data'] = $this->emailcanvas_model->get($id);
        }

        $data['email_templates_list'] = $this->db->get(db_prefix() . 'emailtemplates')->result_array();
        $data['available_languages_list'] = [];

        foreach ($this->app->get_available_languages() as $lang) {
            $data['available_languages_list'][] = ['name' => ucfirst($lang), 'value' => strtolower($lang)];
        }

        $this->load->view('create', $data);
    }

    public function email_builder($template_id = '')
    {
        if (!has_permission('emailcanvas', '', 'edit')) {
            access_denied('emailcanvas');
        }
        if ($template_id === '') {
            access_denied('emailcanvas');
        }

        $data['title'] = _l('emailcanvas');
        $data['template_data'] = $this->emailcanvas_model->get($template_id);

        $uploadDir = FCPATH . '/uploads/emailcanvas_template_assets/';
        if (is_dir($uploadDir)) {

            $files = scandir($uploadDir);
            $fileInfoArray = array();

            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {

                    $fileInfo = array(
                        'type' => 'image',
                        'src' => site_url('uploads/emailcanvas_template_assets/' . $file),
                        'height' => 350,
                        'width' => 250,
                        'name' => $file
                    );

                    $fileInfoArray[] = $fileInfo;
                }
            }

            $data['available_assets'] = json_encode($fileInfoArray);
        } else {
            $data['available_assets'] = json_encode([]);
        }

        $this->load->view('email_builder', $data);
    }

    public function send_email_template_test($template_id)
    {

        if ($this->input->post()) {

            $template_data = $this->emailcanvas_model->get($template_id);

            $this->load->config('email');
            // Simulate fake template to be parsed
            $template           = new StdClass();
            $template->message  = json_decode($template_data->template_html_css);
            $template->fromname = get_option('companyname') != '' ? get_option('companyname') : 'TEST';
            $template->subject  = 'Email Template Testing';

            $template = parse_email_template($template);

            $this->email->initialize();
            if (get_option('mail_engine') == 'phpmailer') {
                $this->email->set_debug_output(function ($err) {
                    if (!isset($GLOBALS['debug'])) {
                        $GLOBALS['debug'] = '';
                    }
                    $GLOBALS['debug'] .= $err . '<br />';

                    return $err;
                });

                $this->email->set_smtp_debug(3);
            }

            $this->email->set_newline(config_item('newline'));
            $this->email->set_crlf(config_item('crlf'));

            $this->email->from(get_option('smtp_email'), $template->fromname);
            $this->email->to($this->input->post('test_email'));

            $systemBCC = get_option('bcc_emails');

            if ($systemBCC != '') {
                $this->email->bcc($systemBCC);
            }

            $this->email->subject($template->subject);
            $this->email->message($template->message);

            if ($this->email->send(true)) {
                set_alert('success', 'Seems like your SMTP settings is set correctly. Check your email now.');
            } else {
                set_debug_alert('<h1>Your SMTP settings are not set correctly here is the debug log.</h1><br />' . $this->email->print_debugger() . (isset($GLOBALS['debug']) ? $GLOBALS['debug'] : ''));
            }
        }
    }

    public function update_email_template_content($template_id = '')
    {
        if (!has_permission('emailcanvas', '', 'edit')) {
            access_denied('emailcanvas');
        }

        if ($this->input->post()) {

            $template_content = str_replace("\\", "\\\\", $this->input->post('content', false));

            $this->emailcanvas_model->update($template_id, [
                'template_content' => $template_content,
                'template_html_css' => json_encode($this->input->post('template_html_css', false))
            ]);

            echo json_encode([
                'status' => '1',
                'message' => _l('updated_successfully', _l('emailcanvas_email_template'))
            ]);
            die;

        }

        echo json_encode([
            'status' => '0',
            'message' => 'Failed'
        ]);
        die;

    }

    public function upload_template_editor_images()
    {
        if (!has_permission('emailcanvas', '', 'edit')) {
            access_denied('emailcanvas');
        }

        if($_FILES)
        {
            $resultArray = array();
            foreach ( $_FILES as $file){
                $fileName = $file['name'];
                $tmpName = $file['tmp_name'];

                if ($file['error'] != UPLOAD_ERR_OK)
                {
                    error_log($file['error']);
                    echo JSON_encode(null);
                }

                $uploadDir = FCPATH . '/uploads/emailcanvas_template_assets/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, DIR_WRITE_MODE);
                }

                $targetPath = $uploadDir. $fileName;
                move_uploaded_file($tmpName, $targetPath);

                $imageUrl = site_url('uploads/emailcanvas_template_assets/'.$fileName);

                $result=array(
                    'name'=>$fileName,
                    'type'=>'image',
                    'src'=>$imageUrl,
                    'height'=>350,
                    'width'=>250
                );
                array_push($resultArray, $result);
            }

            $response = array( 'data' => $resultArray );
            echo json_encode($response);

        }
    }

    public function remove_template_editor_image()
    {
        if (!has_permission('emailcanvas', '', 'edit')) {
            access_denied('emailcanvas');
        }

        if ($this->input->post()) {

            $imageUrl = $this->input->post('image_url');

            $uploadDir = FCPATH . '/uploads/emailcanvas_template_assets/';
            if (is_dir($uploadDir)) {

                $files = scandir($uploadDir);

                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {

                        if (site_url('uploads/emailcanvas_template_assets/' . $file) == $imageUrl) {
                            $filePath = $uploadDir . $file;
                            unlink($filePath);
                        }
                    }
                }
            }
        }
    }

    public function reset_email_template_content($template_id = '')
    {

        if (!has_permission('emailcanvas', '', 'edit')) {
            access_denied('emailcanvas');
        }

        $this->emailcanvas_model->update($template_id, [
            'template_content' => '',
            'template_html_css' => ''
        ]);

        echo json_encode([
            'status' => '1',
            'message' => _l('emailcanvas_reset_template_success')
        ]);
        die;

    }

    public function delete($id = '')
    {
        if (!has_permission('emailcanvas', '', 'delete')) {
            access_denied('emailcanvas');
        }

        if (!$id) {
            redirect(admin_url('emailcanvas/manage'));
        }

        $response = $this->emailcanvas_model->delete($id);

        if ($response == true) {
            set_alert('success', _l('deleted', _l('emailcanvas')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('emailcanvas')));
        }

        redirect(admin_url('emailcanvas/manage'));
    }

    public function update_template_status($id, $status)
    {
        if (!has_permission('emailcanvas', '', 'edit')) {
            access_denied('emailcanvas');
        }

        if ($this->input->is_ajax_request()) {
            $this->emailcanvas_model->changeTemplateStatus($id, $status);
        }
    }
}
