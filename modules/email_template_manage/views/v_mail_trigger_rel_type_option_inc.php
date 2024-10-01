
<?php

$record_status  = [];
$record_sources = [];
$record_priority= [];
$record_date    = "";
$record_day     = "";

if ( !empty( $record_data->options ) )
{

    $options   = json_decode( $record_data->options );

    if ( !empty( $options->status ) )
        $record_status = (array)$options->status;

    if ( !empty( $options->sources ) )
        $record_sources = (array)$options->sources;

    if ( !empty( $options->priority ) )
        $record_priority = (array)$options->priority;

    if ( !empty( $options->record_date ) )
        $record_date = $options->record_date;

    if ( isset( $options->record_day ) )
        $record_day = $options->record_day;

}

?>
