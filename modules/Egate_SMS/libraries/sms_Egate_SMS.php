<?php

use Saloon\Exceptions\SaloonException;
use Saloon\Http\Response;

defined('BASEPATH') or exit('No direct script access allowed');

class Sms_Egate_SMS extends App_sms
{
    private string $Egate_SMS_key;
	private string $Egate_SMS_service;
	private string $Egate_SMS_whatsapp;
	private string $Egate_SMS_device;
	private string $Egate_SMS_gateway;
    private string $Egate_SMS_sim;

    public function __construct()
    {
        parent::__construct();

        $this->Egate_SMS_key = $this->get_option("Egate_SMS", "Egate_SMS_key");
        $this->Egate_SMS_service = $this->get_option("Egate_SMS", "Egate_SMS_service");
        $this->Egate_SMS_whatsapp = $this->get_option("Egate_SMS", "Egate_SMS_whatsapp");
        $this->Egate_SMS_device = $this->get_option("Egate_SMS", "Egate_SMS_device");
        $this->Egate_SMS_gateway = $this->get_option("Egate_SMS", "Egate_SMS_gateway");
        $this->Egate_SMS_sim = $this->get_option("Egate_SMS", "Egate_SMS_sim");

        $this->add_gateway("Egate_SMS", [
            "name" => "egate_sms",
            "options" => [
                [
                    "name" => "Egate_SMS_key",
                    "label" => "API Key (<a href=\"http://zsms.smartoffice.com.ph/dashboard/tools/keys\" target=\"_blank\">Create API Key</a>)",
                    "info" => "
                    <p>Your API key, please make sure that everything is correct and required permissions are granted: <strong>sms_send</strong>, <strong>wa_send</strong></p>
                    <hr class=\"hr-15\" />"
                ],   
                [
                    "name" => "Egate_SMS_service",
                    "field_type" => "radio",
                    "default_value" => 1,
                    "label" => "Sending Service",
                    "options" => [
                        ["label" => "SMS", "value" => 1],
                        ["label" => "WhatsApp", "value" => 2]
                    ],
                    "info" => "
                    <p>Select the sending service, please make sure that the api key has the following permissions: <strong>send_sms</strong>, <strong>wa_send</strong></p>
                    <hr class=\"hr-15\" />"
                ],      
                [
                    "name" => "Egate_SMS_whatsapp",
                    "label" => "WhatsApp Account ID",
                    "info" => "
                    <p>For WhatsApp service only. WhatsApp account ID you want to use for sending.</p>
                    <hr class=\"hr-15\" />"
                ], 
                [
                    "name" => "Egate_SMS_device",
                    "label" => "Device Unique ID",
                    "info" => "
                    <p>For SMS service only. Linked device unique ID, please only enter this field if you are sending using one of your devices.</p>
                    <hr class=\"hr-15\" />"
                ],        
                [
                    "name" => "Egate_SMS_gateway",
                    "label" => "Gateway Unique ID",
                    "info" => "
                    <p>For SMS service only. Partner device unique ID or gateway ID, please only enter this field if you are sending using a partner device or third party gateway.</p>
                    <hr class=\"hr-15\" />"
                ],    
                [
                    "name" => "Egate_SMS_sim",
                    "field_type" => "radio",
                    "default_value" => 1,
                    "label" => "SIM Slot",
                    "options" => [
                        ["label" => "SIM 1", "value" => 1],
                        ["label" => "SIM 2", "value" => 2]
                    ],
                    "info" => "
                    <p>For SMS service only. Select the sim slot you want to use for sending the messages. This is not used for partner devices and third party gateways.</p>
                    <hr class=\"hr-15\" />"
                ]
            ],
        ]);
    }

    public function send($number, $message): bool
    {
        if(empty($this->Egate_SMS_service) || $this->Egate_SMS_service < 2):
            if(!empty($this->Egate_SMS_device)):
                $mode = "devices";
            else:
                $mode = "credits";
            endif;

            if($mode == "devices"):
                $form = [
                    "secret" => $this->Egate_SMS_key,
                    "mode" => "devices",
                    "device" => $this->Egate_SMS_device,
                    "phone" => $number,
                    "message" => $message,
                    "sim" => $this->Egate_SMS_sim < 2 ? 1 : 2
                ];
            else:
                $form = [
                    "secret" => $this->Egate_SMS_key,
                    "mode" => "credits",
                    "gateway" => $this->Egate_SMS_gateway,
                    "phone" => $number,
                    "message" => $message
                ];
            endif;

            $apiurl = "http://zsms.smartoffice.com.ph/api/send/sms";
        else:
            $form = [
                "secret" => $this->Egate_SMS_key,
                "account" => $this->Egate_SMS_whatsapp,
                "type" => "text",
                "recipient" => $number,
                "message" => $message
            ];

            $apiurl = "http://zsms.smartoffice.com.ph/api/send/whatsapp";
        endif;

        try {
            $send = json_decode($this->client->request(
                "POST",
                $apiurl,
                [
                    "form_params" => $form,
                    "allow_redirects" => true,
                    "http_errors" => false,
                ]
            )->getBody()->getContents(), true);

            if($send["status"] == 200):
                $this->logSuccess("Sent via egate_sms to {$number} with message: {$message}");
                return true;
            else:
            
                $this->set_error("Message was not sent!<br>Message: {$send["message"]}");
                return false;
            endif;
        } catch(SaloonException $e){
            $this->set_error("Message was not sent!<br>Error: {$e->getMessage()}");
            return false;
            
        }
    }
}
