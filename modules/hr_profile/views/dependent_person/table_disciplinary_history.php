<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'staff_id',
    'date',
    'discipline_category',
    'reason',
    'file',
    '1'
];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'disciplinary_history';

$where = [];

if ($this->ci->input->post('staff_id')) {
    array_push($where, 'AND staff_id = ' . $this->ci->db->escape_str($this->ci->input->post('staff_id')));
}


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, ['id','action_taken','penalty_point']);

$output  = $result['output'];
$rResult = $result['rResult'];



foreach ($rResult as $aRow) {
    $row = []; // Initialize the $row array
    $buttons = ''; // Initialize the $buttons variable

    $penaly_data = get_penalty_data($aRow['action_taken']);

    $row[] = $aRow['id'];
    $row[] = get_staff_full_name($aRow['staff_id']);
    $row[] = _d($aRow['date']);
    $row[] = get_discipline_name($aRow['discipline_category']);
    $row[] = get_discipline_subject($aRow['reason']);
    $row[] = $penaly_data->name;
    $row[] = $aRow['penalty_point'];

    // $row[] = $aRow['file'];
    $row[] = '<a href="' . base_url() . 'modules/' .  HR_PROFILE_MODULE_NAME . '/uploads/disciplinary_file/' . $aRow['file'] . '" target="_blank">' . $aRow['file'] . '</a>';

    // Add buttons

    $buttons .= '<a style="cursor:pointer;" target="_blank" href="'.admin_url('hr_profile/print_discipline_data/'.$aRow['id']).'" class="fa-solid fa-print fa-lg"></a> ';

    $buttons .= '<a style="cursor:pointer;" onclick="edit_dependent_person(this,' . $aRow['id'] . '); return false" class="fa-solid fa-edit fa-lg"></a> ';
    $buttons .= '<a href="' . admin_url('hr_profile/delete_disciplinary_templetes/' . $aRow['id']) . '" style="cursor:pointer;color:red" id="delete_check_history" data-id ="' . $aRow['id'] . '" class="fa-solid fa-trash-can fa-lg _delete"></a>';

    $row[] = $buttons;

    $output['aaData'][] = $row;
}
