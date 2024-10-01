<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'name',
    
];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'instax_printing_order_from';
$CI = &get_instance();
$where = [];
$join = array();

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array());
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];

    $row[] = $aRow['id'];
    $row[] = $aRow['name'];
   
    $options = "";
    

    if ( is_admin()) {
        $options .= icon_btn(admin_url('instax_printing/delete_order_from/' . $aRow['id']), 'fa-regular fa-trash-can', 'btn-danger _delete');
        $options .= icon_btn(admin_url('instax_printing/create_order_from/' . $aRow['id']), 'fa-regular fa-edit', 'btn-info');
    }


    $row[] = $options;

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
