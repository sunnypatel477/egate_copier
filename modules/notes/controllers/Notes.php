<?php

class Notes extends AdminController
{

    public $table_note       = 'notes';

    public $rel_type_name = [
        'customer'          => 'client',
        'lead'              => 'lead',
        'contract'          => 'contract',
        'proposal'          => 'proposal',
        'invoice'           => 'invoice',
        'estimate'          => 'estimate',
        'staff'             => 'staff',
        'ticket'            => 'ticket',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->table_note      = db_prefix().'notes';
    }

    public function note(){

        $data['title']      = _l('contracts_notes_tab');
        $data['rel_type']   = $this->rel_type_name;
        $data['staffs']     = $this->db->select("CONCAT(firstname, ' ' , lastname) as fullname , staffid")->from(db_prefix() . "staff")->where("active" , 1)->get()->result();
        $this->load->view('manage_notes', $data);
    }

    public function note_lists(){

        $sTable = $this->table_note;


        $sql_client = "( 
        
            CASE WHEN rel_type = 'customer' THEN rel_id
                WHEN  rel_type = 'contract' THEN ( SELECT client FROM ".db_prefix()."contracts WHERE id = $sTable.rel_id )
                WHEN  rel_type = 'invoice' THEN ( SELECT clientid FROM ".db_prefix()."invoices WHERE id = $sTable.rel_id )
                WHEN  rel_type = 'proposal' THEN ( SELECT rel_id FROM ".db_prefix()."proposals pro WHERE id = $sTable.rel_id AND pro.rel_type = 'customer' )
                WHEN  rel_type = 'lead' THEN ( SELECT userid FROM ".db_prefix()."clients   WHERE leadid = $sTable.rel_id   )
                ELSE 0 END
        
        ) as client_id";

        $select = [

            'id',

            'rel_type',

            'rel_id',

            $sql_client ,

            'description',

            'addedfrom',

            'dateadded',

        ];

        $where = [];



        $from_date      = to_sql_date( $this->input->post('from_date') );
        $to_date        = to_sql_date( $this->input->post('to_date') );
        $source         = $this->input->post('source');
        $addedfrom      = $this->input->post('addedfrom');
        $client_id      = $this->input->post('client_id');

        if( !empty( $from_date ) )
            $where[] = "AND DATE( dateadded ) >= '$from_date'";

        if( !empty( $to_date ) )
            $where[] = "AND DATE( dateadded ) <= '$to_date'";

        if (!empty($source))
            $where[] = "AND rel_type = '$source'";

        if (!empty($addedfrom))
            $where[] = "AND addedfrom = '$addedfrom'";


        if( !empty( $client_id ) )
        {

            $where_sql = " ( rel_type = 'customer' AND rel_id = $client_id ) ";

            $where_sql .= " OR ( rel_type = 'proposal' AND rel_id IN ( SELECT id FROM ".db_prefix()."proposals pro WHERE pro.rel_type = 'customer' AND pro.rel_id = $client_id ) ) ";

            $where_sql .= " OR ( rel_type = 'invoice' AND rel_id IN ( SELECT id FROM ".db_prefix()."invoices WHERE clientid = $client_id ) ) ";

            $where_sql .= " OR ( rel_type = 'contract' AND rel_id IN ( SELECT id FROM ".db_prefix()."contracts WHERE client = $client_id ) ) ";

            $where_sql .= " OR ( rel_type = 'lead' AND rel_id IN ( SELECT leadid FROM ".db_prefix()."clients WHERE userid = $client_id ) ) ";

            $where[] = " AND ( $where_sql ) ";

        }


        $sIndexColumn = 'id';

        $join = [];

        $result = data_tables_init($select, $sIndexColumn, $sTable, $join, $where , []);

        $output = $result['output'];

        $rResult = $result['rResult'];

        foreach ($rResult as $aRow) {

            $row = [];

            $row[] =  $aRow["id"];

            if ( !empty( $this->rel_type_name[$aRow['rel_type']] ) )
                $row[] = _l( $this->rel_type_name[$aRow['rel_type']] ) ;
            elseif ( $aRow['rel_type'] == 'special_note' )
                $row[] = 'Special';
            else
                $row[] = _l( $aRow['rel_type'] ) ;


            if ( $aRow['rel_type'] == 'special_note' )
                $aRow['rel_type'] = 'staff';

            $rel_data   = get_relation_data($aRow['rel_type'], $aRow['rel_id']);
            $rel_values = get_relation_values($rel_data, $aRow['rel_type']);

            $rel_link =  $rel_values['link'];
            $rel_name =  $rel_values['name'];

            $rel_link .= $this->note_relation_detail_add($aRow['rel_type']);


            $row[] = "<a href='$rel_link' target='_blank'> $rel_name </a>";


            $row_client = '';

            if ( !empty( $aRow['client_id'] ) )
            {

                $cliend_data = get_client( $aRow['client_id']  );


                if ( !empty( $cliend_data ) )
                {
                    if ( !empty( $cliend_data->company ) )
                        $row_client = $cliend_data->company;
                }


            }

            $row[] = $row_client;


            $desc_lenght = strlen($aRow['description']);

            $note_id = $aRow["id"];

            if ($desc_lenght > 75 )
                $row[] = "<a onclick='note_module_model_get_detail($note_id)' style='text-decoration: none; cursor: pointer'>" . mb_substr(  $aRow['description'] , 0, 75, 'UTF-8') . "... </a>";
            else
                $row[] = $aRow['description'];


            $row[] = get_staff_full_name($aRow['addedfrom']);

            $row[] = _dt($aRow['dateadded']);


            $output['aaData'][] = $row;
        }

        echo json_encode($output);

        die;

    }

    function note_relation_detail_add($rel_type){


        $rel_type_link = [
            'lead'              => "?tab=note",
            'contract'          => '?tab=note',
            'customer'          => '?group=notes',
            'proposal'          => '?tab=note',
            'ticket'            => '?tab=note',
            'invoice'           => '?tab=note',
            'estimate'          => '?tab=note',
        ];

        return !empty($rel_type_link[$rel_type]) ? $rel_type_link[$rel_type] : "";

    }

    function note_module_model_get_detail(){

        if( $this->input->is_ajax_request() ){

            $note_id = $this->input->post('record_id');

            $note_detail = $this->db->select("description")->from($this->table_note)->where("id" , $note_id)->get()->row();

            if (!empty($note_detail)){

                $data['detail'] = $note_detail;

                echo json_encode( $data );

            }


        }

    }

    /**
     * add new note from list
     */
    function add_note()
    {

        if ($this->input->post())
        {

            $post_data['description']  = $this->input->post('description');

            $rel_type   = $this->input->post('rel_type');
            $rel_id     = $this->input->post('rel_id');

            if ( $rel_type == 'special_note' )
                $rel_id = get_staff_user_id();

            $success = $this->misc_model->add_note($post_data, $rel_type, $rel_id);

            if ( $success )
            {

                set_alert('success', _l('added_successfully', _l('note')));

            }

        }

        redirect($_SERVER['HTTP_REFERER']);

    }

}
