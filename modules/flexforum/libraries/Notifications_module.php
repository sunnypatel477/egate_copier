<?php

class Notifications_module
{
    private $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    /**
     * Send tickets to attendees email
     *
     * @param int $invoice_id
     * @return bool
     */
    public function send($type_id, $type)
    {
        $subject = $type == FLEXFORUM_TOPIC_REPLY_TYPE
            ? flexforum_get_topic(['id' => $type_id])
            : flexforum_get_reply(['id' => $type_id]);

        if (!$subject) {
            throw new Exception(flexforum_lang($type == FLEXFORUM_TOPIC_REPLY_TYPE ? 'topic_not_found' : 'reply_not_found'));
        }

        $followers = flexforum_get_followers([
            'type_id' => $type_id,
            'type' => $type
        ]);

        $this->ci->email->initialize();
        $this->ci->load->library('email');

        foreach ($followers as &$follower) {
            $name = flexforum_get_user_name($follower['user_id'], $follower['user_type']);
            $email = flexforum_get_user_email($follower['user_id'], $follower['user_type']);
            $link = flexforum_get_notification_link($type_id, $type);

            if (!$name) {
                throw new Exception(flexforum_lang('missing_name'));
            }
            if (!$email) {
                throw new Exception(flexforum_lang('missing_email'));
            }
            if (!$link) {
                throw new Exception(flexforum_lang('missing_link'));
            }

            $template_name = 'Flexforum_reply_notification';
            $template = mail_template($template_name, "flexforum", $email, $name, $link, $subject['id']);

            $template->send();
        }

        return true;
    }

    public function create_email_template()
    {
        $templateMessage = "Hello {name}, <br/><br/>A response has been added to a forum that you are following. <br/> Check it out here: {link} <br/><br/>Regards.";

        create_email_template('Flexforum - Reply Notification', $templateMessage, 'staff', 'Flexforum Reply Notification', FLEXFORUM_REPLY_NOTIFICATION_SLUG);
    }
}
