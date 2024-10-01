<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Instax_printing extends AdminController
{

    private $_module = 'instax_printing';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }
        if ($this->input->is_ajax_request()) {
            if (has_permission($this->_module, '', 'view') || has_permission($this->_module, '', 'view_own') || is_admin()) {
                $this->app->get_table_data(module_views_path($this->_module, 'table'));
            } else {
                access_denied($this->_module);
            }
        }
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        $this->load->view($this->_module . '/manage', $data);
    }
    public function print_page_view($id)
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }
        $data['title'] = _l($this->_module);
        // $this->db->where('instax_printing_inquery_id', $id);
        // $data['images'] = $this->db->get(db_prefix() . 'instax_printing_inquery_images')->result_array();
        $this->db->select(db_prefix() . 'instax_printing_inquery_images.*, ' . db_prefix() . 'instax_printing_background_images.background_url');
        $this->db->from(db_prefix() . 'instax_printing_inquery_images');
        $this->db->join(db_prefix() . 'instax_printing_background_images', db_prefix() . 'instax_printing_background_images.id = ' . db_prefix() . 'instax_printing_inquery_images.background', 'left');
        $this->db->where('instax_printing_inquery_id', $id);
        $this->db->order_by('page', 'ASC');
        $data['images'] = $this->db->get()->result_array();
        if (!empty($data['images'])) {
            $images_count =  count($data['images']);
        }
        $data['inquery'] = $this->db->get_where(db_prefix() . 'instax_printing_inquery', array('id' => $id))->row();

        $data['background'] = $this->db->get_where(db_prefix() . 'instax_printing_background_images', array('id' => $data['inquery']->background))->row();
        $data['event_category'] = $this->db->get(db_prefix() . 'instax_printing_event_category')->result_array();
        if( $data['inquery']->frame_type == 'mini' ||$data['inquery']->frame_type == '' ){
            $this->load->view($this->_module . '/print_page_view', $data);
        }elseif( $data['inquery']->frame_type == 'square' ){
            $this->load->view($this->_module . '/print_page_view_square', $data);

        }elseif($data['inquery']->frame_type == 'wide'){
            $this->load->view($this->_module . '/print_page_view_wide', $data);

        }
        
    }
    function isBase64ImageOrURL($string)
    {
        // Check if the string is a Base64-encoded image
        if (preg_match('/^data:image\/\w+;base64,/', $string)) {
            return 'base64_image';
        }

        // Check if the string is a URL
        if (filter_var($string, FILTER_VALIDATE_URL)) {
            return 'url';
        }

        return 'unknown';
    }
    public function update_saved_image()
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }
        if ($this->input->is_ajax_request()) {
            $post_data = $this->input->post();
            if (isset($post_data['images'])) {
                $images = json_decode($_POST['images'], true);
                // $images = $post_data['images'];
                unset($post_data['images']);
            }
            $directory = 'modules/instax_printing/uploads/' . $post_data['instax_printing_inquery_id'] . '/';
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
            foreach ($images as $index => $dataimage) {

                $image = $dataimage['imageSrc'];
                if ($this->isBase64ImageOrURL($image) == 'url') {
                    $imageData = $image;
                } else {
                    // $imageData = $image;
                    // Assuming you have the Base64 image data
                    $base64Image = (string)$image;

                    // Decode the Base64 image string to binary
                    // $imageData = base64_decode(preg_replace('[removed]', '', $base64Image));

                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));

                    $filename = $directory . 'image_' . ($index + 1) . '.jpg';
                    if (file_exists($filename)) {
                        // Check if the file exists before attempting to delete it
                        if (unlink($filename)) {
                        }
                    }
                    // Save the binary image data as a file on the server
                    if (file_put_contents($filename, $imageData)) {
                        $imageData = module_dir_url('instax_printing', 'uploads/' . $post_data['instax_printing_inquery_id'] . '/image_' . ($index + 1) . '.jpg');
                    }
                }



                $image_data = array(
                    'image_url' => $imageData,
                    'text_data' => $dataimage['textHTML'],
                    'type' => $dataimage['type'],
                    'page' => $dataimage['page'],
                    'background' => $dataimage['background'],
                );
                $this->db->where('id', $dataimage['id']);
                $this->db->update(db_prefix() . 'instax_printing_inquery_images', $image_data);
            }
            echo json_encode(array('status' => 'success', 'message' => 'Images has been Updated successfully.'));
        }
    }
    public function background_images()
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }
        if ($this->input->is_ajax_request()) {
            if (has_permission($this->_module, '', 'view') || has_permission($this->_module, '', 'view_own') || is_admin()) {
                $this->app->get_table_data(module_views_path($this->_module, 'background_images_table'));
            } else {
                access_denied($this->_module);
            }
        }
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        $data['category'] = $this->db->get(db_prefix() . 'instax_printing_background_category')->result_array();
        $data['event_category'] = $this->db->get(db_prefix() . 'instax_printing_event_category')->result_array();

        $this->load->view($this->_module . '/background_images', $data);
    }
    public function create_background($id = '')
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }
        if ($this->input->post()) {
            $data                = $this->input->post();

            if ($data['id'] > 0) {
                $background = $this->db->get_where(db_prefix() . 'instax_printing_background_images', array('id' => $data['id']))->row();
                if ($_FILES['image']['name'] != '') {

                    $targetDirectory = 'modules/instax_printing/uploads/background_images/';
                    if (!is_dir($targetDirectory)) {
                        mkdir($targetDirectory, 0777, true);
                    }

                    if ($background->background_url != '') {
                        $file_name =  parse_url($background->background_url, PHP_URL_PATH);

                        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $file_name)) {
                            unlink($_SERVER['DOCUMENT_ROOT'] . $file_name);
                        }
                    }
                    $originalFileName = $_FILES['image']['name'];
                    $imageFileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

                    $uniqueFileName = $this->generateUniqueFileName($targetDirectory, $imageFileType);
                    $targetFile = $targetDirectory . $uniqueFileName;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        if (is_image($targetFile)) {
                           
                            $this->create_img_thumb($targetDirectory, $uniqueFileName);
                        }
                        $data['background_image'] =$uniqueFileName;
                        $data['thumb_url'] = module_dir_url('instax_printing', 'uploads/background_images/thumb/' . $uniqueFileName . '');
                        $data['background_url'] = module_dir_url('instax_printing', 'uploads/background_images/' . $uniqueFileName . '');

                        // $imageData = module_dir_url('instax_printing', 'uploads/background_images/' .$uniqueFileName .'');
                    } else {
                        $data['background_url'] = '';
                        $data['background_image'] ='';
                        $data['thumb_url'] = '';
                    }
                }
                if ($_FILES['raw_file']['name']) {
                    $targetDirectory = 'modules/instax_printing/uploads/background_images_RAW/';
                    if (!is_dir($targetDirectory)) {
                        mkdir($targetDirectory, 0777, true);
                    }
                    if ($background->raw_url != '') {
                        $file_name =  parse_url($background->raw_url, PHP_URL_PATH);
                        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $file_name)) {
                            unlink($_SERVER['DOCUMENT_ROOT'] . $file_name);
                        }
                    }

                    $originalFileName = $_FILES['raw_file']['name'];
                    $imageFileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

                    $uniqueFileName = $this->generateUniqueFileName($targetDirectory, $imageFileType);
                    $targetFile = $targetDirectory . $uniqueFileName;
                    if (move_uploaded_file($_FILES['raw_file']['tmp_name'], $targetFile)) {
                        $data['raw_url'] = module_dir_url('instax_printing', 'uploads/background_images_RAW/' . $uniqueFileName . '');
                        // $imageData = module_dir_url('instax_printing', 'uploads/background_images/' .$uniqueFileName .'');
                    } else {
                        $data['raw_url'] = '';
                    }
                }


                $this->db->where('id', $data['id']);
                $this->db->update(db_prefix() . 'instax_printing_background_images', $data);
                set_alert('success', _l('updated_successfully', _l('instax_printing_background')));
            } else {
                $targetDirectory = 'modules/instax_printing/uploads/background_images/';
                if (!is_dir($targetDirectory)) {
                    mkdir($targetDirectory, 0777, true);
                }


                $originalFileName = $_FILES['image']['name'];
                $imageFileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

                $uniqueFileName = $this->generateUniqueFileName($targetDirectory, $imageFileType);
                $targetFile = $targetDirectory . $uniqueFileName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    if (is_image($targetFile)) {
                        $this->create_img_thumb($targetDirectory, $uniqueFileName);
                    }
                    $data['background_image'] =$uniqueFileName;
                    $data['thumb_url'] = module_dir_url('instax_printing', 'uploads/background_images/thumb/' . $uniqueFileName . '');
                    $data['background_url'] = module_dir_url('instax_printing', 'uploads/background_images/' . $uniqueFileName . '');

                    
                    // $imageData = module_dir_url('instax_printing', 'uploads/background_images/' .$uniqueFileName .'');
                } else {
                    $data['background_image'] ='';
                    $data['thumb_url'] = '';
                    $data['background_url'] = '';
                }
                $targetDirectory = 'modules/instax_printing/uploads/background_images_RAW/';
                if (!is_dir($targetDirectory)) {
                    mkdir($targetDirectory, 0777, true);
                }


                $originalFileName = $_FILES['raw_file']['name'];
                $imageFileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

                $uniqueFileName = $this->generateUniqueFileName($targetDirectory, $imageFileType);
                $targetFile = $targetDirectory . $uniqueFileName;
                if (move_uploaded_file($_FILES['raw_file']['tmp_name'], $targetFile)) {
                    $data['raw_url'] = module_dir_url('instax_printing', 'uploads/background_images_RAW/' . $uniqueFileName . '');
                    // $imageData = module_dir_url('instax_printing', 'uploads/background_images/' .$uniqueFileName .'');
                } else {
                    $data['raw_url'] = '';
                }
                $this->db->insert(db_prefix() . 'instax_printing_background_images', $data);
                set_alert('success', _l('added_successfully', _l('instax_printing_background')));
            }
            redirect(admin_url('instax_printing/background_images/'));
        }
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        if ($id != '') {
            $data['background'] = $this->db->get_where(db_prefix() . 'instax_printing_background_images', array('id' => $id))->row();
            $data['id'] = $id;
        }
        $data['category'] = $this->db->get(db_prefix() . 'instax_printing_background_category')->result_array();
        $data['event_category'] = $this->db->get(db_prefix() . 'instax_printing_event_category')->result_array();
        $this->load->view($this->_module . '/create_background', $data);
    }
    function generateUniqueFileName($targetDirectory, $fileExtension)
    {
        $timestamp = time(); // Get the current timestamp
        $randomString = bin2hex(random_bytes(6)); // Generate a random string

        $uniqueFileName = $timestamp . "_" . $randomString . "." . $fileExtension;
        return $uniqueFileName;
    }
    function delete_background($id)
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }
        $this->db->where('id', $id);
        $instax_printing_background_images = $this->db->get(db_prefix() . 'instax_printing_background_images')->row();
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'instax_printing_background_images');
        // $filePath = 'path/to/your/file.txt';
        $url = $instax_printing_background_images->background_url;
        $fileName = basename($url);
        $filePath = 'modules/instax_printing/uploads/background_images/' . $fileName;
        if (file_exists($filePath)) {
            // Check if the file exists before attempting to delete it
            if (unlink($filePath)) {
            }
        }
        set_alert('success', _l('deleted_successfully', _l('instax_printing_background')));
        redirect(admin_url('instax_printing/background_images/'));
    }
    function delete_print_page($id)
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'instax_printing_inquery');
        // $filePath = 'path/to/your/file.txt';

        $filePath = 'modules/instax_printing/uploads/' . $id . '/';
        if (is_dir($filePath)) {
            // Check if the file exists before attempting to delete it
            rmdir($filePath);
            unlink($filePath);
        }
        set_alert('success', _l('deleted_successfully', _l('instax_printing_background')));
        redirect(admin_url('instax_printing'));
    }
    public function bulk_action()
    {
        hooks()->do_action('before_do_bulk_action_for_tasks');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids       = $this->input->post('ids');
            $is_admin  = is_admin();
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if ($this->input->post('mass_delete')) {
                        $this->db->where('id', $id);
                        $this->db->delete(db_prefix() . 'instax_printing_inquery');
                        // $filePath = 'path/to/your/file.txt';

                        $filePath = 'modules/instax_printing/uploads/' . $id . '/';
                        if (is_dir($filePath)) {
                            // Check if the file exists before attempting to delete it
                            rmdir($filePath);
                            unlink($filePath);
                        }
                    }
                }
            }
            if ($this->input->post('mass_delete')) {
                // set_alert('success', _l('total_tasks_deleted', $total_deleted));
                set_alert('success', _l('deleted_successfully', $total_deleted));
            }
        }
    }
    public function payment()
    {
        if ($this->input->post()) {
            $id = $this->input->post('inquiry_id');
            if ($_FILES['attachment']['name'] != '') {

                $targetDirectory = 'modules/instax_printing/uploads/' . $id . '/payment/';
                if (!is_dir($targetDirectory)) {
                    mkdir($targetDirectory, 0777, true);
                }
                $originalFileName = $_FILES['attachment']['name'];
                $imageFileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

                $uniqueFileName = $this->generateUniqueFileName($targetDirectory, $imageFileType);
                $targetFile = $targetDirectory . $uniqueFileName;
                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFile)) {
                    $data['attachment_url'] = module_dir_url('instax_printing', 'uploads/' . $id . '/payment/' . $uniqueFileName . '');
                    // $imageData = module_dir_url('instax_printing', 'uploads/background_images/' .$uniqueFileName .'');
                } else {
                    $data['attachment_url'] = '';
                }
            }
            $data['paymentdate'] = $this->input->post('paymentdate');
            $data['amount'] = $this->input->post('amount');

            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'instax_printing_inquery', $data);
            echo json_encode(array('success' => true, 'message' => _l('updated_successfully', _l('instax_printing_inquery'))));
        }
    }
    public function background_category()
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }
        if ($this->input->is_ajax_request()) {
            if (has_permission($this->_module, '', 'view') || has_permission($this->_module, '', 'view_own') || is_admin()) {
                $this->app->get_table_data(module_views_path($this->_module, 'background_category_table'));
            } else {
                access_denied($this->_module);
            }
        }
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        $this->load->view($this->_module . '/background_category', $data);
    }
    public function create_background_category($id = '')
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }
        if ($this->input->post()) {
            $data                = $this->input->post();

            if ($data['id'] > 0) {
                $this->db->where('id', $data['id']);
                $this->db->update(db_prefix() . 'instax_printing_background_category', $data);
                set_alert('success', _l('updated_successfully', _l('instax_printing_category')));
            } else {
                $this->db->insert(db_prefix() . 'instax_printing_background_category', $data);
                set_alert('success', _l('added_successfully', _l('instax_printing_category')));
            }
            redirect(admin_url('instax_printing/background_category/'));
        }
        if ($id != '') {
            $data['category'] = $this->db->get_where(db_prefix() . 'instax_printing_background_category', array('id' => $id))->row();
        }
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        $this->load->view($this->_module . '/create_category', $data);
    }
    function delete_background_category($id)
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'instax_printing_background_category');
        set_alert('success', _l('deleted_successfully', _l('instax_printing_category')));
        redirect(admin_url('instax_printing/background_category/'));
    }


    /***************************
     * This For Event Category
     */

    public function event_category()
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }
        if ($this->input->is_ajax_request()) {
            if (has_permission($this->_module, '', 'view') || has_permission($this->_module, '', 'view_own') || is_admin()) {
                $this->app->get_table_data(module_views_path($this->_module, 'event_category_table'));
            } else {
                access_denied($this->_module);
            }
        }
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        $this->load->view($this->_module . '/event_category', $data);
    }
    public function create_event_category($id = '')
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }
        if ($this->input->post()) {
            $data                = $this->input->post();

            if ($data['id'] > 0) {
                $this->db->where('id', $data['id']);
                $this->db->update(db_prefix() . 'instax_printing_event_category', $data);
                set_alert('success', _l('updated_successfully', _l('instax_printing_event_category')));
            } else {
                $this->db->insert(db_prefix() . 'instax_printing_event_category', $data);
                set_alert('success', _l('added_successfully', _l('instax_printing_event_category')));
            }
            redirect(admin_url('instax_printing/event_category/'));
        }
        if ($id != '') {
            $data['category'] = $this->db->get_where(db_prefix() . 'instax_printing_event_category', array('id' => $id))->row();
        }
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        $this->load->view($this->_module . '/create_event_category', $data);
    }
    function delete_event_category($id)
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'instax_printing_event_category');
        set_alert('success', _l('deleted_successfully', _l('instax_printing_event_category')));
        redirect(admin_url('instax_printing/event_category/'));
    }



    /***************************
     * This For Order From
     */

    public function order_from()
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }
        if ($this->input->is_ajax_request()) {
            if (has_permission($this->_module, '', 'view') || has_permission($this->_module, '', 'view_own') || is_admin()) {
                $this->app->get_table_data(module_views_path($this->_module, 'order_from_table'));
            } else {
                access_denied($this->_module);
            }
        }
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        $this->load->view($this->_module . '/order_from', $data);
    }
    public function create_order_from($id = '')
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }
        if ($this->input->post()) {
            $data                = $this->input->post();

            if ($data['id'] > 0) {
                $this->db->where('id', $data['id']);
                $this->db->update(db_prefix() . 'instax_printing_order_from', $data);
                set_alert('success', _l('updated_successfully', _l('instax_printing_order_from')));
            } else {
                $this->db->insert(db_prefix() . 'instax_printing_order_from', $data);
                set_alert('success', _l('added_successfully', _l('instax_printing_order_from')));
            }
            redirect(admin_url('instax_printing/order_from/'));
        }
        if ($id != '') {
            $data['order_from'] = $this->db->get_where(db_prefix() . 'instax_printing_order_from', array('id' => $id))->row();
        }
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        $this->load->view($this->_module . '/create_order_from', $data);
    }
    function delete_order_from($id)
    {
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) {
            access_denied($this->_module);
        }
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'instax_printing_order_from');
        set_alert('success', _l('deleted_successfully', _l('instax_printing_order_from')));
        redirect(admin_url('instax_printing/order_from'));
    }
    function set_up()
    {
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        if ($this->input->post()) {
            $data                = $this->input->post();

            update_option('instax_printing_email_inquiry', $data['instax_printing_email_inquiry'], true);
            update_option('instax_printing_email_inquiry_active', isset($data['instax_printing_email_inquiry_active']) ? 1 : 0, true);
            update_option('print_button_display', isset($data['print_button_display']) ? $data['print_button_display'] : 'logged_in', true);
            set_alert('success', _l('updated_successfully', _l('instax_printing_set_up')));

            redirect(admin_url('instax_printing/set_up/'));
        }
        $this->load->view($this->_module . '/set_up', $data);
    }

    function create_thimb()
    {

        $images = $this->db->get(db_prefix() . 'instax_printing_background_images')->result_array();
        $targetDirectory = 'modules/instax_printing/uploads/background_images/';
        foreach ($images as $image) {
            $url = $image['background_url'];
            $path_parts = pathinfo($url);
            $uniqueFileName = $path_parts['basename'];
            $targetFile = $targetDirectory . $uniqueFileName;
            if (is_image($targetFile)) {
                $this->create_img_thumb($targetDirectory, $uniqueFileName);
            }
            $this->db->where('id', $image['id']);
            $this->db->update(db_prefix() . 'instax_printing_background_images', array('background_image'=>$uniqueFileName,'thumb_url' => module_dir_url('instax_printing', 'uploads/background_images/thumb/' . $uniqueFileName . '')));
            // die;
        }
    }

    function create_img_thumb($path, $filename, $width = 300, $height = 300)
    {
        $CI = &get_instance();

        $source_path  = rtrim($path, '/') . '/' . $filename;
        $target_path  = $path.'/thumb/';
        if (!is_dir($target_path)) {
            mkdir($target_path, 0777, true);
        }
        $config_manip = [
            'image_library'  => 'gd2',
            'source_image'   => $source_path,
            'new_image'      => $target_path,
            'maintain_ratio' => true,
            'width'          => $width,
            'height'         => $height,
        ];

        $CI->image_lib->initialize($config_manip);
        $CI->image_lib->resize();
        $CI->image_lib->clear();
    }
}
