<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('number_to_words')) {
    function number_to_words($number) {
        $hyphen      = '-';
        $conjunction = '';
        $separator   = ' ';
        $negative    = 'negative ';
        $decimal     = ' pesos and ';
        $dictionary  = array(
            0                   => 'Zero',
            1                   => 'One',
            2                   => 'Two',
            3                   => 'Three',
            4                   => 'Four',
            5                   => 'Five',
            6                   => 'Six',
            7                   => 'Seven',
            8                   => 'Eight',
            9                   => 'Nine',
            10                  => 'Ten',
            11                  => 'Eleven',
            12                  => 'Twelve',
            13                  => 'Thirteen',
            14                  => 'Fourteen',
            15                  => 'Fifteen',
            16                  => 'Sixteen',
            17                  => 'Seventeen',
            18                  => 'Eighteen',
            19                  => 'Nineteen',
            20                  => 'Twenty',
            30                  => 'Thirty',
            40                  => 'Forty',
            50                  => 'Fifty',
            60                  => 'Sixty',
            70                  => 'Seventy',
            80                  => 'Eighty',
            90                  => 'Ninety',
            100                 => 'Hundred',
            1000                => 'Thousand',
            1000000             => 'Million',
            1000000000          => 'Billion',
            // 1000000000000       => 'Trillion',
            // 1000000000000000    => 'Quadrillion',
            // 1000000000000000000 => 'Quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $fraction = str_pad($fraction, 2, '0', STR_PAD_RIGHT);
            $string .= $decimal . $fraction . '/100';
        }

        return ucfirst($string);
    }
}

$width = 225;
$height = 75;
$orientation = 'P';

$pdf->setPageFormat(array($width, $height), '');

// $pdf->SetMargins(44, 10, 20, 10);

$pdf->SetMargins(get_option('left_side_pdf_margin'), 10, get_option('right_side_pdf_margin'), 10);
$pdf->SetY(13);

$amount = number_format($expensive->amount, 2);
$amount_in_words = number_to_words($expensive->amount);

// $date = $expensive->check_date != '' ? date("m d Y", strtotime($expensive->check_date)) : '';

// if ($date) {
//     $date = implode('&nbsp;&nbsp;&nbsp;', str_split($date));
// }

$date = $expensive->check_date != '' ? date("m d Y", strtotime($expensive->check_date)) : '';

if ($date) {
    $date_parts = explode(' ', $date);

    $formatted_month = implode('&nbsp;&nbsp;&nbsp;', str_split($date_parts[0]));
    $formatted_day = implode('&nbsp;&nbsp;&nbsp;', str_split($date_parts[1]));
    $formatted_year = implode('&nbsp;&nbsp;&nbsp;', str_split($date_parts[2]));

    $formatted_date = $formatted_month . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .
                      $formatted_day . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .
                      $formatted_year;
}


// $is_activated = is_activated_module('accounting');

// if($expensive->vendor_payee != '' && $expensive->check_payee == 1 ) {
//     $vendor = $expensive->vendor_payee;
// } else {
//     if($is_activated == '' && $is_activated == 0) {
//         $vendor = $expensive->name;
//     } else {
//         $vendor = acc_get_vendor_name($expensive->vendor);
//     }
// }

$vendor = $expensive->vendor_payee;


$html = <<<EOF
<style>
    table {
        width: 100%;
    }
    .date {
        text-align: left;
        font-weight: bolder;
        font-size: 16px;
        line-height: 1.8;
    }
    .vendor {
        font-weight: bolder;
        text-align: left;
        font-size: 16px;
        line-height: 1.8;
    }
    .amount {
        font-weight: bolder;
        text-align: right;
        line-height: 1.8;
        margin-bottom:0px;
        font-size:16px;
    }
    .amount-in-words {
        font-weight: bolder;
        text-align: left;
        font-size: 16px;
        line-height: 1.8;
    }
</style>
<table>
    <tr>
        <td colspan="6"></td>
        <td class="date" colspan="3">$formatted_date</td>
    </tr>
    <tr>
        <td class="vendor" colspan="6">$vendor</td>
        <td class="amount" colspan="2">$amount</td>
        <td></td>
    </tr>
    <tr>
        <td class="amount-in-words" colspan="9">$amount_in_words</td>
    </tr>
</table>
EOF;

$pdf->writeHTML($html, true, false, true, false, '');

?>
