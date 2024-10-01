<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Instax_printing_client extends ClientsController
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('clients_model');
    }

    public function index()
    {
        // if (is_client_logged_in()) {

        // Assuming you have the Base64 image data

        $client = get_client();

        $data = array();
        $data['contact'] = $this->clients_model->get_contact(get_contact_user_id());
        $data['background_imags'] = $this->db->where('category', 1)->get(db_prefix() . 'instax_printing_background_images')->result_array();
        $data['background_whole'] = $this->db->where('type', 'whole')->where('category', 1)->get(db_prefix() . 'instax_printing_background_images')->result_array();
        $data['background_individual'] = $this->db->where('type', 'individual')->where('category', 1)->get(db_prefix() . 'instax_printing_background_images')->result_array();

        $data['background_whole_square'] = $this->db->where('type', 'whole')->where('category', 2)->get(db_prefix() . 'instax_printing_background_images')->result_array();
        $data['background_individual_square'] = $this->db->where('type', 'individual')->where('category', 2)->get(db_prefix() . 'instax_printing_background_images')->result_array();

        $data['background_whole_wide'] = $this->db->where('type', 'whole')->where('category', 3)->get(db_prefix() . 'instax_printing_background_images')->result_array();
        $data['background_individual_wide'] = $this->db->where('type', 'individual')->where('category', 3)->get(db_prefix() . 'instax_printing_background_images')->result_array();

        $data['category'] = $this->db->get(db_prefix() . 'instax_printing_background_category')->result_array();
        $data['event_category'] = $this->db->get(db_prefix() . 'instax_printing_event_category')->result_array();
        $data['order_from'] = $this->db->get(db_prefix() . 'instax_printing_order_from')->result_array();
        $this->data($data);
        $this->view('client/manage');
        $this->layout();
        // } else {
        //     redirect('/');
        // }
    }
    public function save_image()
    {
        $client = get_client();

        $post_data = $this->input->post();

        // $image = $post_data['image'];
        if (isset($post_data['images'])) {
            $images = json_decode($_POST['images'], true);
            // $images = $post_data['images'];
            unset($post_data['images']);
        }
        $directory_main = 'modules/instax_printing/uploads/Other/';
        if (!is_dir($directory_main)) {
            mkdir($directory_main, 0777, true);
        }
        if (isset($post_data['order_image_preview'])) {
            $order_image_preview = $post_data['order_image_preview'];


            if ($this->isBase64ImageOrURL($order_image_preview) == 'url') {
                $order_imageData = $order_image_preview;
            } else {
                // $imageData = $image;
                // Assuming you have the Base64 image data
                $base64Image = (string)$order_image_preview;

                // Decode the Base64 image string to binary
                $imageData = base64_decode(preg_replace('[removed]', '', $base64Image));

                // $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $order_image_preview));
                $image_name = 'order_image_' . strtotime(date('Y-m-d H:i:s')) . '.jpg';
                $filename = $directory_main . $image_name;

                // Save the binary image data as a file on the server
                if (file_put_contents($filename, $imageData)) {
                    $order_imageData = module_dir_url('instax_printing', 'uploads/Other/' . $image_name);
                }
            }
            unset($post_data['order_image_preview']);
            $post_data['order_number_image'] = $order_imageData;
        }
        if (isset($post_data['shippinng_image_preview'])) {
            $shippinng_image_preview = $post_data['shippinng_image_preview'];


            if ($this->isBase64ImageOrURL($shippinng_image_preview) == 'url') {
                $shipping_imageData = $shippinng_image_preview;
            } else {
                // $imageData = $image;
                // Assuming you have the Base64 image data
                $base64Image = (string)$shippinng_image_preview;

                // Decode the Base64 image string to binary
                $imageData = base64_decode(preg_replace('[removed]', '', $base64Image));

                // $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $shippinng_image_preview));
                $image_name = 'shipping_image_' . strtotime(date('Y-m-d H:i:s')) . '.jpg';
                $filename = $directory_main . $image_name;

                // Save the binary image data as a file on the server
                if (file_put_contents($filename, $imageData)) {
                    $shipping_imageData = module_dir_url('instax_printing', 'uploads/Other/' . $image_name);
                }
            }
            unset($post_data['shippinng_image_preview']);
            $post_data['shipping_image'] = $shipping_imageData;
        }


        $post_data['created_date'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'instax_printing_inquery', $post_data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            $directory = 'modules/instax_printing/uploads/' . $insert_id . '/';
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

                    // Save the binary image data as a file on the server
                    if (file_put_contents($filename, $imageData)) {
                        $imageData = module_dir_url('instax_printing', 'uploads/' . $insert_id . '/image_' . ($index + 1) . '.jpg');
                    }
                }



                $image_data = array(
                    'instax_printing_inquery_id' => $insert_id,
                    // 'instax_printing_client_id' => $insert_id,
                    'image_url' => $imageData,
                    'text_data' => $dataimage['textHTML'],
                    'type' => $dataimage['type'],
                    'page' => $dataimage['page'],
                    'background' => $dataimage['background'],
                );
                $this->db->insert(db_prefix() . 'instax_printing_inquery_images', $image_data);
            }
            echo json_encode(array('status' => 'success', 'message' => 'Inquery has been submitted successfully.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Something went wrong.'));
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
    function get_background_by_category()
    {


        if ($this->input->post()) {
            $data              = $this->input->post();

            if (isset($data['category']) && $data['category'] != '') {
                $category = $data['category'];
                $this->db->where('category', $category);
            }

            if (isset($data['apply_type']) && $data['apply_type'] != '') {
                $this->db->where('type', strtolower($data['apply_type']));
            }
            if (isset($data['event_category']) && $data['event_category'] != '') {
                $this->db->where('event_category', $data['event_category']);
            }

            $instax_printing_background_images = $this->db->get(db_prefix() . 'instax_printing_background_images')->result_array();
            // $data_event_category = $this->db->get(db_prefix() . 'instax_printing_event_category')->result_array();
            $data_event_category = $this->db->order_by('name', 'ASC')->get(db_prefix() . 'instax_printing_event_category')->result_array();
            $data_event_temp = [];
            foreach ($data_event_category as $data_event) {
                $data_event['id'] = $data_event['id'];
                $data_event['name'] = $data_event['name'];
                $data_event['count'] = get_event_category_images_count($data_event['id'], $data['category'], $data['apply_type']);
                $data_event_temp[] = $data_event;
            }
            echo json_encode(['success' => true, 'background_images' => $instax_printing_background_images, 'data_event_category' => $data_event_temp]);
            die;
        }
    }
    function send_email()
    {
        // $uploadDir = 'uploads/'; // Specify the directory where you want to save the Blob file
        $uploadDir = 'modules/instax_printing/uploads/email/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $post_data = $this->input->post();
     
        // if(isset($_FILES['file']['tmp_name'])){
        //     $image_name = 'inqueriry' . strtotime(date('Y-m-d H:i:s')) . '.pdf';
        //     $filename = $uploadDir . $image_name;
        //     if (move_uploaded_file($_FILES['file']['tmp_name'], $filename)) {
        //         $inbox = array();

        //         $inbox['to'] = get_option('instax_printing_email_inquiry');
        //         $inbox['sender_name'] = get_option('companyname');
        //         $inbox['subject'] = 'Inquery';
        //         $inbox['body'] = '<p>Name : '.$post_data['name'].'</p> <p>Email : '.$post_data['email'].'</p> <p>Contact : '.$post_data['contact'].'</p> <p>OrderNumber : '.$post_data['orderNumber'].'</p>';
        //         // $inbox['body'] = nl2br_save_html($inbox['body']);
        //         $inbox['date_received'] = date('Y-m-d H:i:s');
        //         $inbox['from_email'] = get_option('smtp_email');

        //         if (strlen(get_option('smtp_host')) > 0 && strlen(get_option('smtp_password')) > 0 && strlen(get_option('smtp_username')) > 0) {

        //             $ci = &get_instance();
        //             $ci->email->initialize();
        //             $ci->load->library('email');
        //             $ci->email->clear(true);
        //             $ci->email->from($inbox['from_email'], $inbox['sender_name']);
        //             $ci->email->to($inbox['to']);

        //             $ci->email->subject($inbox['subject']);
        //             $ci->email->message($inbox['body']);
        //             $ci->email->attach($filename);

        //             $ci->email->send(true);
        //         }
        //         echo json_encode(array('status' => 'success', 'message' => 'Inquery has been submitted successfully.'));
        //     }else {
        //         echo json_encode(array('status' => 'fail', 'message' => 'Inquery has been failed please try after some time.'));
        //     }
        // }
        if (isset($post_data['file'])) {
            $shippinng_image_preview = $post_data['file'];


            if ($this->isBase64ImageOrURL($shippinng_image_preview) == 'url') {
                $shipping_imageData = $shippinng_image_preview;
            } else {
                // $imageData = $image;
                // Assuming you have the Base64 image data
                $base64Image = (string)$shippinng_image_preview;

                // Decode the Base64 image string to binary
                $imageData = base64_decode(preg_replace('[removed]', '', $base64Image));

                // $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $shippinng_image_preview));
                
                $image_name = 'inqueriry' . strtotime(date('Y-m-d H:i:s')) . '.pdf';
                     $filename = $uploadDir . $image_name;
                // Save the binary image data as a file on the server
                if (file_put_contents($filename, $imageData)) {

                    $inbox = array();

                    $inbox['to'] = get_option('instax_printing_email_inquiry');
                    $inbox['sender_name'] = get_option('companyname');
                    $inbox['subject'] = 'Inquery';
                    $inbox['body'] = '<p>Name : '.$post_data['name'].'</p> <p>Email : '.$post_data['email'].'</p> <p>Contact : '.$post_data['contact'].'</p> <p>OrderNumber : '.$post_data['orderNumber'].'</p>';
                    // $inbox['body'] = nl2br_save_html($inbox['body']);
                    $inbox['date_received'] = date('Y-m-d H:i:s');
                    $inbox['from_email'] = get_option('smtp_email');

                    if (strlen(get_option('smtp_host')) > 0 && strlen(get_option('smtp_password')) > 0 && strlen(get_option('smtp_username')) > 0) {

                        $ci = &get_instance();
                        $ci->email->initialize();
                        $ci->load->library('email');
                        $ci->email->clear(true);
                        $ci->email->from($inbox['from_email'], $inbox['sender_name']);
                        $ci->email->to($inbox['to']);

                        $ci->email->subject($inbox['subject']);
                        $ci->email->message($inbox['body']);
                        $ci->email->attach($filename);

                        $ci->email->send(true);
                    }
                    echo json_encode(array('status' => 'success', 'message' => 'Inquery has been submitted successfully.'));
                } else {
                    echo json_encode(array('status' => 'fail', 'message' => 'Inquery has been failed please try after some time.'));
                }
            }
        }
        
    }
    function update_print_btn_count(){
        $post_data = $this->input->post();
        if($this->input->post()){
            $post_data = $this->input->post();
            $old_print_btn_count = get_option($post_data['type']) != null ? get_option($post_data['type']) : 0;  
            $new_print_btn_count = $old_print_btn_count + 1;
            update_option($post_data['type'], $new_print_btn_count);
            echo json_encode(array('status' => 'success', 'message' => 'Print count has been updated successfully.'));    
        }
        
        
    }
}
