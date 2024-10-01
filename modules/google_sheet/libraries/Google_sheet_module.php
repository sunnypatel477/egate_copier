<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Google_sheet_module
{
    private $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
    }
}
