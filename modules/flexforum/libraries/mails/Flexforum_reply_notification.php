<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Flexforum_reply_notification extends App_mail_template
{
    protected $for = FLEXFORUM_MODULE_NAME;

    protected $email;
    protected $name;
    protected $link;
    protected $subject_id;

    public $slug = FLEXFORUM_REPLY_NOTIFICATION_SLUG;

    public $rel_type = FLEXFORUM_MODULE_NAME;

    public function __construct($email, $name, $link, $subject_id)
    {
        parent::__construct();

        $this->email = $email;
        $this->name = $name;
        $this->link = $link;
        $this->subject_id = $subject_id;
    }

    public function build()
    {
        $data = [
            'name' => $this->name,
            'link' => $this->link,
        ];
        $this->set_merge_fields('flexforum_notification_merge_fields', $data);
        $this->to($this->email)
            ->set_rel_id($this->subject_id);
    }
}