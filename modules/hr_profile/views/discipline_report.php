<?php

defined('BASEPATH') or exit('No direct script access allowed');

$dimensions = $pdf->getPageDimensions();
$pdf->ln(4);

$info_left_column = pdf_logo_url();
$info_right_column = staff_profile_image($discipline->staff_id, array('staff-profile-image-small','mright10'),'small',[
    'height' => '100',
    'width'  => '100',
]);


pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);
$pdf->ln(5);

$subject = '<span style="font-weight:bold;font-size:20px;">DICIPLINARY ACTION FORM</span>';
$date = '<span style="font-size:15px;">Date : '.$discipline->date.'</span>';

$pdf->writeHTMLCell(0, 0, '', '', $subject, 0, 1, 0, true, 'C', true);
$pdf->writeHTMLCell(0, 0, '', '', $date, 0, 1, 0, true, 'L', true);

$staff_name = '<span style="font-size:15px;">Name of employee : '.get_staff_full_name($discipline->staff_id).'</span>';
$staff = get_staff($discipline->staff_id);
$staff_position = '<span style="font-size:15px;">Position Title : '.$staff->job_position.'</span>';
$department = '<span style="font-size:15px;">Department : </span>';

$pdf->writeHTMLCell(0, 0, '', '', $staff_name, 0, 1, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '', $staff_position, 0, 1, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '', $department, 0, 1, 0, true, 'L', true);
$pdf->ln(5);

$penaly_data = get_penalty_data($discipline->action_taken);

// $action_taken = '<span style="font-size:15px;">Action Taken : '.$penaly_data->name.'</span>';
// $pdf->writeHTMLCell(0, 0, '', '', $action_taken, 0, 1, 0, true, 'R', true);

// $penalty_point = '<span style="font-size:15px;">Penalty Point : '.$penaly_data->point.' </span>';
// $pdf->writeHTMLCell(0, 0, '', '', $penalty_point, 0, 1, 0, true, 'R', true);
// $pdf->ln(5);

$penalty_point = '
<table width="100%">
    <tr>
        <td width="50%"></td>
        <td width="50%" align="right"><span style="font-size:15px;">Action Taken :  '.$penaly_data->name.'</span></td>
    </tr>
     <tr>
        <td width="50%"></td>
        <td width="38%" align="right"><span style="font-size:15px;">Penalty Point : '.$penaly_data->point.'</span></td>
    </tr>
</table>';

$pdf->writeHTMLCell(0, 0, '', '', $penalty_point, 0, 1, 0, true, '', true);

$re_insuboarding = '<span style="font-size:15px;">RE: Insubordination </span>';
$pdf->writeHTMLCell(0, 0, '', '', $re_insuboarding, 0, 1, 0, true, 'L', true);
$pdf->ln(5);

// $pdf->writeHTMLCell(0, 0, '', '', $discipline->content, 0, 1, 0, true, '', true);
$pdf->writeHTMLCell(0, 0, '', '', $discipline->content_template, 0, 1, 0, true, '', true);

$re_insuboarding = '<span style="font-size:15px;">Action Given To Employee Verbal Warning : '.$penaly_data->name.' </span>';
$pdf->writeHTMLCell(0, 0, '', '', $re_insuboarding, 0, 1, 0, true, 'L', true);

$pdf->ln(10);

$signature = '
<table width="100%">
    <tr>
        <td width="50%"><span style="font-size:15px;">Signature of Employee : </span></td>
        <td width="30%" align="right"><span style="font-size:15px;">Signature of Supervisor : </span></td>
    </tr>
</table>';

$pdf->writeHTMLCell(0, 0, '', '', $signature, 0, 1, 0, true, '', true);

$pdf->ln(10);

$date_table = '
<table width="100%">
    <tr>
        <td width="50%"><span style="font-size:15px;">Date : </span></td>
        <td width="15%" align="right"><span style="font-size:15px;">Date : </span></td>
    </tr>
</table>';

$pdf->writeHTMLCell(0, 0, '', '', $date_table, 0, 1, 0, true, '', true);

?>
