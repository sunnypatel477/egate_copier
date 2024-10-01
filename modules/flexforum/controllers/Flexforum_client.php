<?php

defined('BASEPATH') or exit('No direct script access allowed');


use app\services\ValidatesContact;
use app\modules\flexforum\services\HasCommunity;

class FlexForum_client extends ClientsController
{
    use ValidatesContact, HasCommunity;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if(flexforum_banned([
            'user_id' => flexforum_get_user_id(),
            'user_type' => FLEXFORUM_CLIENT_USER_TYPE
        ])){
            show_error(flexforum_lang('banned_message'), 403, flexforum_lang('user_banned'));
        }
        // Won't work if there is no contact logged in and the contact email address is not confirmed.
        $this->app_css->theme('flexforum-css', module_dir_url('flexforum', 'assets/css/flexforum.css'));
        $this->app_scripts->theme('tinymce-js', 'assets/plugins/tinymce/tinymce.min.js');
        $this->app_scripts->theme('flexforum-js', module_dir_url('flexforum', 'assets/js/flexforum.js'));
        $this->title(flexforum_lang('community_forum'));
        $this->view('client/index');
        $this->layout();
    }
}