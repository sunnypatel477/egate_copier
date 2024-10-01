<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'issued_date',
    'staff_id',
    'issued_assets_bnefits_name',
    'returned',
    'file'
];
// if ($this->ci->input->post('main_page')) {
//     $aColumns[] = 'staff_id';
// }

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'issued_assets_bnefits';
$where = [];
if ($this->ci->input->post('staff_id')) {
    array_push($where, 'AND staff_id = ' . $this->ci->db->escape_str($this->ci->input->post('staff_id')));
}
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, ['id']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = []; // Initialize the $row array
    $buttons = ''; // Initialize the $buttons variable

    $row[] = $aRow['id'];
    $row[] = $aRow['issued_date'];
    // if ($this->ci->input->post('main_page')) {
    $row[] = get_staff_full_name($aRow['staff_id']);
    // }


    // $row[] = _d($aRow['date']);
    $row[] = $aRow['issued_assets_bnefits_name'];
    $row[] = $aRow['returned'] == 1 ? 'Returned' : 'Not Returned';
    $row[] = '<a href="' . base_url() . 'modules/' .  HR_PROFILE_MODULE_NAME . '/uploads/issued_assets_benefits/' . $aRow['file'] . '" target="_blank">' . $aRow['file'] . '</a>';

    // Add buttons
    $buttons .= '<a style="cursor:pointer;" onclick="edit_issued_assets_bnefits(this,' . $aRow['id'] . '); return false" class="fa-solid fa-edit fa-lg"></a> ';
    $buttons .= '<a href="' . admin_url('hr_profile/delete_issued_assets_bnefits/' . $aRow['id']) . '" style="cursor:pointer;color:red" id="delete_check_history" data-id ="' . $aRow['id'] . '" class="fa-solid fa-trash-can fa-lg _delete"></a>';

    $row[] = $buttons;

    $output['aaData'][] = $row;
}
