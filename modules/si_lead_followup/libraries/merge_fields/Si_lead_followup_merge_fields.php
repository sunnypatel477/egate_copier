<?php defined('BASEPATH') or exit('No direct script access allowed');

class Si_lead_followup_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Lead Name',
                'key'       => '{lead_name}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => 'Lead Email',
                'key'       => '{lead_email}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                 'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => 'Lead Position',
                'key'       => '{lead_position}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                 'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => 'Lead Website',
                'key'       => '{lead_website}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                 'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => 'Lead Description',
                'key'       => '{lead_description}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => 'Lead Phone Number',
                'key'       => '{lead_phonenumber}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => 'Lead Company',
                'key'       => '{lead_company}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => 'Lead Country',
                'key'       => '{lead_country}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => 'Lead Zip',
                'key'       => '{lead_zip}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => 'Lead City',
                'key'       => '{lead_city}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
            ],
            [
                'name'      => 'Lead State',
                'key'       => '{lead_state}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => 'Lead Address',
                'key'       => '{lead_address}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => 'Lead Assigned',
                'key'       => '{lead_assigned}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => 'Lead Status',
                'key'       => '{lead_status}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => 'Lead Souce',
                'key'       => '{lead_source}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => 'Lead Link',
                'key'       => '{lead_link}',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => is_gdpr() && get_option('gdpr_enable_lead_public_form') == '1' ? 'Lead Public Form URL' : '',
                'key'       => is_gdpr() && get_option('gdpr_enable_lead_public_form') == '1' ? '{lead_public_form_url}' : '',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
            [
                'name'      => is_gdpr() && get_option('gdpr_enable_consent_for_leads') == '1' ? 'Lead Consent Link' : '',
                'key'       => is_gdpr() && get_option('gdpr_enable_consent_for_leads') == '1' ? '{lead_public_consent_url}' : '',
                'available' => [
                    'leads',
                    'si_lead_followup',
                ],
                'templates' => [
                    'si-lead-followup-lead_followup-email',
                ],
            ],
        ];
    }
}