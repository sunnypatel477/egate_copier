<?php
use app\services\imap\Imap;
use Ddeboer\Imap\SearchExpression;
use Ddeboer\Imap\Search\Flag\Unseen;
use app\services\imap\ConnectionErrorException;
use Ddeboer\Imap\Exception\UnexpectedEncodingException;
use Ddeboer\Imap\Exception\MessageDoesNotExistException;

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Customer service model
 */
#[\AllowDynamicProperties]
class Customer_service_model extends App_Model
{
	
	/**
	 * construct
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/*Write your function on here*/

	/**
	 * get mail scan rule
	 * @param  boolean $id     
	 * @param  boolean $active 
	 * @return [type]          
	 */
	public function get_mail_scan_rule($id = false, $active = false) {

		if (is_numeric($id)) {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'cs_spam_filters')->row();
		}
		if ($id == false) {
			if($active){
				$this->db->where('status', 'enabled');
			}
			return $this->db->get(db_prefix() . 'cs_spam_filters')->result_array();
		}
	}

	/**
	 * add mail scan rule
	 * @param [type] $data 
	 */
	public function add_mail_scan_rule($data)
	{

		$data['status'] = 'enabled';
		$data['datecreated'] = date('Y-m-d H:i:s');
		$data['staffid'] = get_staff_user_id();

		$this->db->insert(db_prefix().'cs_spam_filters',$data);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			return $insert_id;
		}
		return false;
	}

	/**
	 * update mail scan rule
	 * @param  [type] $data 
	 * @param  [type] $id   
	 * @return [type]       
	 */
	public function update_mail_scan_rule($data, $id)
	{
		$affected_rows=0;
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'cs_spam_filters', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}

		return false;  
	}

	/**
	 * delete mail scan rule
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_mail_scan_rule($id)
	{
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'cs_spam_filters');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * change mail scan rule status
	 * @param  [type] $id     
	 * @param  [type] $status 
	 * @return [type]         
	 */
	public function change_mail_scan_rule_status($id, $status)
	{
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'cs_spam_filters', [
			'status' => $status,
		]);

		return true;
	}

	/**
	 * change setting with checkbox
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function change_setting_with_checkbox($data)
	{

		$val = $data['input_name_status'] == 'true' ? 1 : 0;

		$this->db->where('name',$data['input_name']);
		$this->db->update(db_prefix() . 'options', [
			'value' => $val,
		]);
		if ($this->db->affected_rows() > 0) {
			return true;
		}else{
			return false;
		}
	}

	/**
	 * update prefix number
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function update_prefix_number($data)
	{
		$affected_rows=0;
		foreach ($data as $key => $value) {

			if (update_option($key, $value)){
				$affected_rows++;
			}
		}

		if($affected_rows > 0){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * create code
	 * @param  [type] $rel_type 
	 * @return [type]           
	 */
	public function create_code($rel_type) {
		$str_result ='';
		$prefix_str ='';
		switch ($rel_type) {
			case 'sla_code':
			$prefix_str .= get_option('cs_sla_prefix');
			$next_number = (int) get_option('cs_sla_number');
			$str_result .= $prefix_str.str_pad($next_number,5,'0',STR_PAD_LEFT);
			break;

			case 'kpi_code':
			$prefix_str .= get_option('cs_kpi_prefix');
			$next_number = (int) get_option('cs_kpi_number');
			$str_result .= $prefix_str.str_pad($next_number,5,'0',STR_PAD_LEFT);
			break;

			case 'ticket_category_code':
			$prefix_str .= get_option('cs_ticket_category_prefix');
			$next_number = (int) get_option('cs_ticket_category_number');
			$str_result .= $prefix_str.str_pad($next_number,5,'0',STR_PAD_LEFT);
			break;

			case 'ticket_code':
			$prefix_str .= get_option('cs_ticket_prefix');
			$next_number = (int) get_option('cs_ticket_number');
			$str_result .= $prefix_str.str_pad($next_number,5,'0',STR_PAD_LEFT);
			break;

			case 'workflow_code':
			$prefix_str .= get_option('cs_workflow_prefix');
			$next_number = (int) get_option('cs_workflow_number');
			$str_result .= $prefix_str.str_pad($next_number,5,'0',STR_PAD_LEFT);
			break;			

			default:
				# code...
			break;
		}

		return $str_result;
	}

	/**
	 * get kpi
	 * @param  boolean $id     
	 * @param  boolean $active 
	 * @return [type]          
	 */
	public function get_kpi($id = false, $active = false) {

		if (is_numeric($id)) {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'cs_kpis')->row();
		}
		if ($id == false) {
			if($active){
				$this->db->where('status', 'enabled');
			}
			return $this->db->get(db_prefix() . 'cs_kpis')->result_array();
		}
	}

	/**
	 * add kpi
	 * @param [type] $data 
	 */
	public function add_kpi($data)
	{

		$data['status'] = 'enabled';
		$data['datecreated'] = date('Y-m-d H:i:s');
		$data['staffid'] = get_staff_user_id();

		$this->db->insert(db_prefix().'cs_kpis',$data);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			update_option('cs_kpi_number', get_option('cs_kpi_number')+1);
			return $insert_id;
		}
		return false;
	}

	/**
	 * update kpi
	 * @param  [type] $data 
	 * @param  [type] $id   
	 * @return [type]       
	 */
	public function update_kpi($data, $id)
	{
		$affected_rows=0;
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'cs_kpis', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}

		return false;  
	}

	/**
	 * delete kpi
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_kpi($id)
	{
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'cs_kpis');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * change kpi status
	 * @param  [type] $id     
	 * @param  [type] $status 
	 * @return [type]         
	 */
	public function change_kpi_status($id, $status)
	{
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'cs_kpis', [
			'status' => $status,
		]);

		return true;
	}

	/**
	 * get sla
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_sla($id = false, $status = '')
	{
		if (is_numeric($id)) {
			$warranty_receipt_process = new \stdClass;

			$this->db->where('id', $id);
			$warranty_receipt_process = $this->db->get(db_prefix() . 'cs_service_level_agreements')->row();
			if($warranty_receipt_process){
				$this->db->where('service_level_agreement_id', $id);
				$sla_detail = $this->db->get(db_prefix() . 'cs_service_level_agreement_warnings')->result_array();
				if(count($sla_detail) > 0){
					$warranty_receipt_process->details = $sla_detail;

				}else{
					$warranty_receipt_process->details = array();
				}
			}

			return $warranty_receipt_process;
		}
		if ($id == false) {
			if(new_strlen($status) > 0){
				$this->db->where('status', $status);
			}
			return $this->db->get(db_prefix().'cs_service_level_agreements')->result_array();
		}
	}

	/**
	 * add sla
	 * @param [type] $data 
	 */
	public function add_sla($data)
	{
		if(isset($data['over_due_warning_alert'])){
			$data['over_due_warning_alert'] = 'disabled';
		}else{
			$data['over_due_warning_alert'] = 'enabled';
		}

		$data['datecreated'] = date("Y-m-d H:i:s");
		$data['staffid'] = get_staff_user_id();
		$data['status'] = 'enabled';

		$this->db->insert(db_prefix().'cs_service_level_agreements',$data);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			update_option('cs_sla_number', get_option('cs_sla_number')+1);
			return $insert_id;
		}
		return false;
	}

	/**
	 * update sla
	 * @param  [type] $data 
	 * @param  [type] $id   
	 * @return [type]       
	 */
	public function update_sla($data, $id)
	{
		if(isset($data['over_due_warning_alert'])){
			$data['over_due_warning_alert'] = 'disabled';
		}else{
			$data['over_due_warning_alert'] = 'enabled';
		}

		if($data['action'] == 'trigger_an_email'){
			$data['breach_action_value'] = '';
		}else{
			$data['breach_action_agent_manager'] = '';
		}

		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'cs_service_level_agreements', $data);
		if ($this->db->affected_rows() > 0) {
			$affected_rows++;
		}

		if($affected_rows > 0){
			return true;
		}
		return false;  
	}
	
	/**
	 * delete sla
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_sla($id)
	{	
		$affected_rows = 0;
		//get operations by routing id
		$sla_warnings = $this->get_sla_warning('', $id);
		foreach ($sla_warnings as $value) {
			$delete_result = $this->delete_sla_warning($value['id']);
			if($delete_result){
				$affected_rows++;
			}
		}

		//delete data
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'cs_service_level_agreements');
		if ($this->db->affected_rows() > 0) {
			$affected_rows++;
		}

		if($affected_rows > 0){
			return true;
		}
		return false;
	}

	/**
	 * get sla warning
	 * @param  boolean $id                         
	 * @param  boolean $service_level_agreement_id 
	 * @return [type]                              
	 */
	public function get_sla_warning($id=false, $service_level_agreement_id = false)
	{
		if (is_numeric($id)) {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'cs_service_level_agreement_warnings')->row();
		}

		if($service_level_agreement_id != false){
			$this->db->where('service_level_agreement_id', $service_level_agreement_id);
			$this->db->order_by('order_number', 'asc');

			return $this->db->get(db_prefix() . 'cs_service_level_agreement_warnings')->result_array();
		}

		if ($id == false) {
			return $this->db->query('select * from ' . db_prefix() . 'cs_service_level_agreement_warnings')->result_array();
		}
	}

	/**
	 * add sla warning
	 * @param [type] $data 
	 */
	public function add_sla_warning($data)
	{
		if($data['action'] == 'increase_the_priority'){
			$data['agent_manager'] = null;
		}
		
		$this->db->insert(db_prefix().'cs_service_level_agreement_warnings',$data);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			return $insert_id;
		}
		return false;
	}

	/**
	 * update sla warning
	 * @param  [type] $data 
	 * @param  [type] $id   
	 * @return [type]       
	 */
	public function update_sla_warning($data, $id)
	{
		$affected_rows=0;

		if($data['action'] == 'trigger_an_email'){
			$data['action_value'] = '';
		}else{
			$data['agent_manager'] = '';
		}

		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'cs_service_level_agreement_warnings', $data);
		if ($this->db->affected_rows() > 0) {
			$affected_rows++;
		}

		if($affected_rows > 0){
			return true;
		}
		return false;   
	}

	/**
	 * delete sla warning
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_sla_warning($id)
	{	
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'cs_service_level_agreement_warnings');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * change sla status
	 * @param  [type] $id     
	 * @param  [type] $status 
	 * @return [type]         
	 */
	public function change_sla_status($id, $status)
	{
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'cs_service_level_agreements', [
			'status' => $status,
		]);

		return true;
	}

	/**
	 * get max sla warning order number
	 * @param  [type] $service_level_agreement_id 
	 * @return [type]                             
	 */
	public function get_max_sla_warning_order_number($service_level_agreement_id)
	{
		$sql_where = "SELECT MAX(order_number) as current_order FROM ".db_prefix()."cs_service_level_agreement_warnings
		WHERE service_level_agreement_id = ".$service_level_agreement_id;
		$current_order = $this->db->query($sql_where)->row();
		if($current_order){
			return (int)$current_order->current_order+1;
		}
		return 1;
	}

	/**
	 * get category
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_category($id=false, $active='', $type = '')
	{
		if (is_numeric($id)) {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'cs_ticket_categories')->row();
		}

		if ($id == false) {
			if(new_strlen($active) > 0){
				$this->db->where('status', $active);
			}

			if(new_strlen($type) > 0){
				$this->db->where('type', $type);
			}
			return $this->db->get(db_prefix() . 'cs_ticket_categories')->result_array();
		}
	}

	/**
	 * add category
	 * @param [type] $data 
	 */
	public function add_category($data)
	{
		if(isset($data['auto_response'])){
			$data['auto_response'] = 'enabled';
		}else{
			$data['auto_response'] = 'disabled';
		}
		$data['status'] = 'enabled';
		$data['datecreated'] = date("Y-m-d H:i:s");
		$data['staffid'] = get_staff_user_id();

		$tags = '';
		if (isset($data['tags'])) {
			$tags = $data['tags'];
			unset($data['tags']);
		}

		$this->db->insert(db_prefix().'cs_ticket_categories',$data);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			/*habdle add tags*/
			handle_tags_save($tags, $insert_id, 'cs_category_tag');

			update_option('cs_ticket_category_number', get_option('cs_ticket_category_number')+1);
			
			return $insert_id;
		}
		return false;
	}

	/**
	 * update category
	 * @param  [type] $data 
	 * @param  [type] $id   
	 * @return [type]       
	 */
	public function update_category($data, $id)
	{
		$affected_rows=0;
		if(isset($data['auto_response'])){
			$data['auto_response'] = 'enabled';
		}else{
			$data['auto_response'] = 'disabled';
		}

		$data['dateupdated'] = date("Y-m-d H:i:s");

		/*handle update item tag*/
		if (isset($data['tags'])) {
            if (handle_tags_save($data['tags'], $id, 'cs_category_tag')) {
                $affectedRows++;
            }
			unset($data['tags']);
        }

		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'cs_ticket_categories', $data);
		if ($this->db->affected_rows() > 0) {
			$affected_rows++;
		}

		if($affected_rows > 0){
			return true;
		}
		return false;   
	}

	/**
	 * delete category
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_category($id)
	{	
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'cs_ticket_categories');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * change category status
	 * @param  [type] $id     
	 * @param  [type] $status 
	 * @return [type]         
	 */
	public function change_category_status($id, $status)
	{
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'cs_ticket_categories', [
			'status' => $status,
		]);

		return true;
	}

	/**
	 * get workflow
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_workflow($id=false)
	{
		if (is_numeric($id)) {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'cs_work_flows')->row();
		}

		if ($id == false) {
			return $this->db->query('select * from ' . db_prefix() . 'cs_work_flows')->result_array();
		}
	}

	/**
	 * add workflow
	 * @param [type] $data 
	 */
	public function add_workflow($data)
	{
		$data['status'] = 'enabled';
		$data['datecreated'] = date('Y-m-d H:i:s');
		$data['staffid'] = get_staff_user_id();

		$this->db->insert(db_prefix().'cs_work_flows',$data);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			update_option('cs_workflow_number', get_option('cs_workflow_number')+1);
			return $insert_id;
		}
		return false;
	}

	/**
	 * update workflow
	 * @param  [type] $data 
	 * @param  [type] $id   
	 * @return [type]       
	 */
	public function update_workflow($data, $id)
	{
		$affected_rows=0;

		if(isset($data['workflow'])){
			$data['workflow'] = json_encode($data['workflow']);
		}
		$data['dateupdated'] =  date('Y-m-d H:i:s');
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'cs_work_flows', $data);
		if ($this->db->affected_rows() > 0) {
			$affected_rows++;
		}

		if($affected_rows > 0){
			return true;
		}
		return false;   
	}

	/**
	 * delete workflow
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_workflow($id)
	{	
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'cs_work_flows');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * change workflow
	 * @param  [type] $id     
	 * @param  [type] $status 
	 * @return [type]         
	 */
	public function change_workflow_status($id, $status)
	{
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'cs_work_flows', [
			'status' => $status,
			'dateupdated' => date('Y-m-d H:i:s'),
		]);

		return true;
	}

	/**
	 * cs auto import imap tickets
	 * @return [type] 
	 */
	public function cs_auto_import_imap_tickets()
	{
		$cs_mail_scan_from_departments = get_option('cs_mail_scan_from_departments');

		if(new_strlen($cs_mail_scan_from_departments) > 0){
			$this->db->select('host,encryption,password,email,delete_after_import,imap_username,folder')
			->from(db_prefix() . 'departments')
			->where('host !=', '')
			->where('password !=', '')
			->where('email !=', '')
			->where('departmentid IN('.$cs_mail_scan_from_departments.')');
		}else{
			$this->db->select('host,encryption,password,email,delete_after_import,imap_username,folder')
			->from(db_prefix() . 'departments')
			->where('host !=', '')
			->where('password !=', '')
			->where('email !=', '');
		}

		$departments = $this->db->get()->result_array();

		foreach ($departments as $dept) {
			if (empty($dept['password'])) {
				continue;
			}

			$password = $this->encryption->decrypt($dept['password']);

			if (!$password) {
				log_activity('Failed to decrypt department password, navigate to Setup->Support->Departments and re-add the password for ' . $dept['email'] . ' department');

				continue;
			}

			$imap = new Imap(
				!empty($dept['imap_username']) ? $dept['imap_username'] : $dept['email'],
				$password,
				$dept['host'],
				$dept['encryption']
			);

			try {
				$connection = $imap->testConnection();
			} catch (ConnectionErrorException $e) {
				log_activity('Failed to connect to IMAP auto importing tickets for department ' . $dept['email'] . '.');

				continue;
			}
			$mailbox = $connection->getMailbox(
				empty($dept['folder']) ? 'INBOX' : $dept['folder']
			);

			$search = new SearchExpression();
			$search->addCondition(new Unseen);

			$messages = $mailbox->getMessages($search);
			$ticket_number = (int) get_option('cs_ticket_number');

			foreach ($messages as $message) {
				try {
					$body = $message->getBodyHtml() ?? $message->getBodyText();
					/*Some mail clients for the text/plain part add only Not set*/
					/*this is bad practice instead of leaving the text/pain part empty*/
					/*In this case, if it's Not set, we will use the HTML of the message*/
					if ($body == 'Not set') {
						$body = $message->getBodyHtml();
					}

					if (empty($body)) {
						$body = 'No message found';
					}

					if (
						class_exists('EmailReplyParser\EmailReplyParser')
						&& get_option('ticket_import_reply_only') === '1'
						&& (mb_substr_count($message->getSubject(), 'FWD:') == 0 && mb_substr_count($message->getSubject(), 'FW:') == 0)
					) {
						$parsedBody = \EmailReplyParser\EmailReplyParser::parseReply(
							$this->cs_prepare_imap_email_body_html($body)
						);

						$parsedBody = trim($parsedBody);

						/*For some emails this is causing an issue and not returning the email, instead is returning empty string*/
						/*In this case, only use parsed email reply if not empty*/

						if (! empty($parsedBody)) {
							$body = $parsedBody;
						}
					}

					$body                = $this->cs_prepare_imap_email_body_html($body);
					$data['attachments'] = [];

					foreach ($message->getAttachments() as $attachment) {
						$data['attachments'][] = [
							'filename' => $attachment->getFilename(),
							'data'     => $attachment->getDecodedContent(),
						];
					}

					$data['subject'] = $message->getSubject();
					$data['body']    = $body;

					$data['to'] = [];
					/*To is the department name*/
					$data['to'][] = $dept['email'];

					/*Check for CC*/
					if (count($message->getCc()) > 0) {
						foreach ($message->getCc() as $recipient) {
							$data['to'][] = $recipient->getAddress();
						}
					}

					$data['to']  = implode(',', $data['to']);
					$fromAddress = null;
					$fromName    = null;

					if ($message->getFrom()) {
						$fromAddress = $message->getFrom()->getAddress();
						$fromName    = $message->getFrom()->getName();
					}

					if (hooks()->apply_filters('imap_fetch_from_email_by_reply_to_header', true)) {
						$replyTo = $message->getReplyTo();

						if (count($replyTo) === 1) {
							$fromAddress = $replyTo[0]->getAddress();
							$fromName    = $replyTo[0]->getName() ?? $fromName;
						}
					}

					/**
					 * Check the the fromAddress is null, perhaps invalid address?
					 * @see https://github.com/ddeboer/imap/issues/370
					 */
					
					if (is_null($fromAddress)) {
						$message->markAsSeen();

						continue;
					}

					$data['email']    = $fromAddress;
					$data['fromname'] = $fromName;

					$data = hooks()->apply_filters('cs_imap_auto_import_ticket_data', $data, $message);

					try {
						$status = $this->insert_piped_ticket($data);

						if ($status == 'Ticket Imported Successfully' || $status == 'Ticket Reply Imported Successfully') {
							if ($dept['delete_after_import'] == 0) {
								$message->markAsSeen();
							} else {
								$message->delete();
								$connection->expunge();
							}
						} else {
							/*Set unseen message in all cases to prevent looping throught the message again*/
							$message->markAsSeen();
						}
					} catch (\Exception $e) {
						/*Set unseen message in all cases to prevent looping throught the message again*/
						$message->markAsSeen();
					}
				} catch (MessageDoesNotExistException $e) {
					continue;
				} catch (UnexpectedEncodingException $e) {
					$message->markAsSeen();

					continue;
				}
			}
		}
	}

	/**
	 * cs prepare imap email body html
	 * @param  [type] $body 
	 * @return [type]       
	 */
	private function cs_prepare_imap_email_body_html($body)
	{
		// Trim message
		$body = trim($body);
		$body = new_str_replace('&nbsp;', ' ', $body);
		// Remove html tags - strips inline styles also
		$body = trim(strip_html_tags($body, '<br/>, <br>, <a>'));
		// Once again do security
		$body = $this->security->xss_clean($body);
		// Remove duplicate new lines
		$body = preg_replace("/[\r\n]+/", "\n", $body);
		// new lines with <br />
		$body = preg_replace('/\n(\s*\n)+/', '<br />', $body);
		$body = preg_replace('/\n/', '<br>', $body);

		return $body;
	}

	/**
	 * insert piped ticket
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function insert_piped_ticket($data)
	{
		$data = hooks()->apply_filters('cs_piped_ticket_data', $data);

		$this->piping = true;
		$attachments  = $data['attachments'];
		$subject      = $data['subject'];
		// Prevent insert ticket to database if mail delivery error happen
		// This will stop createing a thousand tickets
		$system_blocked_subjects = [
			'Mail delivery failed',
			'failure notice',
			'Returned mail: see transcript for details',
			'Undelivered Mail Returned to Sender',
		];

		$subject_blocked = false;

		foreach ($system_blocked_subjects as $sb) {
			if (strpos('x' . $subject, $sb) !== false) {
				$subject_blocked = true;

				break;
			}
		}

		if ($subject_blocked == true) {
			return;
		}

		$message = $data['body'];
		$name    = $data['fromname'];

		$email   = $data['email'];
		$to      = $data['to'];
		$subject = $subject;
		$message = $message;

		$check_spam = $this->check_spam($email, $subject, $message);
		$mailstatus = '';
		// No spam found
		if ($check_spam == false || $check_spam == 'false') {
			$pos = strpos($subject, '[Ticket ID: ');
			if ($pos === false) {
			} else {
				$tid = substr($subject, $pos + 12);
				$tid = substr($tid, 0, strpos($tid, ']'));
				$this->db->where('id', $tid);
				$data = $this->db->get(db_prefix() . 'cs_tickets')->row();
				$tid  = $data->id;
			}
			$to            = trim($to);
			$toemails      = new_explode(',', $to);
			$department_id = false;
			$userid        = false;
			foreach ($toemails as $toemail) {
				if (!$department_id) {
					$this->db->where('email', trim($toemail));
					$data = $this->db->get(db_prefix() . 'departments')->row();
					if ($data) {
						$department_id = $data->departmentid;
						$to            = $data->email;
					}
				}
			}
			if (!$department_id) {
				$mailstatus = 'Department Not Found';
			} else {
				if ($to == $email) {
					$mailstatus = 'Blocked Potential Email Loop';
				} else {
					$message = trim($message);
					$this->db->where('active', 1);
					$this->db->where('email', $email);
					$result = $this->db->get(db_prefix() . 'staff')->row();
					if ($result) {
						if (isset($tid)) {
							$data            = [];
							$data['issue_summary'] = $message;
							$data['status']  = 'answered';

							if ($userid == false) {
								$data['created_id']  = $result->staffid;
								$data['created_type'] = 'staff';
							}

							$internal_reply = [];
							$internal_reply['ticket_id'] = $tid;
							$internal_reply['note_title'] = $subject;
							$internal_reply['note_details'] = $message;
							$internal_reply['staffid'] = $result->staffid;

							$reply_id = $this->add_ticket_internal_reply($internal_reply);

							if ($reply_id) {
								$mailstatus = 'Ticket Reply Imported Successfully';
							}
						} else {
							$mailstatus = 'Ticket ID Not Found';
						}
					} else {
						$this->db->where('email', $email);
						$result = $this->db->get(db_prefix() . 'contacts')->row();
			
						if ($result) {
							$userid    = $result->userid;
							$contactid = $result->id;
						}
						if($userid) {
							$filterdate = date('Y-m-d H:i:s', strtotime('-15 minutes'));
							$query      = 'SELECT count(*) as total FROM ' . db_prefix() . 'cs_tickets WHERE datecreated > "' . $filterdate . '" AND (client_id='. (int) $userid;
							
							$query .= ')';
							$result = $this->db->query($query)->row();
							if (1000 < $result->total) {
								$mailstatus = 'Exceeded Limit of 1000 Tickets within 15 Minutes';
							} else {
								if (isset($tid)) {
									$data            = [];
									$data['issue_summary'] = $message;
									$data['status']  = 'open';
									if ($userid) {
										$data['client_id']    = $userid;
										$data['created_id'] = $contactid;
										$data['created_type'] = 'client';

										$this->db->where('client_id', $userid);
										$this->db->where('id', $tid);
										$t = $this->db->get(db_prefix() . 'cs_tickets')->row();
										if (!$t) {
											$abuse = true;
										}
									}
									if (!isset($abuse)) {

										$client_internal_reply = [];
										$client_internal_reply['ticket_id'] = $tid;
										$client_internal_reply['note_title'] = $subject;
										$client_internal_reply['note_details'] = $message;
										$client_internal_reply['staffid'] = $contactid;

										$reply_id = $this->client_post_reply($client_internal_reply);
										if ($reply_id) {
											// Dont change this line
											$mailstatus = 'Ticket Reply Imported Successfully';
										}
									} else {
										$mailstatus = 'Ticket ID Not Found For User';
									}
								} else {
									//add ticket from email
									/*get category for Ticket: mapping by tag -> category default -> department*/
									$category_tags = $this->get_category_tag_filter();
									$categorize_auto = $this->categorize_auto_generated_from_email($department_id, $subject, $message);

									if(isset($categorize_auto['category_not_found'])){
										$mailstatus = 'Ticket Imported Failed (No related Category found)';
									}else{
										$sla_id = 0;
										$kpi_id = 0;
										$workflow_id = 0;
										$category = $this->get_category($categorize_auto['category']);
										if($category){
											$workflow = $this->get_workflow($category->work_flow_id);
											$workflow_id = $category->work_flow_id;
											if($workflow){
												$sla_id = $workflow->sla_id;
												$kpi_id = $workflow->kpi_id;
											}
										}

										$data               = [];
										$data['category_id'] = $categorize_auto['category'];
										$data['department_id'] = $department_id;
										$data['sla_id'] = $sla_id;
										$data['priority_level'] = $categorize_auto['priority'];
										$data['ticket_source'] = 'email';
										$data['issue_summary']    = $subject;
										$data['internal_note']    = $message;
										$data['code']    = get_option('cs_ticket_prefix').date('YmdHis');
										$data['client_id']  = $userid;
										$data['created_type']  = 'client';
										$data['status']  = 'open';
										$data['invoice_id']  = 0;
										$data['workflow_id']  = $workflow_id;
										$data['kpi_id']  = $kpi_id;

										// $tid = $this->add_ticket($data);

										$data['datecreated'] = date('Y-m-d H:i:s');
										$data['staffid'] = 0;

										$data['due_date'] = $this->cal_due_date_for_ticket($data['sla_id']);
										if($data['created_type'] == 'client'){
											/*assigned ticket for staff in department*/
											$data['assigned_id'] = $this->get_assignee_in_department($data['department_id']);
										}

										$this->db->insert(db_prefix().'cs_tickets',$data);
										$tid = $this->db->insert_id();

										if ($tid) {

											/*get workflow from category*/
											$category = $this->get_category($data['category_id']);
											if($category){
												$workflow = $this->get_workflow($category->work_flow_id);

												if($workflow){
													$ticket_workflows = [];
													$ticket_workflows['ticket_id'] = $tid;
													$ticket_workflows['workflow_id'] = $category->work_flow_id;
													$ticket_workflows['workflow'] = $workflow->workflow;
													$ticket_workflows['datecreated'] = date('Y-m-d H:i:s');
													$ticket_workflows['staffid'] = get_staff_user_id();
													$this->db->insert(db_prefix().'cs_ticket_workflows', $ticket_workflows);
												}
											}
										}
											// Dont change this line
										$mailstatus = 'Ticket Imported Successfully';
									}
								}
							}
						}else{
							$mailstatus = 'Ticket Imported Failed (Client not Exist)';
						}
					}
				}
			}
		}
		if ($mailstatus == '') {
			$mailstatus = 'Ticket Import Failed';
		}

		if ($check_spam == false || $check_spam == 'false') {
			$this->db->insert(db_prefix() . 'cs_tickets_pipe_logs', [
				'date'     => date('Y-m-d H:i:s'),
				'email_to' => $to,
				'name'     => $name ?: 'Unknown',
				'email'    => $email,
				'subject'  => $subject,
				'message'  => $message,
				'status'   => $mailstatus,
				'ticket_id'   => isset($tid) ? $tid : 0,
			]);
		}

		return $mailstatus;
	}

	/**
	 * check spam
	 * @param  [type] $email    
	 * @param  [type] $subject  
	 * @param  [type] $message  
	 * @param  [type] $rel_type 
	 * @return [type]           
	 */
	public function check_spam($email, $subject, $message)
	{
		$status       = false;
		$spam_filters = $this->get_mail_scan_rule(false, true);

		foreach ($spam_filters as $filter) {
			$type  = $filter['type'];
			$value = $filter['value'];

			if($filter['rel_type'] == 'blocked'){
				if ($type == 'sender') {
					if (strpos('x' . strtolower($email), strtolower($value))) {
						$status = 'Blocked Sender';
						return $status;
					}
				}
				if ($type == 'subject') {
					if (strpos('x' . strtolower($subject), strtolower($value))) {
						$status = 'Blocked Subject';
						return $status;
					}
				}
				if ($type == 'phrase') {
					if (strpos('x' . strtolower($message), strtolower($value))) {
						$status = 'Blocked Phrase';
						return $status;
					}
				}
			}
		}

		if($status == false){
			foreach ($spam_filters as $key => $filter) {
				$type  = $filter['type'];
				$value = $filter['value'];

				if($filter['rel_type'] == 'allowed'){
					if ($type == 'sender') {
						if (strtolower($value) == strtolower($email)) {
							return $status;
						}
					}
					if ($type == 'subject') {
						if (strpos('x' . strtolower($subject), strtolower($value))) {
							return $status;
						}
					}
					if ($type == 'phrase') {
						if (strpos('x' . strtolower($message), strtolower($value))) {
							return $status;
						}
					}
				}

				if($key+1 == count($spam_filters) ){
					/*not find blocked and allowed*/
					return $status;
				}
			}
		}

		return $status;
	}

	/**
	 * delete tickets pipe log
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_tickets_pipe_log($id)
	{	
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'cs_tickets_pipe_logs');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * get workflow by status
	 * @param  string $active      
	 * @param  string $workflow_id 
	 * @return [type]              
	 */
	public function get_workflow_by_status($active = '', $workflow_id = '')
	{
		if(new_strlen($workflow_id) > 0){
			$this->db->where('status = "'.$active.'" OR id = '.$workflow_id);
		}else{
			$this->db->where('status = "'.$active.'"');
		}
		return $this->db->get(db_prefix() . 'cs_work_flows')->result_array();
	}

	/**
	 * get ticket
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_ticket($id=false, $where = '')
	{
		if (is_numeric($id)) {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'cs_tickets')->row();
		}

		if ($id == false) {
			if(new_strlen($where) > 0){
				$this->db->where($where);
			}
			return $this->db->get(db_prefix() . 'cs_tickets')->result_array();
		}
	}

	/**
	 * add ticket
	 * @param [type] $data 
	 */
	public function add_ticket($data)
	{
		$sla_id = 0;
		$kpi_id = 0;
		$workflow_id = 0;
		$category = $this->get_category($data['category_id']);
		if($category){
			$workflow = $this->get_workflow($category->work_flow_id);
			$workflow_id = $category->work_flow_id;
			if($workflow){
				$sla_id = $workflow->sla_id;
				$kpi_id = $workflow->kpi_id;
			}
		}

		$data['workflow_id'] = $workflow_id;
		$data['sla_id'] = $sla_id;
		$data['kpi_id'] = $kpi_id;
		$data['status'] = 'open';
		$data['datecreated'] = date('Y-m-d H:i:s');
		$data['staffid'] = 0;

		if($data['created_type'] == 'staff'){
			$data['staffid'] = get_staff_user_id();
		}

		$data['due_date'] = $this->cal_due_date_for_ticket($data['sla_id']);

		if($data['created_type'] == 'client'){
			/*assigned ticket for staff in department*/
			$data['assigned_id'] = $this->get_assignee_in_department($data['department_id']);
		}
		$data['code'] = $this->create_code('ticket_code');


		$this->db->insert(db_prefix().'cs_tickets',$data);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			update_option('cs_ticket_number', get_option('cs_ticket_number')+1);

			//save workflow
			/*get workflow from category*/
			$category = $this->get_category($data['category_id']);
			if($category){
				$workflow = $this->get_workflow($category->work_flow_id);

				if($workflow){
					$ticket_workflows = [];
					$ticket_workflows['ticket_id'] = $insert_id;
					$ticket_workflows['workflow_id'] = $category->work_flow_id;
					$ticket_workflows['workflow'] = $workflow->workflow;
					$ticket_workflows['datecreated'] = date('Y-m-d H:i:s');
					$ticket_workflows['staffid'] = get_staff_user_id();
					$this->db->insert(db_prefix().'cs_ticket_workflows', $ticket_workflows);
				}
			}

			return $insert_id;
		}
		return false;
	}

	/**
	 * update ticket
	 * @param  [type] $data 
	 * @param  [type] $id   
	 * @return [type]       
	 */
	public function update_ticket($data, $id)
	{
		$affected_rows=0;
		$sla_id = 0;
		$kpi_id = 0;
		$workflow_id = 0;
		$category = $this->get_category($data['category_id']);
		if($category){
			$workflow = $this->get_workflow($category->work_flow_id);
			$workflow_id = $category->work_flow_id;
			$sla_id = $workflow->sla_id;
			$kpi_id = $workflow->kpi_id;
		}

		$data['workflow_id'] = $workflow_id;
		$data['sla_id'] = $sla_id;
		$data['kpi_id'] = $kpi_id;


		$data['dateupdated'] =  date('Y-m-d H:i:s');
		$data['last_update_time'] =  date('Y-m-d H:i:s');
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'cs_tickets', $data);
		if ($this->db->affected_rows() > 0) {
			$affected_rows++;
		}

		/*delete workflow*/
		$this->db->where('ticket_id', $id);
		$this->db->delete(db_prefix().'cs_ticket_workflows');

		//save workflow
		/*get workflow from category*/
		$category = $this->get_category($data['category_id']);
		if($category){
			$workflow = $this->get_workflow($category->work_flow_id);

			if($workflow){
				$ticket_workflows = [];
				$ticket_workflows['ticket_id'] = $id;
				$ticket_workflows['workflow_id'] = $category->work_flow_id;
				$ticket_workflows['workflow'] = $workflow->workflow;
				$ticket_workflows['datecreated'] = date('Y-m-d H:i:s');
				$ticket_workflows['staffid'] = get_staff_user_id();
				$this->db->insert(db_prefix().'cs_ticket_workflows', $ticket_workflows);
			}
		}

		if($affected_rows > 0){
			return true;
		}
		return false;   
	}

	/**
	 * delete ticket
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_ticket($id)
	{	
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'cs_tickets');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * get customer service activity log
	 * @param  [type] $ticket_id 
	 * @return [type]            
	 */
	public function get_customer_service_activity_log($ticket_id)
	{
		$warranty_process_detail_ids = [];
		$arr_activity_log = [];

		if(cs_get_status_modules('warranty_management')){ 
			$this->db->where('warranty_claim', $ticket_id);
			$warranty_process_details = $this->db->get(db_prefix() . 'wm_warranty_process_details')->result_array();
			if(count($warranty_process_details) > 0){
				foreach ($warranty_process_details as $warranty_process_detail) {
					$warranty_process_detail_ids[]  = $warranty_process_detail['id'];
				}
			}
		}

		$this->db->or_group_start();
		$this->db->where('rel_id', $ticket_id);
		$this->db->where('rel_type', 'warranty_claim');
		$this->db->group_end();

		if(cs_get_status_modules('warranty_management')){ 
			if(count($warranty_process_detail_ids) > 0){
				$this->db->or_group_start();
				$this->db->where('rel_id IN ('.implode(',', $warranty_process_detail_ids).')');
				$this->db->where('rel_type', 'warranty_claim_process');
				$this->db->group_end();
			}
		}

		$this->db->order_by('date', 'desc');
		$shipment_activity_log = $this->db->get(db_prefix().'wm_timeline_logs')->result_array();

		return $shipment_activity_log;
	}

	/**
	 * get ticket work flow
	 * @param  [type] $ticket_id 
	 * @return [type]            
	 */
	public function get_ticket_work_flow($ticket_id)
	{
		$this->db->where('ticket_id', $ticket_id);
		return $this->db->get(db_prefix().'cs_ticket_workflows')->row();
	}

	/**
	 * check workflow node log
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function check_workflow_node_log($data, $output = true)
	{
		$this->db->where('ticket_id', $data['ticket']->id);
		$this->db->where('client_id', $data['client']->userid);
		$this->db->where('node_id', $data['node']['id']);
		$logs = $this->db->get(db_prefix().'cs_ticket_flows_logs')->row();

		if($logs){
			if($output){
				return $logs->output;
			}
			return $logs;
		}

		return false;
	}

	/**
	 * save workflow node log
	 * @param  [type] $data   
	 * @param  string $output 
	 * @return [type]         
	 */
	public function save_workflow_node_log($data, $output = 'output_1'){
		$this->db->where('ticket_id', $data['ticket']->id);
		$this->db->where('client_id', $data['client']->userid);
		$this->db->where('node_id', $data['node']['id']);
		$logs = $this->db->get(db_prefix().'cs_ticket_flows_logs')->row();

		if(!$logs){

			$this->db->insert(db_prefix().'cs_ticket_flows_logs', [
				'ticket_id' => $data['ticket']->id, 
				'lead_id' => (isset($data['lead']) ? $data['lead']->id : 0), 
				'client_id' => (isset($data['client']) ? $data['client']->userid : 0), 
				'node_id' => $data['node']['id'], 
				'output' => $output, 
				'date_start' => date('Y-m-d H:i:s'), 
				'dateadded' => date('Y-m-d H:i:s'), 
				'stage_status' => isset($data['stage_status']) ? $data['stage_status'] : null, 
			]);
		}

		return true;
	}

	/**
	 * update workflow node log
	 * @param  [type] $data        
	 * @param  [type] $data_update 
	 * @return [type]              
	 */
	public function update_workflow_node_log($data, $data_update)
	{
		$this->db->where('ticket_id', $data['ticket']->id);

		if(isset($data['lead'])){
			$this->db->where('lead_id', $data['lead']->id);
		}else{
			$this->db->where('client_id', $data['client']->userid);
		}

		$this->db->where('node_id', $data['node']['id']);
		$logs = $this->db->get(db_prefix().'cs_ticket_flows_logs')->row();

		if($logs){
			$this->db->where('id', $logs->id);
			$this->db->update(db_prefix().'cs_ticket_flows_logs', $data_update);
		}

		return true;
	}

	/**
	 * get ticket detail data
	 * @param  [type] $ticket_id 
	 * @return [type]            
	 */
	public function get_ticket_detail_data($ticket_id)
	{
		$get_ticket = $this->get_ticket($ticket_id);
		$ticket_work_flow = $this->get_ticket_work_flow($ticket_id);

		if(!$ticket_work_flow){
			return false;
		}
		if(is_null($ticket_work_flow->workflow)){
			return false;
		}

		$workflow = json_decode(json_decode($ticket_work_flow->workflow), true);

		if(!isset($workflow['drawflow']['Home']['data'])){
			return false;
		}

		$workflow = $workflow['drawflow']['Home']['data'];

		$data = [];
		$data['ticket'] = $get_ticket;
		$data['client'] = $this->clients_model->get($get_ticket->client_id);
		$data['stages'] = [];

		foreach($workflow as $data_workflow){
			$data['node'] = $data_workflow;

			if($data_workflow['class'] == 'stage'){

				$check_workflow_node_log = $this->check_workflow_node_log($data);

				if(!$check_workflow_node_log){
					$data['stages'][] = [
						'id' => $data_workflow['id'],
						'name' => isset($data_workflow['data']['stage_name']) ? $data_workflow['data']['stage_name'] : '',
						'department' => isset($data_workflow['data']['department']) ? $data_workflow['data']['department'] : '',
						'priority' => isset($data_workflow['data']['priority']) ? $data_workflow['data']['priority'] : '',
						'sla' => isset($data_workflow['data']['sla']) ? $data_workflow['data']['sla'] : '',
						'staff_id' => isset($data_workflow['data']['staff_id']) ? $data_workflow['data']['staff_id'] : '',
						'stage_description' => isset($data_workflow['data']['stage_description']) ? $data_workflow['data']['stage_description'] : '',
						'status' => 'not_started',
					];
				}else{
					$check_workflow_node_log = $this->check_workflow_node_log($data, false);

					$data['stages'][] = [
						'id' => $check_workflow_node_log->id,
						'name' => isset($data_workflow['data']['stage_name']) ? $data_workflow['data']['stage_name'] : '',
						'department' => isset($data_workflow['data']['department']) ? $data_workflow['data']['department'] : '',
						'priority' => isset($data_workflow['data']['priority']) ? $data_workflow['data']['priority'] : '',
						'sla' => isset($data_workflow['data']['sla']) ? $data_workflow['data']['sla'] : '',
						'staff_id' => isset($data_workflow['data']['staff_id']) ? $data_workflow['data']['staff_id'] : '',
						'stage_description' => isset($data_workflow['data']['stage_description']) ? $data_workflow['data']['stage_description'] : '',
						'status' => $check_workflow_node_log->stage_status,
					];
				}
			}
		}
		return $data;
	}

	/**
	 * run workflow node
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function run_workflow_node($data)
	{
		
		$output = $this->check_workflow_node_log($data);

		if(!$output){
			switch ($data['node']['class']) {
				case 'stage':
				$data['stage_id'] = $data['node']['id'];
				$success = $this->handle_stage_node($data);

				if($success){
					if(!isset($data['stage_status'])){
						$data['stage_status'] = 'not_started';
					}
					$this->save_workflow_node_log($data);

					/*reset stage_status*/
					$data['stage_status'] = null;
					foreach ($data['node']['outputs']['output_1']['connections'] as $connection) {
						$data['node'] = $data['workflow'][$connection['node']];
						$this->run_workflow_node($data);
					}
				}

				break;

				case 'ticket_status':
				$success = $this->handle_ticket_status_node($data);

				if($success){
					$this->save_workflow_node_log($data);

					foreach ($data['node']['outputs']['output_1']['connections'] as $connection) {
						$data['node'] = $data['workflow'][$connection['node']];
						$this->run_workflow_node($data);
					}
				}

				break;

				case 'stage_status':
				if($data['stage_id'] == 0){
					$input_node = $data['node']['inputs']['input_1']['connections'];
					$data['stage_id'] = $this->get_pre_stage_id($data, $input_node);
				}
				$success = $this->handle_stage_status_node($data);

				if($success){
					$this->save_workflow_node_log($data);

					foreach ($data['node']['outputs']['output_1']['connections'] as $connection) {
						$data['node'] = $data['workflow'][$connection['node']];
						$this->run_workflow_node($data);
					}
				}

				break;

				case 'ticket_priority':
				$success = $this->handle_ticket_priority_node($data);

				if($success){
					$this->save_workflow_node_log($data);

					foreach ($data['node']['outputs']['output_1']['connections'] as $connection) {
						$data['node'] = $data['workflow'][$connection['node']];
						$this->run_workflow_node($data);
					}
				}

				break;

				case 'ticket_type':
				$success = $this->handle_ticket_type_node($data);

				if($success){
					$this->save_workflow_node_log($data);

					foreach ($data['node']['outputs']['output_1']['connections'] as $connection) {
						$data['node'] = $data['workflow'][$connection['node']];
						$this->run_workflow_node($data);
					}
				}

				break;

				case 'email_user':

				$success = $this->handle_email_user_node($data);

				if($success){
					$this->save_workflow_node_log($data);

					foreach ($data['node']['outputs']['output_1']['connections'] as $connection) {
						$data['node'] = $data['workflow'][$connection['node']];
						$this->run_workflow_node($data);
					}
				}

				break;

				case 'email_group':
				$success = $this->handle_email_group_node($data);

				if($success){
					$this->save_workflow_node_log($data);

					foreach ($data['node']['outputs']['output_1']['connections'] as $connection) {
						$data['node'] = $data['workflow'][$connection['node']];
						$this->run_workflow_node($data);
					}
				}

				break;

				case 'assignee':
				$success = $this->handle_assignee_node($data);

				if($success){
					$this->save_workflow_node_log($data);

					foreach ($data['node']['outputs']['output_1']['connections'] as $connection) {
						$data['node'] = $data['workflow'][$connection['node']];
						$this->run_workflow_node($data);
					}
				}

				break;
				
				case 'wait':
				$success = $this->handle_wait_node($data);

				if($success){
					$this->save_workflow_node_log($data);

					foreach ($data['node']['outputs']['output_1']['connections'] as $connection) {
						$data['node'] = $data['workflow'][$connection['node']];
						$this->run_workflow_node($data);
					}
				}

				break;
				

				case 'condition':
				if($data['stage_id'] == 0){
					$input_node = $data['node']['inputs']['input_1']['connections'];
					$data['stage_id'] = $this->get_pre_stage_id($data, $input_node);
				}
				$success = $this->handle_condition_node($data);
				if($success == 'output_1'){
					$this->save_workflow_node_log($data);

					foreach ($data['node']['outputs']['output_1']['connections'] as $connection) {
						$data['node'] = $data['workflow'][$connection['node']];
						$this->run_workflow_node($data);
					}

				}elseif($success == 'output_2'){
					$this->save_workflow_node_log($data, 'output_2');

					foreach ($data['node']['outputs']['output_2']['connections'] as $connection) {
						$data['node'] = $data['workflow'][$connection['node']];
						$this->run_workflow_node($data);
					}
				}

				break;


				default:
                    // code...
				break;
			}
		}else{

			foreach ($data['node']['outputs'][$output]['connections'] as $connection) {
				$data['node'] = $data['workflow'][$connection['node']];
				$this->run_workflow_node($data);
			}
		}

		return true;
	}

	/**
	 * customer service status mark as
	 * @param  [type] $status 
	 * @param  [type] $id     
	 * @param  [type] $type   
	 * @return [type]         
	 */
	public function customer_service_status_mark_as($status, $id, $type)
	{

		/*TODO*/
		$status_f = false;
		if($type == 'priority'){
			$this->db->where('id', $id);
			$this->db->update(db_prefix() . 'cs_tickets', ['priority_level' => $status]);
			if ($this->db->affected_rows() > 0) {
				$status_f = true;
				//write log
				$this->cs_ticket_log($id, 'cs_ticket', _l('cs_ticket_priority_change_to').': '. _l('cs_'.$status));

			}
		}elseif($type == 'ticket_type'){
			$this->db->where('id', $id);
			$this->db->update(db_prefix() . 'cs_tickets', ['ticket_type' => $status]);

			if ($this->db->affected_rows() > 0) {
				$status_f = true;
				//write log
				$this->cs_ticket_log($id, 'cs_ticket', _l('cs_ticket_type_change_to').': '. _l($status));

			}
		}elseif($type == 'ticket_status'){

			if($status == 'closed'){
				$time_spent = 0;
				$get_ticket = $this->get_ticket($id);
				if($get_ticket){
					$datecreated = $get_ticket->datecreated;
					$time_spent = strtotime(date('Y-m-d H:i:s')) - strtotime($datecreated);
					$time_spent = $time_spent/3600;
				}
				$this->db->where('id', $id);
				$this->db->update(db_prefix() . 'cs_tickets', ['status' => $status, 'time_spent' => $time_spent, 'last_update_time' => date('Y-m-d H:i:s')]);
			}else{
				$this->db->where('id', $id);
				$this->db->update(db_prefix() . 'cs_tickets', ['status' => $status]);
			}
			
			if ($this->db->affected_rows() > 0) {
				$status_f = true;
				//write log
				$this->cs_ticket_log($id, 'cs_ticket', _l('cs_ticket_status_change_to').': '. _l('cs_'.$status));

			}
		}elseif($type == 'stage_status'){
			$this->db->where('id', $id);
			$this->db->update(db_prefix() . 'cs_ticket_flows_logs', ['stage_status' => $status]);
			if ($this->db->affected_rows() > 0) {
				$status_f = true;
				//write log
				$this->cs_ticket_log($id, 'cs_ticket', _l('cs_stage_status_change_to').': '. _l('cs_'.$status));

			}
		}
		return $status_f;
	}

	/**
	 * cron manually
	 * @param  boolean $manually 
	 * @return [type]            
	 */
	public function cron_manually($manually = false)
	{
		if ($this->can_cron_run()) {
			$this->cs_auto_import_imap_tickets();

            update_option('last_cron_run', time());

			if ($manually == true) {
				$this->manually = true;

				if (!extension_loaded('suhosin')) {
					@ini_set('memory_limit', '-1');
				}

				log_activity('Cron Invoked Manually');
			}

			/*Finally send any emails in the email queue - if enabled and any*/

			$this->email->send_queue();

			$last_email_queue_retry = get_option('last_email_queue_retry');

			$retryQueue = hooks()->apply_filters('cron_retry_email_queue_seconds', 600);

			/*Retry queue failed emails every 10 minutes*/
			if ($last_email_queue_retry == '' || (time() > ($last_email_queue_retry + $retryQueue))) {
				$this->email->retry_queue();
				update_option('last_email_queue_retry', time());
			}

			app_maybe_delete_old_temporary_files();

            /*For all cases try to release the lock after everything is finished*/
			$this->lockHandle();
		}
	}

	/**
	 * lockHandle
	 * @return [type] 
	 */
	private function lockHandle()
	{
		if ($this->lock_handle) {
			flock($this->lock_handle, LOCK_UN);
			fclose($this->lock_handle);
			$this->lock_handle = null;
		}
	}

	/**
	 * can cron run
	 * @return [type] 
	 */
	private function can_cron_run()
	{
		return true;

		if ($this->app->is_db_upgrade_required()) {
			return false;
		}

		return ($this->lock_handle && flock($this->lock_handle, LOCK_EX | LOCK_NB))
		|| (defined('APP_DISABLE_CRON_LOCK') && APP_DISABLE_CRON_LOCK);
	}

	/**
	 * add ticket reply
	 * @param [type] $data 
	 */
	public function add_ticket_reply($data)
	{
		$data_reply = [];

		foreach ($data['formdata'] as $formdata) {
			if($formdata['name'] == 'to_staff_id'){
				$data['to_staff_id'] = $formdata['value'];
			}
			if($formdata['name'] == 'response'){
				$data['response'] = $formdata['value'];
			}
			if($formdata['name'] == 'ticket_status'){
				$data['ticket_status'] = $formdata['value'];
			}
			if($formdata['name'] == 'resolution'){
				$data['resolution'] = $formdata['value'];
			}
		}
		
		$data_reply['ticket_id'] = isset($data['ticket_id']) ? $data['ticket_id'] : '';
		$data_reply['to_staff_id'] = isset($data['to_staff_id']) ? $data['to_staff_id'] : '';
		$data_reply['to_email'] = isset($data['to_staff_id']) ? cs_get_staff_email($data['to_staff_id']) : 0;
		$data_reply['response'] = isset($data['response']) ? $data['response'] : '';

		if(isset($data['ticket_status'])){
			$data_reply['ticket_status'] = 'closed';

			/*update ticket status*/
			$time_spent = 0;
			$get_ticket = $this->get_ticket($data['ticket_id']);
			if($get_ticket){
				$datecreated = $get_ticket->datecreated;
				$time_spent = strtotime(date('Y-m-d H:i:s')) - strtotime($datecreated);
				$time_spent = $time_spent/3600;
			}

			$this->db->where('id', $data['ticket_id']);
			$this->db->update(db_prefix().'cs_tickets', ['status' => 'closed', 'last_update_time' => date('Y-m-d H:i:s'), 'dateupdated' => date('Y-m-d H:i:s'), 'time_spent' => $time_spent]);
		}
		if(isset($data['resolution'])){
			$data_reply['resolution'] = 'reply_as_resolution';

			/*update ticket resolution*/
			$this->db->where('id', $data['ticket_id']);
			$this->db->update(db_prefix().'cs_tickets', ['resolution' => $data['response'], 'last_update_time' => date('Y-m-d H:i:s'), 'dateupdated' => date('Y-m-d H:i:s')]);

		}
		$data_reply['datecreated'] = date('Y-m-d H:i:s');
		$data_reply['staffid'] = get_staff_user_id();

		$this->db->insert(db_prefix().'cs_ticket_action_post_replies', $data_reply);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {

			/*TODO send mail to staff*/
			return $insert_id;
		}
		return false;
	}

	/**
	 * add ticket internal reply
	 * @param [type] $data 
	 */
	public function add_ticket_internal_reply($data)
	{
		$data_reply = [];

		$data_reply['ticket_id'] = $data['ticket_id'];
		$data_reply['note_title'] = $data['note_title'];
		$data_reply['note_details'] = $data['note_details'];
		$data_reply['ticket_status'] = $data['cs_ticket_status'];
		$data_reply['resolution'] = isset($data['response']) ? $data['response'] : '';

		if(isset($data['cs_ticket_status']) && new_strlen($data['cs_ticket_status']) > 0){
			$data_reply['ticket_status'] = $data['cs_ticket_status'];

			/*update ticket status*/
			$time_spent = 0;
			$get_ticket = $this->get_ticket($data['ticket_id']);
			if($get_ticket){
				$time_spent = $get_ticket->time_spent;
				if($data['cs_ticket_status'] == 'closed'){
					$datecreated = $get_ticket->datecreated;
					$time_spent = strtotime(date('Y-m-d H:i:s')) - strtotime($datecreated);
					$time_spent = $time_spent/3600;
				}
			}

			$this->db->where('id', $data['ticket_id']);
			$this->db->update(db_prefix().'cs_tickets', ['status' => $data['cs_ticket_status'], 'last_update_time' => date('Y-m-d H:i:s'), 'dateupdated' => date('Y-m-d H:i:s'), 'time_spent' => $time_spent]);
		}
		if(isset($data['internal_resolution'])){
			$data_reply['resolution'] = $data['note_details'];

			/*update ticket resolution*/
			$this->db->where('id', $data['ticket_id']);
			$this->db->update(db_prefix().'cs_tickets', ['resolution' => $data['note_details'], 'last_update_time' => date('Y-m-d H:i:s'), 'dateupdated' => date('Y-m-d H:i:s')]);

		}
		$data_reply['datecreated'] = date('Y-m-d H:i:s');
		if(isset($data['staffid'])){
			$data_reply['staffid'] = get_staff_user_id();
		}else{
			$data_reply['staffid'] = get_staff_user_id();
		}

		$this->db->insert(db_prefix().'cs_ticket_action_post_internal_notes', $data_reply);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			$this->update_ticket_first_reply_time($data['ticket_id']);

			/*TODO send mail to client*/
			return $insert_id;
		}
		return false;
	}

	/**
	 * add department transfer
	 * @param [type] $data 
	 */
	public function add_department_transfer($data)
	{
		$data_reply = [];

		$data_reply['comment'] = $data['comment'];
		$data_reply['department_id'] = $data['department_id'];
		$data_reply['ticket_id'] = $data['ticket_id'];
		$data_reply['datecreated'] = date('Y-m-d H:i:s');
		$data_reply['staffid'] = get_staff_user_id();

		$this->db->insert(db_prefix().'cs_ticket_action_change_departments', $data_reply);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {

			if(is_numeric($data['department_id'])){
				/*update ticket resolution*/
				$this->db->where('id', $data['ticket_id']);
				$this->db->update(db_prefix().'cs_tickets', ['department_id' => $data['department_id'], 'last_update_time' => date('Y-m-d H:i:s'), 'dateupdated' => date('Y-m-d H:i:s')]);
			}

			/*TODO send mail to client*/
			return $insert_id;
		}
		return false;
	}

	/**
	 * add ticket reassign
	 * @param [type] $data 
	 */
	public function add_ticket_reassign($data)
	{
		$data_reply = [];

		$data_reply['comment'] = $data['re_comment'];
		$data_reply['assignee_id'] = $data['assignee_id'];
		$data_reply['ticket_id'] = $data['ticket_id'];
		$data_reply['datecreated'] = date('Y-m-d H:i:s');
		$data_reply['staffid'] = get_staff_user_id();

		$this->db->insert(db_prefix().'cs_ticket_action_reassign_tickets', $data_reply);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {

			if(is_numeric($data['assignee_id'])){
				/*update ticket resolution*/
				$this->db->where('id', $data['ticket_id']);
				$this->db->update(db_prefix().'cs_tickets', ['assigned_id' => $data['assignee_id'], 'last_update_time' => date('Y-m-d H:i:s'), 'dateupdated' => date('Y-m-d H:i:s')]);
			}

			/*TODO send mail to client*/
			return $insert_id;
		}
		return false;
	}

	/**
	 * get ticket history
	 * @param  [type] $ticket_id 
	 * @return [type]            
	 */
	public function get_ticket_history($ticket_id, $client = false)
	{
		$histories = [];

		if(!$client){

			$this->db->where('ticket_id', $ticket_id);
			$action_post_replies = $this->db->get(db_prefix() . 'cs_ticket_action_post_replies')->result_array();
			foreach ($action_post_replies as $value) {
				$value['strdate'] = strtotime($value['datecreated']);
				$histories[strtotime($value['datecreated'])] = $value;
			}
		}

		$this->db->where('ticket_id', $ticket_id);
		$action_post_replies = $this->db->get(db_prefix() . 'cs_ticket_action_post_internal_notes')->result_array();
		foreach ($action_post_replies as $value) {
			$value['strdate'] = strtotime($value['datecreated']);
			$histories[strtotime($value['datecreated'])] = $value;
		}

		if(!$client){
			$this->db->where('ticket_id', $ticket_id);
			$action_post_replies = $this->db->get(db_prefix() . 'cs_ticket_action_change_departments')->result_array();
			foreach ($action_post_replies as $value) {
				$value['strdate'] = strtotime($value['datecreated']);
				$histories[strtotime($value['datecreated'])] = $value;
			}

			$this->db->where('ticket_id', $ticket_id);
			$action_post_replies = $this->db->get(db_prefix() . 'cs_ticket_action_reassign_tickets')->result_array();
			foreach ($action_post_replies as $value) {
				$value['strdate'] = strtotime($value['datecreated']);
				$histories[strtotime($value['datecreated'])] = $value;
			}
		}

		$this->db->where('rel_id', $ticket_id);
		$this->db->where('rel_type', 'cs_ticket');
		$ticket_timeline_logs = $this->db->get(db_prefix() . 'cs_ticket_timeline_logs')->result_array();

		$second = 1;
		foreach ($ticket_timeline_logs as $value) {
			if(isset($histories[strtotime($value['date'])])){
				$value['strdate'] = strtotime($value['date']);

				$histories[strtotime($value['date']."+ ".$second." seconds")] = $value;
				$second ++;
			}else{

				$value['strdate'] = strtotime($value['date']);

				$histories[strtotime($value['date'])] = $value;
			}
		}

		usort($histories, function ($item1, $item2) {
			return $item2['strdate'] <=> $item1['strdate'];
		});


		return $histories;
	}

	/**
	 * delete ticket history
	 * @param  [type] $id   
	 * @param  [type] $type 
	 * @return [type]       
	 */
	public function delete_ticket_history($id, $type)
	{
		if($type == 'assign_ticket'){
			$this->db->where('id', $id);
			$this->db->delete(db_prefix() . 'cs_ticket_action_reassign_tickets');
			if ($this->db->affected_rows() > 0) {
				return true;
			}
		}elseif($type == 'transfer_department'){
			$this->db->where('id', $id);
			$this->db->delete(db_prefix() . 'cs_ticket_action_change_departments');
			if ($this->db->affected_rows() > 0) {
				return true;
			}
		}elseif($type == 'post_internal'){
			$this->db->where('id', $id);
			$this->db->delete(db_prefix() . 'cs_ticket_action_post_internal_notes');
			if ($this->db->affected_rows() > 0) {
				return true;
			}	if ($this->db->affected_rows() > 0) {
				return true;
			}
		}elseif($type == 'post_reply'){
			$this->db->where('id', $id);
			$this->db->delete(db_prefix() . 'cs_ticket_action_post_replies');
			if ($this->db->affected_rows() > 0) {
				return true;
			}
		}elseif($type == 'ticket_timeline_log'){
			$this->db->where('id', $id);
			$this->db->delete(db_prefix() . 'cs_ticket_timeline_logs');
			if ($this->db->affected_rows() > 0) {
				return true;
			}
		}
		return false;
	}

	/**
	 * get customer service email template
	 * @return [type] 
	 */
	public function get_customer_service_email_template()
	{
		$emailtemplate_ids = [];
		$sql_where = 'SELECT max(emailtemplateid) as emailtemplateid FROM '.db_prefix().'emailtemplates where type = "customer_service_email_template" GROUP BY type, slug';
		$emailtemplates = $this->db->query($sql_where)->result_array();

		foreach ($emailtemplates as $value) {
		    $emailtemplate_ids[] = $value['emailtemplateid'];
		}

		return $emailtemplate_ids;
	}

	/**
	 * cs add email template
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function cs_add_email_template($data)
	{

		$main_id = '';
		$available_languages = $this->app->get_available_languages();

		$type = $data['type'];
		$name = $data['name'];
		$fromname = $data['fromname'];
		$fromemail = $data['fromemail'];
		$subject = $data['subject'][0];
		$message = $data['message'][0];
		$slug = slug_it($subject).'-'.strtotime(date('Y-m-d H:i:s'));

		if($data['plaintext'] == 'on'){
			$plaintext = 1;
		}else{
			$plaintext = 0;
		}

		if($data['disabled'] == 'on'){
			$active = 0;
		}else{
			$active = 1;
		}

		if (total_rows('emailtemplates', ['slug' => $slug]) > 0) {
			return false;
		}

		$emailtemplate = [];
		$emailtemplate['subject']   = $subject;
		$emailtemplate['message']   = $message;
		$emailtemplate['type']      = $type;
		$emailtemplate['name']      = $name;
		$emailtemplate['slug']      = $slug;
		$emailtemplate['language']  = 'english';
		$emailtemplate['active']    = $active;
		$emailtemplate['plaintext'] = $plaintext;
		$emailtemplate['fromname'] = $fromname;

		$this->db->insert(db_prefix().'emailtemplates', $emailtemplate);
		$main_id = $this->db->insert_id();

		$insert_batch_data = [];
		foreach ($available_languages as $language) {
			$emailtemplate['language']  = $language;

			$insert_batch_data[] = $emailtemplate; 

		}
		if(count($insert_batch_data) > 0){
			$this->db->insert_batch(db_prefix().'emailtemplates', $insert_batch_data);
		}

		return $main_id;
	}

	/**
	 * delete email template
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_email_template($id)
	{
		$this->db->where('emailtemplateid', $id);
		$emailtemplates = $this->db->get(db_prefix().'emailtemplates')->row();
		if($emailtemplates){
			$this->db->where('type', 'customer_service_email_template');
			$this->db->where('slug', $emailtemplates->slug);
			$this->db->delete(db_prefix() . 'emailtemplates');
			if ($this->db->affected_rows() > 0) {
				return true;
			}
			return false;

		}
	}

	/**
	 * get email template slug
	 * @return [type] 
	 */
	public function get_email_template_slug()
	{

		$email_template_slug = [];
		$email_template_ids = $this->get_customer_service_email_template();
		if(count($email_template_ids) > 0){

			$sql_where = 'SELECT * FROM '.db_prefix().'emailtemplates where emailtemplateid IN(' . implode(', ', $email_template_ids) .') AND active = 1';
			$emailtemplates = $this->db->query($sql_where)->result_array();

			foreach ($emailtemplates as $value) {
				$email_template_slug[] = [
					'slug' => $value['slug'],
					'name' => $value['name'],
				];
			    
			}
		}
		return $email_template_slug;
	}

	/**
	 * cs get staff by department
	 * @param  [type] $departmentid 
	 * @return [type]               
	 */
	public function cs_get_staff_by_department($department_id)
	{
		$staff_option = '';
		if(cs_get_status_modules('hr_profile')){
			$this->load->model('hr_profile/hr_profile_model');
			$departmentgroup = $this->hr_profile_model->get_staff_in_deparment($department_id);
			if (count($departmentgroup) > 0) {

				$this->db->where(db_prefix().'staff.staffid IN (SELECT staffid FROM '.db_prefix().'staff_departments WHERE departmentid IN (' . implode(', ', $departmentgroup) . '))');
				$staffs = $this->db->get(db_prefix().'staff')->result_array();
			}
		}else{
			$staff_ids = [];
			$this->db->select('DISTINCT staffid');
			$this->db->where('departmentid', $department_id);
			$staff_departments = $this->db->get(db_prefix().'staff_departments')->result_array();

			foreach ($staff_departments as $value) {
				$staff_ids[] = $value['staffid'];
			}

			if (count($staff_ids) > 0) {
				$this->db->where(db_prefix().'staff.staffid IN (' . implode(', ', $staff_ids) . ')');
				$staffs = $this->db->get(db_prefix().'staff')->result_array();
			}
		}

		if(isset($staffs)){
			foreach ($staffs as $staff) {
				$staff_option .= '<option value="' . $staff['staffid'] . '">' . $staff['firstname'].' '.$staff['lastname'] . '</option>';
			}
		}

		return $staff_option;
	}

	/**
	 * cs cron ticket
	 * @return [type] 
	 */
	public function cs_cron_ticket()
	{
		$this->db->where('status != "closed"');
		$tickets = $this->db->get(db_prefix().'cs_tickets')->result_array();

		foreach($tickets as $ticket){
			$this->run_ticket($ticket['id']);
		}
		return true;
	}

	/**
	 * run ticket
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function run_ticket($id)
	{
		$get_ticket = $this->get_ticket($id);
		$ticket_workflow = $this->get_ticket_work_flow($id);

		if($ticket_workflow){
			$workflow = json_decode(json_decode($ticket_workflow->workflow), true);
		}else{
			return false;
		}

		if(!isset($workflow['drawflow']['Home']['data'])){
			return false;
		}

		$workflow = $workflow['drawflow']['Home']['data'];
		$data_flow = [];

		$data = [];
		$data['workflow'] = $workflow;
		$data['ticket'] = $get_ticket;
		$data['ticket_id'] = $get_ticket->id;
		$data['client'] = $this->clients_model->get($get_ticket->client_id);
		$data['stages'] = [];

		foreach($workflow as $data_workflow){
			$data['node'] = $data_workflow;

			if($data_workflow['class'] == 'flow_start'){

				if(!$this->check_workflow_node_log($data)){
					$this->save_workflow_node_log($data);
				}

				foreach ($data_workflow['outputs']['output_1']['connections'] as $connection) {
					$data['node'] = $workflow[$connection['node']];
					$data['stage_id'] = 0;

					$this->run_workflow_node($data);
				}
			}
		}

		return true;
	}

	/**
	 * handle email user node
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function handle_email_user_node($data)
	{

		if(isset($data['node']['data']['email_user_template']) && new_strlen($data['node']['data']['email_user_template']) > 0 && isset($data['node']['data']['email_user'])){

			$email_template_slug = $data['node']['data']['email_user_template'];
			$email_user = $data['node']['data']['email_user'];
			$language = 'english';
			$email = '';
			if($email_user == 'requester'){
				$this->update_ticket_first_reply_time($data['ticket']->id);

				//send email to client
				$get_client_default_language = get_client_default_language($data['ticket']->client_id);
				if(new_strlen($get_client_default_language) > 0){
					$language = $get_client_default_language;
				}

				if($data['ticket']->created_type == 'client'){
					$email = cs_get_contact_email('client', $data['ticket']->client_id);
				}else{

					$primary_contact_user_id = get_primary_contact_user_id($data['ticket']->client_id);
					$email = cs_get_contact_email('contact', $primary_contact_user_id);
				}
				
				if(new_strlen($email) > 0){
					$cs_send_email = $this->cs_send_email($email, $email_template_slug, $language, $data, 'requester');
					return $cs_send_email;

				}

			}elseif($email_user == 'ticket_assigned_user'){
				$staff_default_language = get_staff_default_language($data['ticket']->assigned_id);
				if(new_strlen($staff_default_language) > 0){
					$language = $staff_default_language;
				}

				$email = cs_get_staff_email($data['ticket']->assigned_id);
				if(new_strlen($email) > 0){
					$cs_send_email = $this->cs_send_email($email, $email_template_slug, $language, $data, 'staff');

					return $cs_send_email;
				}
			}else{
				//send email to assigned_user
				//send email to specific staff
				
				$staff_default_language = get_staff_default_language($email_user);
				if(new_strlen($staff_default_language) > 0){
					$language = $staff_default_language;
				}

				$email = cs_get_staff_email($email_user);
				if(new_strlen($email) > 0){
					$cs_send_email = $this->cs_send_email($email, $email_template_slug, $language, $data, 'staff');

					return $cs_send_email;
				}
			}
		}

		return false;
	}

	/**
	 * handle stage node
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function handle_stage_node($data)
	{
		return true;
	}

	/**
	 * handle ticket status node
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function handle_ticket_status_node($data)
	{
		if(isset($data['node']['data'])){
			if(isset($data['node']['data']['ticket_status'])){
				$this->db->where('id', $data['ticket_id']);
				$this->db->update(db_prefix().'cs_tickets', ['status' => $data['node']['data']['ticket_status']]);
				if ($this->db->affected_rows() > 0) {

					//write log
					$this->cs_ticket_log($data['ticket_id'], 'cs_ticket', _l('cs_ticket_status_change_to').': '. _l('cs_'.$data['node']['data']['ticket_status']));

					return true;
				}

			}
		}
		return false; 
	}

	/**
	 * handle stage status node
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function handle_stage_status_node($data)
	{
		if(isset($data['node']['data'])){
			if(isset($data['node']['data']['stage_status'])){
				$this->db->where('ticket_id', $data['ticket_id']);
				$this->db->where('node_id', $data['stage_id']);
				$this->db->update(db_prefix().'cs_ticket_flows_logs', ['stage_status' => $data['node']['data']['stage_status']]);

				if ($this->db->affected_rows() > 0) {
					//write log
					$this->cs_ticket_log($data['ticket_id'], 'cs_ticket', _l('cs_stage_status_change_to').': '. _l('cs_'.$data['node']['data']['stage_status']));

					return true;
				}

			}
		}
		return false; 
	}

	/**
	 * handle ticket priority node
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function handle_ticket_priority_node($data)
	{
    	if(isset($data['node']['data'])){
			if(isset($data['node']['data']['ticket_priority'])){
				$this->db->where('id', $data['ticket_id']);
				$this->db->update(db_prefix().'cs_tickets', ['priority_level' => $data['node']['data']['ticket_priority']]);
				if ($this->db->affected_rows() > 0) {

					//write log
					$this->cs_ticket_log($data['ticket_id'], 'cs_ticket', _l('cs_ticket_priority_change_to').': '. _l('cs_'.$data['node']['data']['ticket_priority']));

					return true;
				}

			}
		}
		return false;
	}

	/**
	 * handle ticket type node
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function handle_ticket_type_node($data)
	{
		if(isset($data['node']['data'])){
			if(isset($data['node']['data']['ticket_type'])){
				$this->db->where('id', $data['ticket_id']);
				$this->db->update(db_prefix().'cs_tickets', ['ticket_type' => $data['node']['data']['ticket_type']]);
				if ($this->db->affected_rows() > 0) {

					//write log
					$this->cs_ticket_log($data['ticket_id'], 'cs_ticket', _l('cs_ticket_type_change_to').': '. _l($data['node']['data']['ticket_type']));

					return true;
				}

			}
		}
		return false;
	}

	/**
	 * handle email group node
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function handle_email_group_node($data)
	{
		if(isset($data['node']['data']['email_group_template']) && new_strlen($data['node']['data']['email_group_template']) > 0 ){

			$email_template_slug = $data['node']['data']['email_group_template'];
			$email_user = $data['node']['data']['email_user'];
			$language = 'english';
			$email = '';

				//send email to assigned_user
				//send email to specific staff

			$staff_default_language = get_staff_default_language($data['ticket']->assigned_id);
			if(new_strlen($staff_default_language) > 0){
				$language = $staff_default_language;
			}

			$email = cs_get_department_email($data['ticket']->department_id);
			if(new_strlen($email) > 0){
				$cs_send_email = $this->cs_send_email($email, $email_template_slug, $language, $data, 'department');

				return $cs_send_email;
			}

		}

		return false;
	}

	/**
	 * handle_assignee_node
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function handle_assignee_node($data)
	{
		if(isset($data['node']['data'])){
			if(isset($data['node']['data']['assignee_department_id']) && isset($data['node']['data']['assignee_id']) ){
				$this->db->where('id', $data['ticket_id']);
				$this->db->update(db_prefix().'cs_tickets', ['department_id' => $data['node']['data']['assignee_department_id'],'assigned_id' => $data['node']['data']['assignee_id'] ]);
				if ($this->db->affected_rows() > 0) {

					//write log
					$this->cs_ticket_log($data['ticket_id'], 'cs_ticket', _l('ticket_assigness_department').': '. cs_get_department_name($data['node']['data']['assignee_department_id']));
					$this->cs_ticket_log($data['ticket_id'], 'cs_ticket', _l('cs_ticket_assigned_user').': '. get_staff_full_name($data['node']['data']['assignee_id']));

					return true;
				}

			}
		}
		return false;
	}

	/**
	 * handle wait node
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function handle_wait_node($data)
	{
		if(isset($data['node']['data']['wait_type']) && isset($data['node']['data']['wait_duration']) ){
			$pre_node = $data['node']['inputs']['input_1']['connections'];

			if(count($pre_node) > 0){
				foreach ($pre_node as $value) {
					$this->db->where('ticket_id', $data['ticket']->id);
					$this->db->where('client_id', $data['client']->userid);
					$this->db->where('node_id', $value['node']);
					$logs = $this->db->get(db_prefix().'cs_ticket_flows_logs')->row();

					if($logs){
						$date_start = $logs->date_start;

						if($data['node']['data']['wait_type'] == 'days'){
							$wait_date = strtotime($date_start."+ ".$data['node']['data']['wait_duration']." days");
						}elseif($data['node']['data']['wait_type'] == 'hours'){
							$wait_date = strtotime($date_start."+ ".$data['node']['data']['wait_duration']." hours");
						}else{
							$wait_date = strtotime($date_start."+ ".$data['node']['data']['wait_duration']." minutes");
						}


						if($wait_date <= strtotime(date('Y-m-d H:i:s'))){
							return true;
						}
					}
				}
			}
		}

		return false;
	}

	/**
	 * handle condition node
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function handle_condition_node($data)
	{

		if(!isset($data['node']['data']['name_of_variable']) || !isset($data['node']['data']['condition']) || !isset($data['node']['data']['value_of_variable']) ){
			return 'output_2';
		}
		$system_value = '';
		$name_of_variable = $data['node']['data']['name_of_variable'];
		$condition = $data['node']['data']['condition'];
		$value_of_variable = $data['node']['data']['value_of_variable'];

		if($name_of_variable == 'stage_status'){
			$this->db->where('id', $data['stage_id']);
			$stage_data = $this->db->get(db_prefix().'cs_ticket_workflows')->row();
			if($stage_data){
				$system_value = $stage_data->stage_status;
			}
		}else{

			$ticket = $data['ticket'];
			switch ($name_of_variable) {
				case 'ticket_id':
					$system_value = $ticket->id;
					break;

				case 'requester_name':
					if($ticket->created_type == 'client'){
						$system_value = get_contact_full_name('contact', get_primary_contact_user_id($ticket->client_id));
					}else{
						$system_value = get_contact_full_name('contact', $ticket->created_id);
					}

					break;

				case 'requester_email':
					if($ticket->created_type == 'client'){
						$email = cs_get_contact_email('client', $ticket->client_id);
					}else{
						$email = cs_get_contact_email('contact', $ticket->created_id);
					}
					break;

				case 'ticket_status':
					$system_value = _l('cs_'.$ticket->status);
					break;

				case 'ticket_priority':
					$system_value = _l('cs_'.$ticket->priority_level);
					break;

				case 'ticket_type':
					$system_value = _l($ticket->ticket_type);
					break;

				case 'ticket_subject':
					$system_value = $ticket->issue_summary;
					break;
				
				default:
					// code...
					break;
			}
			// $ticket
		}

		switch ($condition) {
			case 'equals':
			if($value_of_variable == $system_value){
				return 'output_1';
			}
			break;
			case 'not_equal':
			if($value_of_variable != $system_value){
				return 'output_1';
			}
			break;
			case 'greater_than':
			if($value_of_variable = $system_value){
				return 'output_1';
			}
			break;
			case 'greater_than_or_equal':
			if($value_of_variable <= $system_value){
				return 'output_1';
			}
			break;
			case 'less_than':
			if($value_of_variable > $system_value){
				return 'output_1';
			}
			break;
			case 'less_than_or_equal':
			if($value_of_variable <= $system_value){
				return 'output_1';
			}
			break;
			case 'empty':
			if($system_value == ''){
				return 'output_1';
			}
			break;
			case 'not_empty':
			if($system_value != ''){
				return 'output_1';
			}
			if(isset($data['lead'])){
				$where .= db_prefix().'leads.'.$filter['type'].' != ""';
			}else{
				$where .= db_prefix().'clients.'.$filter['type'].' != ""';
			}
			break;
			case 'like':
			if (!(strpos(strtoupper($system_value), strtoupper($value_of_variable)) === false)) {
				return 'output_1';
			}
			break;
			case 'not_like':
			if (!(strpos(strtoupper($system_value), strtoupper($value_of_variable)) !== false)) {
				return 'output_1';
			}
			break;
			default:
			break;
		}

		return 'output_2';
	}

	/**
	 * cs send email
	 * @param  [type] $email               
	 * @param  [type] $email_template_slug 
	 * @param  [type] $language            
	 * @param  [type] $data                
	 * @return [type]                      
	 */
	public function cs_send_email($email, $email_template_slug, $language, $data, $type='')
	{
		$parse_content = $this->parse_content_merge_fields($email_template_slug, $language, $data);

		$inbox['body'] = _strip_tags($parse_content['content']);
		$inbox['body'] = nl2br_save_html($inbox['body']);
		
		$this->load->model('emails_model');
		$result = $this->cs_send_simple_email($email, $parse_content['subject'], $inbox['body'], $parse_content['fromname']);

		if ($result) {

			if($type == 'department'){
				//write log
				$description = '';
				$description .= '<strong>'._l('cs_send_an_email_to_assigned_ticket_deparment').'</strong><br>';
				$description .= '<strong>'._l('cs_subject').'</strong>: '.$parse_content['subject'].'<br>';
				$description .= '<strong>'._l('cs_to').'</strong>: '.$email.'<br>';
				$description .= $inbox['body'];

				$this->cs_ticket_log($data['ticket']->id, 'cs_ticket', $description, '', null, null, 0, 'System');

			}elseif($type == 'requester'){
				$description = '';
				$description .= '<strong>'._l('cs_send_an_email_to_requester').'</strong><br>';
				$description .= '<strong>'._l('cs_subject').'</strong>: '.$parse_content['subject'].'<br>';
				$description .= '<strong>'._l('cs_to').'</strong>: '.$email.'<br>';
				$description .= $inbox['body'];

				$this->cs_ticket_log($data['ticket']->id, 'cs_ticket', $description, '', null, null, 0, 'System');

			}elseif($type == 'staff'){
				$description = '';
				$description .= '<strong>'._l('cs_send_an_email_to_staff').'</strong><br>';
				$description .= '<strong>'._l('cs_subject').'</strong>: '.$parse_content['subject'].'<br>';
				$description .= '<strong>'._l('cs_to').'</strong>: '.$email.'<br>';
				$description .= $inbox['body'];

				$this->cs_ticket_log($data['ticket']->id, 'cs_ticket', $description, '', null, null, 0, 'System');
			}

			return true;
		}
		return false;
	}

	/**
	 * parse content merge fields
	 * @param  [type] $slug     
	 * @param  [type] $language 
	 * @param  [type] $data     
	 * @return [type]           
	 */
	public function parse_content_merge_fields($slug, $language, $data)
	{
		if (!class_exists('other_merge_fields', false)) {
			$this->load->library('merge_fields/other_merge_fields');
		}
		$this->load->library('merge_fields/customer_service_email_template_merge_fields');

		/*get emailtemplate*/
		$this->load->model('emails_model');
		$template_result = $this->emails_model->get(['slug' => $slug, 'language' => $language], 'row');

		$merge_field_input['ticket_id']=$data['ticket_id'];
		$merge_field_input['stage_id']=$data['stage_id'];

		$merge_fields = [];
		$merge_fields = array_merge($merge_fields, $this->customer_service_email_template_merge_fields->format(array_to_object($merge_field_input)));
		$merge_fields = array_merge($merge_fields, $this->other_merge_fields->format());

		$subject = '';
		$fromname = '';
		$content = '';

		if($template_result){
			$subject = $template_result->subject;
			$fromname = $template_result->fromname;
			$content = $template_result->message;

			foreach ($merge_fields as $key => $val) {

				if (stripos($subject, $key) !== false) {
					$subject = str_ireplace($key, $val, $subject);
				} else {
					$subject = str_ireplace($key, '', $subject);
				}

				if (stripos($fromname, $key) !== false) {
					$fromname = str_ireplace($key, $val, $fromname);
				} else {
					$fromname = str_ireplace($key, '', $fromname);
				}

				if (stripos($content, $key) !== false) {
					$content = str_ireplace($key, $val, $content);
				} else {
					$content = str_ireplace($key, '', $content);
				}
				
			}
		}

		$parse_data = [];
		$parse_data['subject'] = $subject;
		$parse_data['fromname'] = $fromname;
		$parse_data['content'] = $content;

		return $parse_data;
	}
	
	/**
	 * cs send simple email
	 * @param  [type] $email    
	 * @param  [type] $subject  
	 * @param  [type] $message  
	 * @param  string $fromname 
	 * @return [type]           
	 */
	public function cs_send_simple_email($email, $subject, $message, $fromname = '')
	{
		$cnf = [
			'from_email' => get_option('smtp_email'),
			'from_name'  => $fromname != '' ? $fromname : get_option('companyname'),
			'email'      => $email,
			'subject'    => $subject,
			'message'    => $message,
		];

        // Simulate fake template to be parsed
		$template           = new StdClass();
		$template->message  = get_option('email_header') . $cnf['message'] . get_option('email_footer');
		$template->fromname = $cnf['from_name'];
		$template->subject  = $cnf['subject'];

		$template = parse_email_template($template);

		$cnf['message']   = $template->message;
		$cnf['from_name'] = $template->fromname;
		$cnf['subject']   = $template->subject;

		$cnf['message'] = check_for_links($cnf['message']);

		$cnf = hooks()->apply_filters('before_send_simple_email', $cnf);

		if (isset($cnf['prevent_sending']) && $cnf['prevent_sending'] == true) {
			$this->clear_attachments();

			return false;
		}
		$this->load->config('email');
		$this->email->clear(true);
		$this->email->set_newline(config_item('newline'));
		$this->email->from($cnf['from_email'], $cnf['from_name']);
		$this->email->to($cnf['email']);

		$bcc = '';
        // Used for action hooks
		if (isset($cnf['bcc'])) {
			$bcc = $cnf['bcc'];
			if (is_array($bcc)) {
				$bcc = implode(', ', $bcc);
			}
		}

		$systemBCC = get_option('bcc_emails');
		if ($systemBCC != '') {
			if ($bcc != '') {
				$bcc .= ', ' . $systemBCC;
			} else {
				$bcc .= $systemBCC;
			}
		}
		if ($bcc != '') {
			$this->email->bcc($bcc);
		}

		if (isset($cnf['cc'])) {
			$this->email->cc($cnf['cc']);
		}

		if (isset($cnf['reply_to'])) {
			$this->email->reply_to($cnf['reply_to']);
		}

		$this->email->subject($cnf['subject']);
		$this->email->message($cnf['message']);

		$this->email->set_alt_message(strip_html_tags($cnf['message'], '<br/>, <br>, <br />'));

		if (isset($this->attachment) && count($this->attachment) > 0) {
			foreach ($this->attachment as $attach) {
				if (!isset($attach['read'])) {
					$this->email->attach($attach['attachment'], 'attachment', $attach['filename'], $attach['type']);
				} else {
					if (!isset($attach['filename']) || (isset($attach['filename']) && empty($attach['filename']))) {
						$attach['filename'] = basename($attach['attachment']);
					}
					$this->email->attach($attach['attachment'], '', $attach['filename']);
				}
			}
		}

		$this->clear_attachments();
		if ($this->email->send()) {
			log_activity('Email sent to: ' . $cnf['email'] . ' Subject: ' . $cnf['subject']);

			return true;
		}

		return false;
	}

	/**
	 * clear attachments
	 * @return [type] 
	 */
	private function clear_attachments()
	{
		$this->attachment = [];
	}

	/**
	 * get pre stage id
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function get_pre_stage_id($data, $input_node)
	{	
		if(count($input_node) > 0){
			foreach ($input_node as $connection) {
				$node = $data['workflow'][$connection['node']];
				if($node['class'] == 'stage'){
					return $node['id'];
				}

				if(isset($data['workflow'][$connection['node']]['inputs']['input_1']['connections'])){
					$input_node = $data['workflow'][$connection['node']]['inputs']['input_1']['connections'];
					return $this->get_pre_stage_id($data, $input_node);
				}
			}

		}else{
			return 0;
		}
	}

	/**
	 * cs_ticket_log
	 */
	public function cs_ticket_log($id, $rel_type, $description, $date = '', $from_date = null, $to_date = null, $duration = 0, $created_type = '')
	{
		if(new_strlen($date) == 0){
			$date = date('Y-m-d H:i:s');
		}

		if($created_type == 'System'){
			$staffid = 0;
			$full_name = 'System';
			$created_type = 'Auto created by system';
		}else{

			if (is_staff_logged_in()) {
				$staffid = get_staff_user_id();
				$full_name = get_staff_full_name(get_staff_user_id());
				$created_type = 'staff';
			}elseif(is_client_logged_in()){
				$staffid = get_client_user_id();
				$full_name = get_company_name($staffid);
				$created_type = 'client';
			}else{
				$staffid = 0;
				$full_name = 'System';
				$created_type = 'Auto created by system';
			}
		}

		$log = [
			'date'            => $date,
			'description'     => $description,
			'rel_id'          => $id,
			'rel_type'          => $rel_type,
			'staffid'         => $staffid,
			'full_name'       => $full_name,
			'from_date'	=> $from_date,
			'to_date'	=> $to_date,
			'duration'	=> $duration,
			'created_type'	=> $created_type,
		];

		$this->db->insert(db_prefix() . 'cs_ticket_timeline_logs', $log);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			return $insert_id;
		}
		return false;
	}

	/**
	 * count ticket by status
	 * @param  string $client_id 
	 * @return [type]            
	 */
	public function count_ticket_by_status($client_id = '')
	{
		$status = [];
		if(is_numeric($client_id)){
			$sql_where = "SELECT count(id) as total, status, client_id FROM ".db_prefix()."cs_tickets
			WHERE client_id = '".$client_id."'
			GROUP BY client_id, ".db_prefix()."cs_tickets.status;";
		}else{
			$sql_where = "SELECT count(id) as total, status FROM ".db_prefix()."cs_tickets
			GROUP BY ".db_prefix()."cs_tickets.status;";
		}

		$service_detail = $this->db->query($sql_where)->result_array();
		$status['all'] = 0;
		foreach ($service_detail as $value) {
		    $status[$value['status']] = $value['total'];
		    $status['all'] += (float)$value['total'];
		}
		return $status;
	}

	/**
	 * client post reply
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function client_post_reply($data)
	{

		$data_reply = [];

		$data_reply['ticket_id'] = $data['ticket_id'];
		$data_reply['note_title'] = $data['note_title'];
		$data_reply['note_details'] = $data['note_details'];
		$data_reply['datecreated'] = date('Y-m-d H:i:s');
		if(isset($data['staffid'])){
			$data_reply['staffid'] = $data['staffid'];
		}else{
			$data_reply['staffid'] = get_contact_user_id();
		}
		$data_reply['created_type'] = 'client';

		$this->db->insert(db_prefix().'cs_ticket_action_post_internal_notes', $data_reply);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {

			/*TODO send mail to client*/
			return $insert_id;
		}
		return false;
	}

	/**
	 * get ticket post internal history
	 * @param  [type]  $ticket_id 
	 * @param  boolean $client    
	 * @return [type]             
	 */
	public function get_ticket_post_internal_history($ticket_id, $client = false)
	{
		$histories = [];

		$this->db->where('ticket_id', $ticket_id);
		$action_post_replies = $this->db->get(db_prefix() . 'cs_ticket_action_post_internal_notes')->result_array();
		foreach ($action_post_replies as $value) {
			$value['strdate'] = strtotime($value['datecreated']);
			$histories[strtotime($value['datecreated'])] = $value;
		}

		usort($histories, function ($item1, $item2) {
			return $item2['strdate'] <=> $item1['strdate'];
		});

		return $histories;
	}

	/**
	 * cal due date for ticket
	 * @param  [type] $sla_id 
	 * @return [type]         
	 */
	public function cal_due_date_for_ticket($sla_id)
	{
		$due_data = date('Y-m-d H:i:s');
		$sla = $this->get_sla($sla_id);
		if($sla){
			if($sla->hours_of_operation == 'business_hours'){
				$cs_business_from_hours = get_option('customer_service_business_from_hours');
				$cs_business_to_hours = get_option('customer_service_business_to_hours');
				$cs_business_days = get_option('customer_service_business_days');

				if(new_strlen($cs_business_days) > 0){
					$hours = (strtotime(date('Y-m-d').' '.$cs_business_to_hours.':00') - strtotime(date('Y-m-d').' '.$cs_business_from_hours.':00'))/60/60;
					if($hours > 0){
						$due_data = $this->get_due_date((int)$sla->grace_period, $hours);

					}else{
						$due_data = date('Y-m-d H:i:s', strtotime($due_data."+ ".(int)$sla->grace_period." hours"));
					}

				}else{
	    			//full support
					$due_data = date('Y-m-d H:i:s', strtotime($due_data."+ ".(int)$sla->grace_period." hours"));
				}
			}else{
	    		//full support
				$due_data = date('Y-m-d H:i:s', strtotime($due_data."+ ".(int)$sla->grace_period." hours"));
			}
		}
		return $due_data;
	}

	/**
	 * get due date
	 * @param  [type] $grace_period   
	 * @param  [type] $business_hours 
	 * @return [type]                 
	 */
	public function get_due_date($grace_period, $business_hours) {
		$cs_business_from_hours = get_option('customer_service_business_from_hours');
		$cs_business_to_hours = get_option('customer_service_business_to_hours');
		$cs_business_days = get_option('customer_service_business_days');
		$arr_business_days = explode(",", $cs_business_days);

		$due_date = date('Y-m-d H:i:s');
		$current_time = date('Y-m-d H:i:s');

		$grace_period_minutes = (int)$grace_period*60;

		do {

			$jd=cal_to_jd(CAL_GREGORIAN,date('m', strtotime($current_time)), date('d', strtotime($current_time)), date('Y', strtotime($current_time)));
			$day=jddayofweek($jd,0);

			switch($day){
				case 0:
				// sunday
				$business_setting = 6;
				break;

				case 1:
				// monday
				$business_setting = 0;

				break;
				case 2:
				// tuesday
				$business_setting = 1;

				break;
				case 3:
				// wednesday
				$business_setting = 2;

				break;
				case 4:
				// thursday
				$business_setting = 3;

				break;
				case 5:
				// friday
				$business_setting = 4;

				break;
				case 6:
				// saturday
				$business_setting = 5;

				break;
			}

			if(in_array($business_setting, $arr_business_days)){


				$start_business_hours = strtotime(date("Y-m-d", strtotime($current_time)).' '.$cs_business_from_hours.':00');
				$end_business_hours = strtotime(date("Y-m-d", strtotime($current_time)).' '.$cs_business_to_hours.':00');


				if(strtotime($due_date) < $start_business_hours){
					$new_business_minutes = (int)$business_hours*60;

					if($grace_period_minutes < $new_business_minutes){
						$due_date = date("Y-m-d H:i:s",strtotime(date("Y-m-d", strtotime($current_time)).' '.$cs_business_from_hours.':00'."+ ".(int)$grace_period_minutes." minutes"));

						$grace_period_minutes = 0;
					}else{
						$due_date = date("Y-m-d H:i:s",strtotime(date("Y-m-d", strtotime($current_time)).' '.$cs_business_from_hours.':00'."+ ".$new_business_minutes." minutes"));

						$grace_period_minutes = (float)$grace_period_minutes - (float)$new_business_minutes;
					}


				}elseif((strtotime($current_time) >= $start_business_hours) && (strtotime($current_time) <= $end_business_hours) ){

					$new_business_hours = $end_business_hours - strtotime($current_time);
					$new_business_minutes = (int)($new_business_hours/60);

					if($grace_period_minutes < $new_business_minutes){
						$due_date = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s", strtotime($current_time))."+ ".(int)$grace_period_minutes." minutes"));


						$grace_period_minutes = 0;
					}else{
						$due_date = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s", strtotime($current_time))."+ ".(int)$new_business_minutes." minutes"));
						$grace_period_minutes = (float)$grace_period_minutes - (float)$new_business_minutes;
					}
				}
			}
			$current_time = date("Y-m-d H:i:s", strtotime(date("Y-m-d",strtotime($current_time)).' '.$cs_business_from_hours.':00'."+ 1 days"));

		} while ($grace_period_minutes > 0);

		return $due_date;
	}

	/**
	 * get assignee in department
	 * @param  [type] $department_id 
	 * @return [type]                
	 */
	public function  get_assignee_in_department($department_id)
	{
		$assigneed_id = 0;

		$staff_ids = [];
		//get staff in department, order by ticket ASC (status closed)
		$this->db->where('departmentid', $department_id);
		$this->db->order_by('staffid ASC');
		$staff_departments = $this->db->get(db_prefix().'staff_departments')->result_array();

		foreach ($staff_departments as $value) {
			$staff_ids[] = $value['staffid'];
		}

		if(count($staff_ids) > 0){
			$this->db->select('assigned_id, count(id) as total_ticket');
			$this->db->where('assigned_id IN ('.implode(',', $staff_ids).')');
			$this->db->where('status != "closed"');
			$this->db->group_by('assigned_id');
			$this->db->order_by('total_ticket ASC');
			$ticket_by_staff = $this->db->get(db_prefix().'cs_tickets')->result_array();
			if(count($ticket_by_staff) > 0){
				if(count($staff_ids) > count($ticket_by_staff) ){
					foreach ($ticket_by_staff as $value) {
					    if(in_array($value['assigned_id'], $staff_ids)){
					    	unset($staff_ids[$value['assigned_id']]);
					    }
					}
				}else{
					$assigneed_id = $ticket_by_staff[0]['assigned_id'];
				}
			}else{
				$assigneed_id = $staff_ids[0];
			}
		}else{
			$this->db->where('admin', 1);
			$staff = $this->db->get(db_prefix().'staff')->row();
			if($staff){
				$assigneed_id = $staff->staffid;
			}
		}
		return $assigneed_id;
	}

	/**
	 * get category tag filter
	 * @return [type] 
	 */
	public function get_category_tag_filter()
	{
		return $this->db->query('select DISTINCT id, name  FROM '.db_prefix().'taggables left join '.db_prefix().'tags on '.db_prefix().'taggables.tag_id =' .db_prefix().'tags.id where '.db_prefix().'taggables.rel_type = "cs_category_tag"')->result_array();
	}

	/**
	 * get list category tags
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function get_list_category_tags($id)
	{
		$data=[];

		/* get list tinymce start*/
		$this->db->from(db_prefix() . 'taggables');
		$this->db->join(db_prefix() . 'tags', db_prefix() . 'tags.id = ' . db_prefix() . 'taggables.tag_id', 'left');

		$this->db->where(db_prefix() . 'taggables.rel_id', $id);
		$this->db->where(db_prefix() . 'taggables.rel_type', 'cs_category_tag');
		$this->db->order_by('tag_order', 'ASC');

		$cs_category_tags = $this->db->get()->result_array();

		$html_tags='';
		foreach ($cs_category_tags as $tag_value) {
			$html_tags .='<li class="tagit-choice ui-widget-content ui-state-default ui-corner-all tagit-choice-editable tag-id-'.$tag_value['id'].' true" value="'.$tag_value['id'].'">
			<span class="tagit-label">'.$tag_value['name'].'</span>
			<a class="tagit-close">
			<span class="text-icon">×</span>
			<span class="ui-icon ui-icon-close"></span>
			</a>
			</li>';
		}

		$data['htmltag']    = $html_tags;  

		return $data;
	}

	/**
	 * get tags name
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function get_tags_name($id)
	{
		/* get list tinymce start*/
		$this->db->from(db_prefix() . 'taggables');
		$this->db->join(db_prefix() . 'tags', db_prefix() . 'tags.id = ' . db_prefix() . 'taggables.tag_id', 'left');

		$this->db->where(db_prefix() . 'taggables.rel_id', $id);
		$this->db->where(db_prefix() . 'taggables.rel_type', 'cs_category_tag');
		$this->db->order_by('tag_order', 'ASC');

		$cs_category_tags = $this->db->get()->result_array();

		$array_tags_name = [];
		foreach ($cs_category_tags as $tag_value) {
			array_push($array_tags_name, $tag_value['name']);
		}

		return implode(",", $array_tags_name);
	}

	/**
	 * change category default
	 * @param  [type] $id     
	 * @param  [type] $status 
	 * @return [type]         
	 */
	public function change_category_default($id, $status)
	{
		$category = $this->get_category($id);

		if($category){
			$this->db->where('id', $id);
			$this->db->update(db_prefix().'cs_ticket_categories', ['category_default' => 1]);

			if ($this->db->affected_rows() > 0) {
				$this->db->where('department_id', $category->department_id);
				$this->db->where('id !=', $id);
				$this->db->update(db_prefix().'cs_ticket_categories', ['category_default' => 0]);
				if ($this->db->affected_rows() > 0) {
					return true;
				}
				return true;
			}
		}
		return false;
	}

	/**
	 * categorize auto generated from email
	 * @param  [type] $department_id 
	 * @param  [type] $subject       
	 * @param  [type] $message       
	 * @return [type]                
	 */
	public function categorize_auto_generated_from_email($department_id, $subject, $message)
	{
		$category = 0;
		$priority = 0;
		$workflow_id = 0;
		$sla_id = 0;

		/*get category for Ticket: mapping by tag -> category default -> department*/

		$this->db->where('department_id', $department_id);
		$ticket_categories = $this->db->get(db_prefix() . 'cs_ticket_categories')->result_array();

		if(count($ticket_categories) > 0){
			$category_ids = [];
			foreach ($ticket_categories as $value) {
			    $category_ids[] = $value['id'];
			}
			$list_tags = $this->get_list_tags($category_ids);
			foreach ($list_tags as $key => $tag_value) {
				if (!(strpos(strtoupper($subject), strtoupper($tag_value['name'])) === false) || !(strpos(strtoupper($message), strtoupper($tag_value['name'])) === false) ) {
					$category_id = $tag_value['rel_id'];

					$get_category = $this->get_category($category_id);
					if($get_category){
						$data = [];
						$data['category'] = $category_id;
						$data['priority'] = $get_category->priority;
						$data['workflow_id'] = $get_category->work_flow_id;
						$data['sla_id'] = $get_category->sla_id;

						return $data;
					}
				}
			}

			/*get default category*/
			$this->db->where('department_id', $department_id);
			$this->db->where('category_default', 1);
			$category_default = $this->db->get(db_prefix() . 'cs_ticket_categories')->row();

			if($category_default){
				$data = [];
				$data['category'] = $category_default->id;
				$data['priority'] = $category_default->priority;
				$data['workflow_id'] = $category_default->work_flow_id;
				$data['sla_id'] = $category_default->sla_id;
				return $data;
			}


			$this->db->where('department_id', $department_id);
			$category_department = $this->db->get(db_prefix() . 'cs_ticket_categories')->row();

			if($category_department){
				$data = [];
				$data['category'] = $category_department->id;
				$data['priority'] = $category_department->priority;
				$data['workflow_id'] = $category_department->work_flow_id;
				$data['sla_id'] = $category_department->sla_id;
				return $data;
			}

		}

		$data = [];
		$data['category'] = $category;
		$data['priority'] = $priority;
		$data['workflow_id'] = $workflow_id;
		$data['sla_id'] = $sla_id;
		$data['category_not_found'] = 0;

		return $data;
	}

	/**
	 * get list tags
	 * @param  [type] $category_ids 
	 * @return [type]               
	 */
	public function get_list_tags($category_ids)
	{
		$data=[];

		/* get list tinymce start*/
		$this->db->from(db_prefix() . 'taggables');
		$this->db->join(db_prefix() . 'tags', db_prefix() . 'tags.id = ' . db_prefix() . 'taggables.tag_id', 'left');
		$this->db->where(db_prefix() . 'taggables.rel_id IN ('.implode(',', $category_ids).')');
		$this->db->where(db_prefix() . 'taggables.rel_type', 'cs_category_tag');
		$this->db->order_by('tag_order', 'ASC');

		$cs_category_tags = $this->db->get()->result_array();

		return $cs_category_tags;
	}

	/**
	 * ticket report by status
	 * @return [type] 
	 */
	public function get_ticket_on_hold_closed_data($from_date, $to_date)
	{	
		$chart=[];
		
		$sql_where="SELECT  date_format(datecreated, '%m') as mo_month, count(id) as total, status FROM ".db_prefix()."cs_tickets
			where date_format(datecreated, '%Y-%m-%d') >= '".$from_date."' AND date_format(datecreated, '%Y-%m-%d') <= '".$to_date."'
			group by date_format(datecreated, '%m'), status
			";

		$ticket = $this->db->query($sql_where)->result_array();

		$ticket_by_month=[];
		foreach ($ticket as $key => $ticket_value) {
		    $ticket_by_month[(int)$ticket_value['mo_month']][$ticket_value['status']] = $ticket_value;
		}


		for($_month = 1 ; $_month <= 12; $_month++){

			if(isset($ticket_by_month[$_month])){

				$chart['open'][] = isset($ticket_by_month[$_month]['open']) ? (float)$ticket_by_month[$_month]['open']['total'] : 0;
				$chart['inprogress'][] = isset($ticket_by_month[$_month]['inprogress']) ? (float)$ticket_by_month[$_month]['inprogress']['total'] : 0;
				$chart['answered'][] = isset($ticket_by_month[$_month]['answered']) ? (float)$ticket_by_month[$_month]['answered']['total'] : 0;
				$chart['on_hold'][] = isset($ticket_by_month[$_month]['on_hold']) ? (float)$ticket_by_month[$_month]['on_hold']['total'] : 0;
				$chart['closed'][] = isset($ticket_by_month[$_month]['closed']) ? (float)$ticket_by_month[$_month]['closed']['total'] : 0;

			}else{
				$chart['open'][] =  0;
				$chart['inprogress'][] =  0;
				$chart['answered'][] =  0;
				$chart['on_hold'][] =  0;
				$chart['closed'][] =  0;
			}

			if($_month == 5){
				$chart['categories'][] = _l('month_05');
			}else{
				$chart['categories'][] = _l('month_'.$_month);
			}

		}

		return $chart;
	}

	/**
	 * ticket total hours
	 * @param  string $from_date 
	 * @param  string $to_date   
	 * @return [type]            
	 */
	public function ticket_total_hours($from_date = '', $to_date = '')
	{
		$total_hours = 0;
		$avg_resolution_time = 0;

		if(new_strlen($from_date) > 0){

		$sql_where="SELECT  SUM(time_spent) as total_hours, AVG(time_spent) as avg_resolution_time FROM ".db_prefix()."cs_tickets
			where date_format(datecreated, '%Y-%m-%d') >= '".$from_date."' AND date_format(datecreated, '%Y-%m-%d') <= '".$to_date."' AND status = 'closed'
			";
		}else{
			$sql_where="SELECT  SUM(time_spent) as total_hours, AVG(time_spent) as avg_resolution_time FROM ".db_prefix()."cs_tickets
			where status = 'closed'
			";
		}

		$ticket_total_hours = $this->db->query($sql_where)->row();
		if($ticket_total_hours){
			$total_hours = $ticket_total_hours->total_hours;
			$avg_resolution_time = $ticket_total_hours->avg_resolution_time;
		}

		$data = [];
		$data['total_hours'] = $total_hours;
		$data['avg_resolution_time'] = $avg_resolution_time;
		return $data;
	}

	public function count_ticket_by_status_with_time($from_date = '', $to_date = '')
	{
		$status = [];
		if(new_strlen($from_date) > 0){
			$sql_where = "SELECT count(id) as total, status FROM ".db_prefix()."cs_tickets
			where date_format(datecreated, '%Y-%m-%d') >= '".$from_date."' AND date_format(datecreated, '%Y-%m-%d') <= '".$to_date."'
			GROUP BY ".db_prefix()."cs_tickets.status;";
		}else{
			$sql_where = "SELECT count(id) as total, status FROM ".db_prefix()."cs_tickets
			GROUP BY ".db_prefix()."cs_tickets.status;";
		}

		$service_detail = $this->db->query($sql_where)->result_array();
		$status['all'] = 0;
		foreach ($service_detail as $value) {
			$status[$value['status']] = $value['total'];
			$status['all'] += (float)$value['total'];
		}
		return $status;
	}

	/**
	 * ticket by category
	 * @return [type] 
	 */
	public function ticket_by_category($from_date = '', $to_date = '')
	{
		$chart = [];
		$color_data = ['#a48a9e', '#c6e1e8', '#648177' ,'#0d5ac1','#00FF7F', '#0cffe95c','#80da22','#f37b15','#da1818','#176cea','#5be4f0', '#57c4d8', '#a4d17a', '#225b8', '#be608b', '#96b00c', '#088baf',
		'#63b598', '#ce7d78', '#ea9e70' ,
		'#d2737d' ,'#c0a43c' ,'#f2510e' ,'#651be6' ,'#79806e' ,'#61da5e' ,'#cd2f00' ];

		$this->db->select('count('.db_prefix().'cs_tickets.id) as total_ticket,'.db_prefix().'cs_ticket_categories.category_name');
		$this->db->join(db_prefix() . 'cs_ticket_categories', db_prefix() . 'cs_tickets.category_id = ' . db_prefix() . 'cs_ticket_categories.id', 'left');
		if(new_strlen($from_date) > 0){
			$sql_where = "date_format(".db_prefix()."cs_tickets.datecreated, '%Y-%m-%d') >= '".$from_date."' AND date_format(".db_prefix()."cs_tickets.datecreated, '%Y-%m-%d') <= '".$to_date."'";
			$this->db->where($sql_where);
		}
		$this->db->group_by('category_id');
		$ticket_by_category = $this->db->get(db_prefix().'cs_tickets')->result_array();

		$color_index=0;
		$categories = [];
		foreach ($ticket_by_category as $key => $value) {
			$categories[] = $value['category_name'];

			$chart[] = (int)$value['total_ticket'];
			$color_index++;
		}

		return ['chart' => $chart, 'categories' => $categories];
	}

	/**
	 * cal CSAT
	 * @param  string $from_date 
	 * @param  string $to_date   
	 * @return [type]            
	 */
	public function cal_CSAT($from_date = '', $to_date = '')
	{
		$csat = 0;
		$total_stars_given = 0;
		$total_possible_stars = 0;

		if(new_strlen($from_date) > 0){
			$sql_where = "SELECT count(id) as total, client_rating FROM ".db_prefix()."cs_tickets
			where date_format(datecreated, '%Y-%m-%d') >= '".$from_date."' AND date_format(datecreated, '%Y-%m-%d') <= '".$to_date." AND client_rating != 0'
			GROUP BY ".db_prefix()."cs_tickets.client_rating;";
		}else{
			$sql_where = "SELECT count(id) as total, client_rating FROM ".db_prefix()."cs_tickets WHERE client_rating != 0
			GROUP BY ".db_prefix()."cs_tickets.client_rating;";
		}
		$client_rating = $this->db->query($sql_where)->result_array();

		foreach ($client_rating as $value) {
		    $total_stars_given += (int)$value['client_rating']*(int)$value['total'];
		    $total_possible_stars += 5*(int)$value['total'];
		}

		if($total_possible_stars > 0){
			$csat = ($total_stars_given / $total_possible_stars) * 100;
		}
		return $csat;
	}

	/**
	 * similar
	 * @param  [type]  $comparer     
	 * @param  [type]  $comparee     
	 * @param  boolean $asPercentage 
	 * @return [type]                
	 */
	public function similar($comparer, $comparee, $asPercentage = false)
	{
		if ($asPercentage) {
			$percentage = 0;
			similar_text($comparer, $comparee, $percentage);

			return $percentage;
		}

		return similar_text($comparer, $comparee);
	}

	/**
	 * find similar content tickets
	 * @param  string $ticket_id 
	 * @return [type]            
	 */
	public function find_similar_content_tickets($ticket_id='')
	{
		/*find similar realted: issue_summary, internal_note, resolution*/
		$ticket_related = [];
		$precision_default = 30;
		$ticket_ids = [];

		$main_ticket = $this->get_ticket($ticket_id);

		$this->db->where('id !=', $ticket_id);
		$this->db->where('status', 'closed');
		$cs_tickets = $this->db->get(db_prefix().'cs_tickets')->result_array();
		foreach ($cs_tickets as $value) {
			$comparer = $main_ticket->issue_summary.' '.$main_ticket->internal_note;
			$comparee = $value['issue_summary'].' '.$value['internal_note'].' '.$value['resolution'];

			$precision = $this->similar($comparer, $comparee, true);
			if((float)$precision >= $precision_default){
				$ticket_ids[] = $value['id'];

				$ticket_related[$value['id']] = [
					'ticket_id' => $value['id'],
					'precision' => (float)$precision,
				];
			}
		}

		usort($ticket_related, function ($item1, $item2) {
			return $item2['precision'] <=> $item1['precision'];
		});

		if(count($ticket_ids) > 0){
			$this->db->where('id IN ('.implode(',', $ticket_ids).')');
			$tickets = $this->db->get(db_prefix().'cs_tickets')->result_array();
		}

		foreach ($ticket_related as $key => $value) {
		    if(isset($tickets[$key])){
		    	$ticket_related[$key]['ticket'] = $tickets[$key];
		    }
		}
		return $ticket_related;
	}

	/**
	 * get payments
	 * @param  array  $where 
	 * @return [type]        
	 */
	public function get_payments($where = [])
	{
		$this->db->select('*,' . db_prefix() . 'invoicepaymentrecords.id as paymentid');
		$this->db->join(db_prefix() . 'payment_modes', db_prefix() . 'payment_modes.id = ' . db_prefix() . 'invoicepaymentrecords.paymentmode', 'left');
		$this->db->order_by(db_prefix() . 'invoicepaymentrecords.daterecorded', 'desc');
		$this->db->where($where);
		$payments = $this->db->get(db_prefix() . 'invoicepaymentrecords')->result_array();
		return $payments;
	}

	/**
	 * get ticket by kpi rule
	 * @param  [type] $kpi_id 
	 * @return [type]         
	 */
	public function get_ticket_by_kpi_rule($kpi_id)
	{
		$total_ticket = 0;

		$kpi_first_response_time = 0;
		$kpi_average_resolution_time = 0;
		$kpi_average_handle_time = 0;
		$kpi_number_of_tickets = 0;
		$kpi_number_of_resolved_tickets = 0;
		$kpi_number_of_tickets_by_medium = 0;
		$kpi_escalation_rate = 0;
		$kpi_customer_satisfaction_score = 0;

		$ticket_kpi_first_response_time = 0;
		$ticket_kpi_average_resolution_time = 0;
		$ticket_kpi_average_handle_time = 0;
		$ticket_kpi_number_of_tickets = 0;
		$ticket_kpi_number_of_resolved_tickets = 0;
		$ticket_kpi_number_of_tickets_by_medium = 0;
		$ticket_kpi_escalation_rate = 0;
		$ticket_kpi_customer_satisfaction_score = 0;

		$ticket_kpi_max_customer_satisfaction_score = 0;

		

		$arr_first_response_time = [];
		$arr_average_resolution_time = [];
		$arr_average_handle_time = [];
		$arr_number_of_tickets = [];
		$arr_number_of_resolved_tickets = [];
		$arr_number_of_tickets_by_medium = [];
		$arr_escalation_rate = [];
		$arr_customer_satisfaction_score = [];
		$arr_total_ticket = [];

		$kpi = $this->get_kpi($kpi_id);
		if($kpi){
			switch ($kpi->first_response_time_measure) {
				case 'seconds':
				$kpi_first_response_time = (float)$kpi->first_response_time/60/60;
				break;
				case 'minutes':
				$kpi_first_response_time = (float)$kpi->first_response_time/60;
				break;
				case 'hours':
				$kpi_first_response_time = (float)$kpi->first_response_time;
				break;
				case 'days':
				$kpi_first_response_time = (float)$kpi->first_response_time*24;
				break;

				default:
    				// code...
				break;
			}

			switch ($kpi->average_resolution_time_measure) {
				case 'seconds':
				$kpi_average_resolution_time = (float)$kpi->average_resolution_time/60/60;
				break;
				case 'minutes':
				$kpi_average_resolution_time = (float)$kpi->average_resolution_time/60;
				break;
				case 'hours':
				$kpi_average_resolution_time = (float)$kpi->average_resolution_time;
				break;
				case 'days':
				$kpi_average_resolution_time = (float)$kpi->average_resolution_time*24;
				break;

				default:
    				// code...
				break;
			}

			switch ($kpi->average_handle_time_measure) {
				case 'seconds':
				$kpi_average_handle_time = (float)$kpi->average_handle_time/60/60;
				break;
				case 'minutes':
				$kpi_average_handle_time = (float)$kpi->average_handle_time/60;
				break;
				case 'hours':
				$kpi_average_handle_time = (float)$kpi->average_handle_time;
				break;
				case 'days':
				$kpi_average_handle_time = (float)$kpi->average_handle_time*24;
				break;

				default:
    				// code...
				break;
			}

			$kpi_number_of_tickets = $kpi->number_of_tickets;
			$kpi_number_of_resolved_tickets = $kpi->number_of_resolved_tickets;
			$kpi_number_of_tickets_by_medium = $kpi->number_of_tickets_by_medium;
			$kpi_escalation_rate = $kpi->escalation_rate;
			$kpi_customer_satisfaction_score = $kpi->customer_satisfaction_score;
		}

		$this->db->where('kpi_id', $kpi_id);
		$ticket_by_kpi= $this->db->get(db_prefix() . 'cs_tickets')->result_array();
		$total_ticket = count($ticket_by_kpi);

		foreach ($ticket_by_kpi as $key => $value) {
			if($value['first_reply_time']){
				$first_reply_time = (strtotime($value['first_reply_time']) - strtotime($value['datecreated']))/60/60;
				if((float)$first_reply_time > $kpi_first_response_time){
					$value['first_reply_time_hours'] = $first_reply_time;
					$arr_first_response_time[] = $value;
				}
			}

			if($value['status'] == 'closed'){
				$ticket_kpi_average_resolution_time += (float)$value['time_spent'];
				$ticket_kpi_average_handle_time += (strtotime($value['last_update_time']) - strtotime($value['datecreated']))/60/60;

				$ticket_kpi_number_of_resolved_tickets++;
				if($value['client_rating'] != 0){
					$ticket_kpi_customer_satisfaction_score += $value['client_rating'];
					$ticket_kpi_max_customer_satisfaction_score += 5;
					$arr_number_of_resolved_tickets[] = $value;
				}
			}

			$arr_total_ticket[] = $value;

		}

		if($ticket_kpi_number_of_resolved_tickets > 0){
			$ticket_kpi_average_resolution_time = $ticket_kpi_average_resolution_time/$ticket_kpi_number_of_resolved_tickets;
			$ticket_kpi_average_handle_time = $ticket_kpi_average_handle_time/$ticket_kpi_number_of_resolved_tickets;
		}

		if($ticket_kpi_customer_satisfaction_score != 0){
			$ticket_kpi_customer_satisfaction_score = round($ticket_kpi_customer_satisfaction_score/$ticket_kpi_max_customer_satisfaction_score * 100, 2);
		}

		$result_data = [];
		$result_data['kpi_first_response_time'] = count($arr_first_response_time);
		$result_data['arr_first_response_time'] = $arr_first_response_time;
		$result_data['kpi_average_resolution_time'] = $ticket_kpi_average_resolution_time;
		$result_data['kpi_average_handle_time'] = $ticket_kpi_average_handle_time;
		$result_data['kpi_number_of_tickets'] = $total_ticket;
		$result_data['kpi_number_of_resolved_tickets'] = count($arr_number_of_resolved_tickets);
		$result_data['arr_number_of_resolved_tickets'] = $arr_number_of_resolved_tickets;
		$result_data['kpi_number_of_tickets_by_medium'] = $kpi_number_of_tickets_by_medium ;
		$result_data['kpi_escalation_rate'] = $kpi_escalation_rate;
		$result_data['kpi_customer_satisfaction_score'] = $ticket_kpi_customer_satisfaction_score;
		$result_data['arr_total_ticket'] = $arr_total_ticket;
		$result_data['main_kpi_average_resolution_time'] = $kpi_average_resolution_time;
		$result_data['main_kpi_average_handle_time'] = $kpi_average_handle_time;

		return $result_data;

	}

	/**
	 * update ticket first reply time
	 * @param  [type] $ticket_id 
	 * @return [type]            
	 */
	public function update_ticket_first_reply_time($ticket_id)
	{
		$this->db->where('id', $ticket_id);
		$ticket = $this->db->get(db_prefix() . 'cs_tickets')->row();
		if($ticket){
			if($ticket->first_reply_time == null){
				$this->db->where('id', $ticket_id);
				$ticket = $this->db->update(db_prefix() . 'cs_tickets', ['first_reply_time' => date('Y-m-d H:i:s')]);
			}

			$this->db->where('id', $ticket_id);
			$ticket = $this->db->update(db_prefix() . 'cs_tickets', ['last_response_time' => date('Y-m-d H:i:s')]);
		}
		return true;
	}

	/**
	 * get ticket by sla
	 * @param  [type] $sla_id 
	 * @return [type]         
	 */
	public function get_ticket_by_sla($sla_id)
	{
		$total_ticket = 0;
		$sla_grace_period = 0;

		$arr_total_ticket = [];
		$arr_ticket_violate = [];

		$sla = $this->get_sla($sla_id);
		if($sla){
			$sla_grace_period = $sla->grace_period;
		}

		$this->db->where('sla_id', $sla_id);
		$ticket_by_kpi= $this->db->get(db_prefix() . 'cs_tickets')->result_array();
		$total_ticket = count($ticket_by_kpi);

		foreach ($ticket_by_kpi as $key => $value) {

			if($value['time_spent'] > $sla_grace_period){
				$arr_ticket_violate[] = $value;
			}

			$arr_total_ticket[] = $value;
		}

		$result_data = [];
		$result_data['arr_total_ticket'] = $arr_total_ticket;
		$result_data['arr_ticket_violate'] = $arr_ticket_violate;

		return $result_data;
	}

	/**
	 * cs get commodity
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function cs_get_commodity($id = false) {

		if (is_numeric($id)) {
			$this->db->select('*, '.db_prefix().'items.id as item_id');
			$this->db->where('id', $id);

			return $this->db->get(db_prefix() . 'items')->row();
		}
		if ($id == false) {
			$this->db->select('*, '.db_prefix().'items.id as item_id');
			return $this->db->get(db_prefix() . 'items')->result_array();
		}

	}

	/**
	 * get list item warranty by invoice
	 * @param  string $invoice_id 
	 * @param  string $item_id    
	 * @return [type]             
	 */
	public function cs_get_list_item_warranty_by_invoice($invoice_id = '', $item_id = '')
	{
		$service_details = [];
		$invoice_items = [];
		if(cs_get_status_modules('service_management')){
			$sql_where = db_prefix().'sm_service_details.invoice_id = '.$invoice_id.' AND date_format('.db_prefix().'sm_service_details.expiration_date, "%Y-%m-%d") >= "'.date('Y-m-d').'"';
			$this->db->select('id, client_id, order_id, invoice_id, item_name, billing_plan_rate, billing_plan_type, start_date, billing_plan_value as rate, quantity, expiration_date as warranty_period, item_id');
			$this->db->where($sql_where);
			if(new_strlen($item_id) > 0){
				$this->db->where(db_prefix().'sm_service_details.iteem_id = '.$item_id);
			}
			$service_details = $this->db->get(db_prefix().'sm_service_details')->result_array();
		}else{
			$this->load->model('invoices_model');
			$invoice = $this->invoices_model->get($invoice_id);
			if($invoice){
				if($invoice->items){
					foreach ($invoice->items as $key => $value) {
						$value['client_id'] = $invoice->clientid;
						$value['order_id'] = $invoice_id;
						$value['invoice_id'] = $invoice_id;
						$value['item_name'] = $value['description'];
						$value['quantity'] = $value['qty'];
						$value['warranty_period'] = 0;
						$value['date_add'] = $invoice->date;
						$value['item_id'] = $this->cs_get_itemid_from_name($value['description']);
						$invoice_items[] = $value;
					}
				}
			}

		}

		$goods_delivery_details = [];
		if(cs_get_status_modules('warehouse')){
			$sql_where = db_prefix().'goods_delivery.invoice_id = '.$invoice_id.' AND '.db_prefix().'goods_delivery.approval = 1 AND '.db_prefix().'goods_delivery.customer_code is not null AND '.db_prefix().'goods_delivery.customer_code != "" AND (date_format('.db_prefix().'goods_delivery_detail.guarantee_period, "%Y-%m-%d") >= "'.date('Y-m-d').'" OR '.db_prefix().'goods_delivery_detail.guarantee_period is NULL OR '.db_prefix().'goods_delivery_detail.guarantee_period = "" )';

			$this->db->select(db_prefix().'goods_delivery_detail.id as id, '.db_prefix() . 'goods_delivery.customer_code as client_id, '.db_prefix() . 'goods_delivery.date_add as date_add,'. db_prefix() . 'goods_delivery_detail.goods_delivery_id as order_id, '.db_prefix() . 'goods_delivery.invoice_id as invoice_id, '.db_prefix() . 'goods_delivery_detail.commodity_name as item_name, '.db_prefix() . 'goods_delivery_detail.unit_price as rate, '.db_prefix() . 'goods_delivery_detail.quantities as quantity, '.db_prefix() . 'goods_delivery_detail.expiry_date as expiry_date, '.db_prefix() . 'goods_delivery_detail.lot_number as lot_number, '.db_prefix() . 'goods_delivery_detail.serial_number as serial_number, '.db_prefix() . 'goods_delivery_detail.guarantee_period as warranty_period, '.db_prefix() . 'goods_delivery_detail.commodity_code as item_id');

			$this->db->join(db_prefix() . 'goods_delivery', '' . db_prefix() . 'goods_delivery.id = ' . db_prefix() . 'goods_delivery_detail.goods_delivery_id', 'left');

			$this->db->where($sql_where);
			if(new_strlen($item_id) > 0){
				$this->db->where(db_prefix().'goods_delivery_detail.commodity_code = '.$item_id);
			}
			$goods_delivery_details = $this->db->get(db_prefix().'goods_delivery_detail')->result_array();
		}

		return array_merge($service_details, $goods_delivery_details, $invoice_items);
	}

	/**
	 * cs get itemid from name
	 * @param  [type] $name 
	 * @return [type]       
	 */
	public function cs_get_itemid_from_name($name)
    {	
    	$item_id=0;

    	$this->db->where('description LIKE "%'.$name.'%"');
    	$item_value = $this->db->get(db_prefix().'items')->row();
    	if($item_value){
    		$item_id = $item_value->id;
    	}

    	if(!$item_value){
			if ($this->db->field_exists('commodity_code' ,db_prefix() . 'items')) { 
    			$this->db->where('CONCAT(commodity_code,"_",description) LIKE "%'.$name.'%"');
    			$item_value = $this->db->get(db_prefix().'items')->row();

    			if($item_value){
    				$item_id = $item_value->id;
    			}
    		}
    	}

    	return $item_id;
    }

	/*end file*/
}