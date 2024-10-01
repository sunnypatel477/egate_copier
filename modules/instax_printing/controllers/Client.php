<?php 

defined('BASEPATH') or exit('No direct script access allowed');

class Client extends ClientsController
{
    public function __construct(){
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
        $data['background_whole'] = $this->db->where('type', 'whole')->get(db_prefix() . 'instax_printing_background_images')->result_array();
        $data['background_individual'] = $this->db->where('type', 'individual')->get(db_prefix() . 'instax_printing_background_images')->result_array();
        $data['category'] = $this->db->get(db_prefix() . 'instax_printing_background_category')->result_array();
        $data['event_category'] = $this->db->get(db_prefix() . 'instax_printing_event_category')->result_array();
        $data['order_from'] = $this->db->get(db_prefix() . 'instax_printing_order_from')->result_array();
        $this->disableNavigation();
        $this->disableSubMenu();
        $this->disableFooter();
        $this->data($data);
        $this->view('client/manage');
        $this->layout();
        // } else {
        //     redirect('/');
        // }
    }

}
?>