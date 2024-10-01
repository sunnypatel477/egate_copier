<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Check_email extends CI_Controller
{

    public function track( $mail_id = 0 )
    {
        // The SPAM protection appears to call "HEAD" calls instead of "GET" as they aren't interested in the result,
        // just that things seem to redirect somewhere good.

        if (strtolower($_SERVER['REQUEST_METHOD']) === 'get') {

            $this->db->where('opened', 0);
            $this->db->where('id', $mail_id);
            $tracking = $this->db->select('id')->get(db_prefix() . 'email_template_manage_mail_logs')->row();

            // Perhaps already tracked?
            if ( $tracking )
            {

                $this->db->where('id', $tracking->id);

                $this->db->update(db_prefix() . 'email_template_manage_mail_logs', [
                    'date_opened' => date('Y-m-d H:i:s'),
                    'opened'      => 1,
                ]);

            }

        }

    }

    public function track_temp( $mail_id = '' )
    {

        if (strtolower($_SERVER['REQUEST_METHOD']) === 'get')
        {

            $this->db->where('opened', 0);
            $this->db->where('system_template_id', $mail_id);
            $tracking = $this->db->select('id')->get(db_prefix() . 'email_template_manage_mail_logs')->row();

            // Perhaps already tracked?
            if ( $tracking )
            {

                $this->db->where('id', $tracking->id);

                $this->db->update(db_prefix() . 'email_template_manage_mail_logs', [
                    'date_opened' => date('Y-m-d H:i:s'),
                    'opened'      => 1,
                ]);

            }

        }


    }


}
