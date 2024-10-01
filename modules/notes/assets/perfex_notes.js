
function perfex_note_get_tab_is_note()
{

    var param_name = 'tab';

    let regex = new RegExp('[?&]' + param_name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec( window.location.href );

    if (!results) return null;
    if (!results[2]) return '';

    return decodeURIComponent(results[2].replace(/\+/g, ' '));

}

function perfex_note_set_note_tab()
{
    if ( perfex_note_get_tab_is_note() == 'note' )
    {
        $('a[href="#tab_notes"]').tab('show');

    }
}
