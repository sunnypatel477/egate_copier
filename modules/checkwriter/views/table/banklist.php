<?php 
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'bank_name',
    'account_no',
    'account_notes',
    '1',
];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'bank_details';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    $row[] = $aRow['id'];
    $row[] = $aRow['bank_name'];
    $row[] = $aRow['account_no'];
    $row[] = $aRow['account_notes'];

    $buttons = '<a style="cursor:pointer;" id="edit_bank_detail" data-id ="'.$aRow['id'].'" class="fa-regular fa-pen-to-square fa-lg"></a> ';
    $buttons .= '<a style="cursor:pointer;color:red;" id="delete_bank_detail" data-id ="'.$aRow['id'].'" class="fa-regular fa-trash-can fa-lg"
    ></a>';
    $row[] = $buttons;
    $output['aaData'][] = $row;
}
