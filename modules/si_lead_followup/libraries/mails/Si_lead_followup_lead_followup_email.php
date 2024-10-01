<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Si_lead_followup_lead_followup_email extends App_mail_template
{
    protected $for = 'customer';

    protected $lead;

    public $rel_type = 'lead';

    protected $email;

    public $slug = 'si-lead-followup-lead-followup-email';

    public function __construct($lead,$email)
    {
        parent::__construct();
        $this->lead = $lead;
        $this->email = $email;

        $this->set_merge_fields('leads_merge_fields',  $this->lead);
        $this->set_merge_fields('staff_merge_fields', $this->lead->assigned);
        
    }

    public function build()
    {
        $this->to($this->email)
        ->set_rel_id($this->lead->id);
    }
}
