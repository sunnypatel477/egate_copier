<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Si_lead_filters extends AdminController 
{
	public function __construct()
	{
		parent::__construct(); 
		$this->load->model('si_lead_filter_model');
		$this->load->model('leads_model');
		$this->load->model('currencies_model');
		if (!is_admin() && !has_permission('si_lead_filters', '', 'view')) {
			access_denied(_l('si_lead_filters'));
		}
	}
	
	private function get_where_report_period($field = 'date',$months_report='this_month')
	{
		$custom_date_select = '';
		if ($months_report != '') {
			if (is_numeric($months_report)) {
				// Last month
				if ($months_report == '1') {
					$beginMonth = date('Y-m-01', strtotime('first day of last month'));
					$endMonth   = date('Y-m-t', strtotime('last day of last month'));
				} else {
					$months_report = (int) $months_report;
					$months_report--;
					$beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
					$endMonth   = date('Y-m-t');
				}

				$custom_date_select = 'AND (' . $field . ' BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
			} elseif ($months_report == 'today') {
				$custom_date_select = 'AND (' . $field . ' BETWEEN "' . date('Y-m-d') . '" AND "' . date('Y-m-d') . '")';
			} elseif ($months_report == 'this_week') {
				$custom_date_select = 'AND (' . $field . ' BETWEEN "' . date('Y-m-d', strtotime('monday this week')) . '" AND "' . date('Y-m-d', strtotime('sunday this week')) . '")';
			} elseif ($months_report == 'last_week') {
				$custom_date_select = 'AND (' . $field . ' BETWEEN "' . date('Y-m-d', strtotime('monday last week')) . '" AND "' . date('Y-m-d', strtotime('sunday last week')) . '")';	
			} elseif ($months_report == 'this_month') {
				$custom_date_select = 'AND (' . $field . ' BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
			} elseif ($months_report == 'this_year') {
				$custom_date_select = 'AND (' . $field . ' BETWEEN "' .
				date('Y-m-d', strtotime(date('Y-01-01'))) .
				'" AND "' .
				date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
			} elseif ($months_report == 'last_year') {
				$custom_date_select = 'AND (' . $field . ' BETWEEN "' .
				date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
				'" AND "' .
				date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
			} elseif ($months_report == 'custom') {
				$from_date = to_sql_date($this->input->post('report_from'));
				$to_date   = to_sql_date($this->input->post('report_to'));
				if ($from_date == $to_date) {
					$custom_date_select = 'AND ' . $field . ' = "' . $from_date . '"';
				} else {
					$custom_date_select = 'AND (' . $field . ' BETWEEN "' . $from_date . '" AND "' . $to_date . '")';
				}
			}
		}
		
		 return $custom_date_select;
	}
	
	public function leads_filter()
	{
		//for kanban
		$data['switch_kanban'] = true;	

		if ($this->session->userdata('si_leads_kanban_view') == 'true') {
            $data['switch_kanban'] = false;
            $data['bodyclass']     = 'kan-ban-body';
        }
		//end for kanban
		
		$saved_filter_name='';
		$filter_id = $this->input->get('filter_id');
		if($filter_id!='' && is_numeric($filter_id) && empty($this->input->post()))
		{
			$filter_obj = $this->si_lead_filter_model->get($filter_id);
			if(!empty($filter_obj))
			{
				$_POST = unserialize($filter_obj->filter_parameters);
				$saved_filter_name = $filter_obj->filter_name;
			}	
		}	

		$has_permission_view   = has_permission('leads', '', 'view');

		if (!$has_permission_view) {
			$staff_id = get_staff_user_id();
		} elseif ($this->input->post('member')) {
			$staff_id = $this->input->post('member');
		} else {
			$staff_id = '';
		}
		$status = $this->input->post('status');
		if(empty($status))
			$status=array('');
		$source = $this->input->post('source');
		if(empty($source))
			$source=array('');	
		$tag = $this->input->post('tags');
		if(empty($tag))
			$tag=array('');
		$country = $this->input->post('countries');
		if(empty($country))
			$country=array('');
		$city = $this->input->post('cities');
		if(empty($city))
			$city=array('');
		$state = $this->input->post('states');
		if(empty($state))
			$state=array('');
		$zip = $this->input->post('zips');
		if(empty($zip))
			$zip=array('');				
		
		$type = $this->input->post('type');	
				
		$hide_columns = $this->input->post('hide_columns');
		if(empty($hide_columns))
			$hide_columns=array();
			
		if ($this->input->post('date_by')) {
			$date_by = $this->input->post('date_by');
		} else {
			$date_by = 'dateadded';
		}

		$cf = $this->input->post('cf') ? $this->input->post('cf') : [];
		
		$fetch_month_from = $date_by;
		
		if ($this->input->post('report_months')!='')
			$report_months = $this->input->post('report_months');
		elseif($this->input->post('report_months')=='' && $filter_id=='' && $this->input->server('REQUEST_METHOD') !== 'POST')
			$report_months = 'this_month';//by default when loaded
		else
			$report_months = '';
		
		$save_filter = $this->input->post('save_filter');
		$filter_name='';
		$current_user_id = get_staff_user_id();
		if($save_filter==1)
		{
			$filter_name=$this->input->post('filter_name');
			$all_filter = $this->input->post();
			unset($all_filter['save_filter']);
			unset($all_filter['filter_name']);
			$saved_filter_name = $filter_name;
			$filter_parameters = serialize($all_filter);
			$filter_data = array('filter_name'=>$filter_name,
								 'filter_parameters'=>$filter_parameters,
								 'staff_id'=>$current_user_id);
			if($filter_id!='' && is_numeric($filter_id))
				$this->si_lead_filter_model->update($filter_data,$filter_id);
			else					 
				$new_filter_id = $this->si_lead_filter_model->add($filter_data);
		}

		$list_custom_field = si_lf_get_custom_fields_from_settings();

		$data['title']    = _l('si_lf_submenu_lead_filters');
		$data['lead_statuses'] = $this->leads_model->get_status();
		$data['lead_sources']  = $this->leads_model->get_source();
		$data['lead_countries']  = $this->si_lead_filter_model->get_leads_country_list();
		$data['lead_cities']  = $this->si_lead_filter_model->get_leads_city_list();
		$data['lead_states']  = $this->si_lead_filter_model->get_leads_state_list();
		$data['lead_zips']  = $this->si_lead_filter_model->get_leads_zip_list();
		$data['members']  = $this->staff_model->get();
		$data['staff_id'] = $staff_id;
		$data['saved_filter_name'] = $saved_filter_name;
		$data['date_by'] = $date_by;
		$data['statuses']  =$status;
		$data['sources']  =$source;
		$data['tags']  =$tag;
		$data['countries']  =$country;
		$data['cities']  =$city;
		$data['states']  =$state;
		$data['zips']  =$zip;
		$data['type']=$type;
		$data['report_months'] = $report_months;
		$data['report_from'] = $this->input->post('report_from');
		$data['report_to'] = $this->input->post('report_to');
		$data['hide_columns'] = $hide_columns;
		$data['filter_templates'] = $this->si_lead_filter_model->get_templates($current_user_id);
		$data['summary']  = $this->get_leads_summary();
		$data['list_custom_field'] = $list_custom_field;
		$data['selected_custom_fields'] = $cf;
		$data['isKanBan'] = $this->session->has_userdata('si_leads_kanban_view') &&
            $this->session->userdata('si_leads_kanban_view') == 'true';
		
		$this->load->view('lead_report', $data);
	}
	
	function table()
	{
		$data = $this->input->post();
		
		$data['custom_date_select'] = '';
		$date_by = 'dateadded';
		if ($data['date_by']!='')
			$date_by = $data['date_by'];
		
		if ($data['report_months']!=''){
			$report_months = $data['report_months'];
			$data['custom_date_select'] = $this->get_where_report_period('DATE('.$date_by.')',$report_months);
		}	
		$data['perfex_version'] = (int)$this->app->get_current_db_version();
		
		$this->app->get_table_data(module_views_path(SI_LEAD_FILTERS_MODULE_NAME,'tables/leads'), $data);
	}

	public function switch_kanban($set = 0)
    {
        if ($set == 1) {
            $set = 'true';
        } else {
            $set = 'false';
        }
        $this->session->set_userdata([
            'si_leads_kanban_view' => $set,
        ]);
        redirect($this->session->userdata('_prev_url') ?: $_SERVER['HTTP_REFERER']);
    }

	public function kanban()
    {
        if (!is_staff_member()) {
            ajax_access_denied();
        }

		$overview = [];

		$status = $this->input->post('status');
		if(empty($status))
			$status=array('');

		$lead_statuses = $this->leads_model->get_status();
		foreach($lead_statuses as $lead_status){
			$overview[$lead_status['id']]	=	$this->get_kanban_data($lead_status['id'],1,[
				'sort_by' =>  $this->input->post('sort_type'),
				'sort'    =>  $this->input->post('sort'),
			]);

			$overview[$lead_status['id']]['total_leads'] = $this->get_kanban_data($lead_status['id'],1,[
				'sort_by' =>  $this->input->post('sort_type'),
				'sort'    =>  $this->input->post('sort'),
			],true);
		}
		

		$data['overview'] 		= $overview;
		$data['lead_statuses'] 	= $lead_statuses;
		$data['statuses']  		= $status;
        $data['base_currency'] 	= get_base_currency();
        $data['summary']        = $this->get_leads_summary();
		$data['perfex_version'] = (int)$this->app->get_current_db_version();

        echo $this->load->view('kan-ban', $data, true);
		die();
    }

	public function leads_kanban_load_more()
    {
        if (!is_staff_member()) {
            ajax_access_denied();
        }

        $status = $this->input->post('status');
        $page   = $this->input->post('page');

		$this->db->where('id', $status);
        $status = $this->db->get(db_prefix() . 'leads_status')->row_array();

		$leads	=	$this->get_kanban_data($status['id'],$page,[
			'sort_by' => $this->input->post('sort_type'),
			'sort'    => $this->input->post('sort'),
		]);

        foreach ($leads as $lead) {
            echo $this->load->view('_kan_ban_card', [
                'lead'   => $lead,
                'status' => $status,
            ],true);
        }
    }

	private function get_kanban_data($status_id = '',$page = 1,$sort = [], $count = false)
	{
		$limit                          = get_option('leads_kanban_limit');
        $default_leads_kanban_sort      = get_option('default_leads_kanban_sort');
        $default_leads_kanban_sort_type = get_option('default_leads_kanban_sort_type');
        $has_permission_view            = has_permission('leads', '', 'view');

		if (!$has_permission_view) {
			$staff_id = get_staff_user_id();
		} elseif ($this->input->post('member')) {
			$staff_id = $this->input->post('member');
		} else {
			$staff_id = '';
		}
		/* $status = $this->input->post('status');
		if(empty($status))
			$status=array(''); */
		$source = $this->input->post('source');
		if(empty($source))
			$source=array('');	
		$tag = $this->input->post('tags');
		if(empty($tag))
			$tag=array('');
		$country = $this->input->post('countries');
		if(empty($country))
			$country=array('');
		$city = $this->input->post('cities');
		if(empty($city))
			$city=array('');
		$state = $this->input->post('states');
		if(empty($state))
			$state=array('');
		$zip = $this->input->post('zips');
		if(empty($zip))
			$zip=array('');				
		
		$type = $this->input->post('type');	
				
		$hide_columns = $this->input->post('hide_columns');
		if(empty($hide_columns))
			$hide_columns=array();
			
		if ($this->input->post('date_by')) {
			$date_by = $this->input->post('date_by');
		} else {
			$date_by = 'dateadded';
		}

		$cf = $this->input->post('cf') ? $this->input->post('cf') : [];
		
		$fetch_month_from = $date_by;
		
		if ($this->input->post('report_months')!='')
			$report_months = $this->input->post('report_months');
		else
			$report_months = '';

        //get query Leads
		$sqlLeadsSelect = db_prefix().'leads.*,'.db_prefix().'leads_sources.name as source_name,
		(SELECT count(leadid) FROM ' . db_prefix() . 'clients WHERE ' . db_prefix() . 'clients.leadid=' . db_prefix() . 'leads.id) as is_converted, 
		(SELECT COUNT(id) FROM ' . db_prefix() . 'files WHERE rel_id=' . db_prefix() . 'leads.id AND rel_type="lead") as total_files,
		(SELECT COUNT(id) FROM ' . db_prefix() . 'notes WHERE rel_id=' . db_prefix() . 'leads.id AND rel_type="lead") as total_notes,
		(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'leads.id and rel_type="lead" ORDER by tag_order ASC) as tags';
		
		$this->db->select($sqlLeadsSelect);
		
		$this->db->join(db_prefix() . 'leads_sources' , db_prefix() . 'leads_sources.id = ' . db_prefix() . 'leads.source','left');
		
		if($report_months!=''){
			$custom_date_select = $this->get_where_report_period('DATE('.$fetch_month_from.')',$report_months);
			$this->db->where("1=1 ".$custom_date_select);
		}
		
		if(!$has_permission_view){
			$this->db->where('(assigned =' . $staff_id . ' OR addedfrom = ' . $staff_id . ' OR is_public = 1)');
		}
		elseif ($has_permission_view) {
			if (is_numeric($staff_id)) {
				$this->db->where('assigned',$staff_id);
			}
		}
		
		/* if ($status && !in_array('',$status)) {
			$this->db->where_in('status', $status);
		} */

		if(is_numeric($status_id)){
			$this->db->where('status', $status_id);
		}
		
		if ($source && !in_array('',$source)) {
			$this->db->where_in('source', $source);
		}
		
		if ($tag && !in_array('',$tag)) {
			$this->db->join(db_prefix() . 'taggables' , '('.db_prefix() . 'taggables.rel_id = ' . db_prefix() . 'leads.id and rel_type=\'lead\')','left');
			$this->db->where_in('tag_id', $tag);
			$this->db->group_by(db_prefix() . 'leads.id');
		}
		if ($country && !in_array('',$country)) {
			if(in_array(-1,$country))//if country is unknown
				$country[]=0;
			$this->db->where_in('country', $country);
		}
		if ($city && !in_array('',$city)) {
			$where_city	=	' city in ("'.implode('","',$city).'")';
			if(in_array(-1,$city)){//if city is unknown
				$where_city .= " or city='' or city IS NULL";
			}
			$this->db->where('('. $where_city.')');
		}
		if ($state && !in_array('',$state)) {
			$where_state	=	' state in ("'.implode('","',$state).'")';
			if(in_array(-1,$state)){//if state is unknown
				$where_state .= " or state='' or state IS NULL";
			}
			$this->db->where('('. $where_state.')');
		}
		if ($zip && !in_array('',$zip)) {
			$where_zip	=	' zip in ("'.implode('","',$zip).'")';
			if(in_array(-1,$zip)){//if zip is unknown
				$where_zip .= " or zip='' or zip IS NULL";
			}
			$this->db->where('('. $where_zip.')');
		}
		if($type!='')
		{
			if($type=='lost')
				$this->db->where('lost',1);
			if($type=='junk')
				$this->db->where('junk',1);
			if($type=='public')
				$this->db->where('is_public',1);
			if($type=='not_assigned')
				$this->db->where('assigned',0);			
		}

		//filter by custom fields
		if(!empty($cf)){
			foreach($cf as $_cf=>$value){
				$this->db->where(db_prefix() . 'leads.id in (SELECT relid FROM '.db_prefix() . 'customfieldsvalues  where fieldid='.$_cf.' and value in ("'.implode('","',$value).'"))');
			}	
		}

		if (isset($sort['sort_by']) && $sort['sort_by'] != '' && isset($sort['sort']) && $sort['sort'] != '') {
            $this->db->order_by($sort['sort_by'], $sort['sort']);
        } else {
            $this->db->order_by($default_leads_kanban_sort, $default_leads_kanban_sort_type);
        }

		if ($count == false) {
            if ($page > 1) {
                $page--;
                $position = ($page * $limit);
                $this->db->limit($limit, $position);
            } else {
                $this->db->limit($limit);
            }
        }

		$this->db->from(db_prefix() . 'leads');

        if ($count == false) {
            return $this->db->get()->result_array();
        }

        return $this->db->count_all_results();
	}
	
	private function get_leads_summary()
	{
		$perfex_version = (int)$this->app->get_current_db_version();
		$statuses = $this->leads_model->get_status();
		$statuses[] = [
			'lost'  => true,
			'name'  => _l('lost_leads'),
			'color' => '#fc2d42',
		];
		$statuses[] = [
			'junk'  => true,
			'name'  => _l('junk_leads'),
			'color' => '#fc2d430',
		];
		#get leads count by status, if status is zero, then get Lost and Junk Leads count
		$filter_id = $this->input->get('filter_id');
		if($filter_id!='' && is_numeric($filter_id) && empty($this->input->post()))
		{
			$filter_obj = $this->si_lead_filter_model->get($filter_id);
			if(!empty($filter_obj))
			{
				$_POST = unserialize($filter_obj->filter_parameters);
			}	
		}	

		$has_permission_view   = has_permission('leads', '', 'view');

		if (!$has_permission_view) {
			$staff_id = get_staff_user_id();
		} elseif ($this->input->post('member')) {
			$staff_id = $this->input->post('member');
		} else {
			$staff_id = '';
		}
		$status = $this->input->post('status');
		if(empty($status))
			$status=array('');
		$source = $this->input->post('source');
		if(empty($source))
			$source=array('');	
		$tag = $this->input->post('tags');
		if(empty($tag))
			$tag=array('');
		$country = $this->input->post('countries');
		if(empty($country))
			$country=array('');
		$city = $this->input->post('cities');
		if(empty($city))
			$city=array('');
		$state = $this->input->post('states');
		if(empty($state))
			$state=array('');
		$zip = $this->input->post('zips');
		if(empty($zip))
			$zip=array('');				
		
		$type = $this->input->post('type');	
			
		if ($this->input->post('date_by')) {
			$date_by = $this->input->post('date_by');
		} else {
			$date_by = 'dateadded';
		}

		$cf = $this->input->post('cf') ? $this->input->post('cf') : [];
		
		$fetch_month_from = $date_by;
		
		if ($this->input->post('report_months')!='')
			$report_months = $this->input->post('report_months');
		elseif($this->input->post('report_months')=='' && $filter_id=='' && $this->input->server('REQUEST_METHOD') !== 'POST')
			$report_months = 'this_month';//by default when loaded
		else
			$report_months = '';
		
		//get query Leads
		$sqlLeadsSelect = db_prefix().'leads.status,count(*) as total,sum(lost) as total_lost,sum(junk) as total_junk';	
		if($perfex_version >= 250)
			$sqlLeadsSelect .= ',SUM(lead_value) as total_value';

		$this->db->select($sqlLeadsSelect);
		
		if($report_months!=''){
			$custom_date_select = $this->get_where_report_period('DATE('.$fetch_month_from.')',$report_months);
			$this->db->where("1=1 ".$custom_date_select);
		}
		
		if(!$has_permission_view){
			$this->db->where('(assigned =' . $staff_id . ' OR addedfrom = ' . $staff_id . ' OR is_public = 1)');
		}
		elseif ($has_permission_view) {
			if (is_numeric($staff_id)) {
				$this->db->where('assigned',$staff_id);
			}
		}
		
		if ($status && !in_array('',$status)) {
			$this->db->where_in('status', $status);
		}
		
		if ($source && !in_array('',$source)) {
			$this->db->where_in('source', $source);
		}
		
		if ($tag && !in_array('',$tag)) {
			$this->db->join(db_prefix() . 'taggables' , '('.db_prefix() . 'taggables.rel_id = ' . db_prefix() . 'leads.id and rel_type=\'lead\')','left');
			$this->db->where_in('tag_id', $tag);
			$this->db->group_by(db_prefix() . 'leads.id');
		}
		if ($country && !in_array('',$country)) {
			if(in_array(-1,$country))//if country is unknown
				$country[]=0;
			$this->db->where_in('country', $country);
		}
		if ($city && !in_array('',$city)) {
			$where_city	=	' city in ("'.implode('","',$city).'")';
			if(in_array(-1,$city)){//if city is unknown
				$where_city .= " or city='' or city IS NULL";
			}
			$this->db->where('('. $where_city.')');
		}
		if ($state && !in_array('',$state)) {
			$where_state	=	' state in ("'.implode('","',$state).'")';
			if(in_array(-1,$state)){//if state is unknown
				$where_state .= " or state='' or state IS NULL";
			}
			$this->db->where('('. $where_state.')');
		}
		if ($zip && !in_array('',$zip)) {
			$where_zip	=	' zip in ("'.implode('","',$zip).'")';
			if(in_array(-1,$zip)){//if zip is unknown
				$where_zip .= " or zip='' or zip IS NULL";
			}
			$this->db->where('('. $where_zip.')');
		}
		if($type!='')
		{
			if($type=='lost')
				$this->db->where('lost',1);
			if($type=='junk')
				$this->db->where('junk',1);
			if($type=='public')
				$this->db->where('is_public',1);
			if($type=='not_assigned')
				$this->db->where('assigned',0);			
		}

		//filter by custom fields
		if(!empty($cf)){
			foreach($cf as $_cf=>$value){
				$this->db->where(db_prefix() . 'leads.id in (SELECT relid FROM '.db_prefix() . 'customfieldsvalues  where fieldid='.$_cf.' and value in ("'.implode('","',$value).'"))');
			}	
		}

		$this->db->group_by('status');
		$result = $this->db->get(db_prefix() . 'leads');
		$status_count = $vaule_count = array();
		$total_leads = 0;
		if($result) {
			$result = $result->result_array();
			foreach($result as $_status) {
				if($_status['status']==0) {
					$status_count['junk'] = $_status['total_junk'];
					$status_count['lost'] = $_status['total_lost'];
					$total_leads += $_status['total_lost'] + $_status['total_junk'];
				}
				else{
					$status_count[$_status['status']] = $_status['total'];
					$total_leads += $_status['total'];
					$vaule_count[$_status['status']] = ($perfex_version >= 250 ? $_status['total_value'] : 0);
				}
			}
		}
		foreach ($statuses as $key => $_status) {
			if(isset($_status['id']) && $_status['id']>0){
				$statuses[$key]['total'] = isset($status_count[$_status['id']])?$status_count[$_status['id']]:0;
				$statuses[$key]['value'] = isset($vaule_count[$_status['id']])?$vaule_count[$_status['id']]:0;
			}elseif(isset($_status['junk'])){
				$statuses[$key]['total'] = isset($status_count['junk'])?$status_count['junk']:0;
				$statuses[$key]['percent'] = ($total_leads > 0 ? number_format(($statuses[$key]['total'] * 100) / $total_leads, 2) : 0);
			}elseif(isset($_status['lost'])){
				$statuses[$key]['total'] = isset($status_count['lost'])?$status_count['lost']:0;
				$statuses[$key]['percent'] = ($total_leads > 0 ? number_format(($statuses[$key]['total'] * 100) / $total_leads, 2) : 0);
			}	
								
		}
	
		return $statuses;
	}
	
	function list_filters()
	{
		$data=array();
		$data['title']    = _l('si_lf_submenu_filter_templates');
		$current_user_id = get_staff_user_id();
		$data['filter_templates'] = $this->si_lead_filter_model->get_templates($current_user_id);
		$this->load->view('lead_list_filters', $data);
	}
	function del_lead_filter($id)
	{
		$current_user_id = get_staff_user_id();
		$this->si_lead_filter_model->delete($id,$current_user_id);
		redirect('si_lead_filters/list_filters');
	}
	
	function get_lead_status($id)
    {
       // if (has_permission('leads', '', 'edit')) {

            // Generate lead Status dropdown
			$lead = (array)$this->leads_model->get($id);
			$lead_statuses = $this->leads_model->get_status();
			
            $leadHtml = '';
			$success = false;
			if(!empty($lead)){
				$status          = si_get_lead_status_by_id($lead['status']);
				$leadHtml    = '';
			
				$leadHtml .= '<span class="inline-block label" style="color:' . $status['color'] . ';border:1px solid ' . $status['color'] . '" task-status-table="' . $lead['status'] . '">';
			
				$leadHtml .= $status['name'];
			
				$leadHtml .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
				$leadHtml .= '<a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark" id="tableLeadsStatus-' . $lead['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
				$leadHtml .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
				$leadHtml .= '</a>';
		
				$leadHtml .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tableLeadsStatus-' . $lead['id'] . '">';
				foreach ($lead_statuses as $leadChangeStatus) {
					if ($lead['status'] != $leadChangeStatus['id']) {
						$leadHtml .= '<li>
						  <a href="#" onclick="si_leads_status_update(' . $leadChangeStatus['id'] . ',' . $lead['id'] . '); return false;">
							 ' . $leadChangeStatus['name'] . '
						  </a>
					   </li>';
					}
				}
				$leadHtml .= '</ul>';
				$leadHtml .= '</div>';
			
				$leadHtml .= '</span>';
				$success = true;

           }
			echo json_encode([
				'success'  => $success,
				'leadHtml' => $leadHtml,
			]);
        
       /* } else {
            echo json_encode([
                'success'  => false,
                'leadHtml' => '',
            ]);
        }*/
    }

	function settings()
	{
		if (!is_admin() && !has_permission('si_lead_filters', '', 'settings')) {
			access_denied(_l('settings'));
		}
		if($this->input->post()){
			$custom_fields = (!is_null($this->input->post('si_lf_cf')) ? $this->input->post('si_lf_cf') : array());
			update_option(SI_LEAD_FILTERS_MODULE_NAME.'_cf',serialize($custom_fields));
			set_alert('success',  _l('updated_successfully', _l('settings')));
			redirect(admin_url('si_lead_filters/settings'));
		}
		$data['title']    = _l('si_lf_title_settings');
		$data['custom_fields'] = get_custom_fields('leads', 'show_on_table = 1 and type not in ("date_picker_time","date_picker")');
		$data['selected_custom_fields'] = (get_option(SI_LEAD_FILTERS_MODULE_NAME.'_cf')!=="" ? unserialize(get_option(SI_LEAD_FILTERS_MODULE_NAME.'_cf')) : array());

		$this->load->view('lead_settings', $data);

	}
}
