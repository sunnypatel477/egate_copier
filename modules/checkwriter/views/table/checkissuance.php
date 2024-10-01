<?php 
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'date',
    'vendor_payee',
    'bank',
    'check_no',
    'check_date',
    'amount',
    'check_note',
    'note',
    '1'
];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'expensive_history';

$where = [];

if ($this->ci->input->post('bank_filter')) {
    array_push($where, 'AND bank = ' . $this->ci->db->escape_str($this->ci->input->post('bank_filter')));
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, ['id','expensive_id']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['date'];

    $row[] = $aRow['vendor_payee'];

    if(!empty($aRow['bank'])) {
        $bank_name = get_bank_details($aRow['bank']);
        $row[] = $bank_name->bank_name;
    } else { 
        $row[] = '';
    }

    $row[] = $aRow['check_no'];

    // $row[] = $aRow['amount'];

   if(!empty($aRow['check_date'])) {
        $row[] = $aRow['check_date'];
    } else {
        $row[] = '';
    }
   

    $row[] = '<a style="cursor:pointer;" href="'.admin_url('expenses/expense/'.$aRow['expensive_id']).'" data-id ="'.$aRow['id'].'">'._l(number_format($aRow['amount'], 2)).'</a>';


    $row[] = $aRow['check_note'];

    $row[] = $aRow['note'];

    $buttons = '<a style="cursor:pointer;" id="edit_check_history" data-id ="'.$aRow['id'].'" class="fa-regular fa-pen-to-square fa-lg"></a> ';

    $buttons .= '<a style="cursor:pointer;" id="printCheckBtn" target="_blank" href="' . admin_url('checkwriter/printcheckhistory/' . $aRow['id']) . '" class="fa-solid fa-print fa-lg"></a> ';

    $buttons .= '<a style="cursor:pointer;color:red" id="delete_check_history" data-id ="'.$aRow['id'].'" class="fa-solid fa-trash-can fa-lg"
    ></a>';
    

    $row[] = $buttons;
    $output['aaData'][] = $row;
}
