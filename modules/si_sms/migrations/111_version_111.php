<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_111 extends App_module_migration
{
	public function up()
	{   
		add_option(SI_SMS_MODULE_NAME.'_skip_draft_status_when_create',1);
	}
}