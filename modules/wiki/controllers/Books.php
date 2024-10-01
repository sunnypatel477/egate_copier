<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Books extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('roles_model');
        $this->load->model('staff_model');
        $this->load->model('wikibooks_model');
    }

    public function index()
    {
        $user = get_staff($this->session->userdata('tfa_staffid'));
        $data['title'] = _l('wiki_books_list');
        $filter_query = $this->input->get('filter_query');
        if(!isset($filter_query)){
            $filter_query = "";
        }
        $data['books'] = $this->wikibooks_model->get_all_books($filter_query);
        $data['filter_query'] = $filter_query;
        $data['user_id'] = $user->staffid;
        $this->load->view('books_manage', $data);
    }

    public function book($id = '')
    {
        if ($this->input->post()) {
            if ($id == '') {
                if (!has_permission('wiki_books', '', 'create')) {
                    access_denied('wiki_books');
                }
                $id = $this->wikibooks_model->add($this->input->post());
                if ($id) {
                    $uploadedFiles = handle_wiki_book_attachments_array($id);
                    if ($uploadedFiles && is_array($uploadedFiles)) {
                        foreach ($uploadedFiles as $file) {
                            $this->misc_model->add_attachment_to_database($id, WIKI_MODULE_NAME, [$file]);
                        }
                    }
                    set_alert('success', _l('added_successfully', _l('wiki_book')));
                    redirect(admin_url('wiki/books/book/' . $id));
                }
            } else {
                if (!has_permission('wiki_books', '', 'edit')) {
                    access_denied('wiki_books');
                }
                $success = $this->wikibooks_model->update($this->input->post(), $id);
                $uploadedFiles = handle_wiki_book_attachments_array($id);
                if ($uploadedFiles && is_array($uploadedFiles)) {
                    foreach ($uploadedFiles as $file) {
                        $this->misc_model->add_attachment_to_database($id, WIKI_MODULE_NAME, [$file]);
                    }
                }
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('wiki_book')));
                }else{
                
                }
                $back_url = $this->input->post('back_url');
                if(isset($back_url)){
                    redirect($back_url);
                }else{
                    redirect(admin_url('wiki/books/book/' . $id));
                }
            }
        }
        if ($id == '') {
            $title = _l('wiki_create_book');
        } else {
            $data['book']        = $this->wikibooks_model->get($id);
            $data['attachments']    = $this->wikibooks_model->get_wiki_book_attachments($id);
            $back_url = $this->input->get('back_url');
            if(isset($back_url)){
                $data['back_url'] = $back_url;
            }
            $title = _l('edit', _l('wiki_book_lowercase'));
        }
        $data['title']                 = $title;
        $data['roles']    = $this->roles_model->get();
        $data['members'] = $this->staff_model->get('', [
            'active'       => 1,
            'is_not_staff' => 0,
        ]);
        $this->load->view('book', $data);
    }

    public function delete($id)
    {
        if (!has_permission('wiki_books', '', 'delete')) {
            access_denied('wiki_books');
        }
        if (!$id) {
            redirect(admin_url('wiki/books'));
        }
        $response = $this->wikibooks_model->delete($id);
        if ($response == true) {
            $this->wikibooks_model->remove_wiki_book_all($id);
            set_alert('success', _l('deleted', _l('wiki_book')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('wiki_book_lowercase')));
        }
        redirect(admin_url('wiki/books'));
    }

    public function remove_wiki_book_attachment($id)
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->wikibooks_model->remove_wiki_book_attachment($id));
        }
    }

    public function download_files($knowledge_base_id)
    {
        $taskWhere = 'external IS NULL';

        $files = $this->wikibooks_model->get_wiki_book_attachments($knowledge_base_id, $taskWhere);

        if (count($files) == 0) {
            redirect($_SERVER['HTTP_REFERER']);
        }

        $path = FCPATH . 'uploads/' . WIKI_MODULE_NAME . '/' . $knowledge_base_id;

        $this->load->library('zip');

        foreach ($files as $file) {
            $this->zip->read_file($path . '/' . $file['file_name']);
        }

        $this->zip->download('files.zip');
        $this->zip->clear_data();
    }
}
