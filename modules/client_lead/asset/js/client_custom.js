$(function() {
    var urlSegments = window.location.pathname.split('/');
    var userid = urlSegments[urlSegments.length - 1];

    var tAPI = initDataTable('.table-leads_client_tab', admin_url + 'client_lead/leads/lead_table_tab/' + userid, [0], [0], {}, '');

    appValidateForm($('#lead_client_form'), {
        inquiry_about: 'required'
    });

    var selectedOption = $('#existing_client_id').find('option:selected').text();
    $('#company').val(selectedOption);

    $(document).on('change', '#existing_client_id', function() {
        var selectedOption = $(this).find('option:selected').text();
        $('#company').val(selectedOption);
    });

    function updateTasksRelatedFilter() {
        var tasks_related_values = [];
        $('#tasks_related_filter :checkbox:checked').each(function(i) {
            tasks_related_values[i] = $(this).val();
        });
        $('input[name="tasks_related_to"]').val(tasks_related_values.join());
    }
    $("input[name='tasks_related_to[]']").on('change', function() {
        updateTasksRelatedFilter();
    });
    function reloadTasksWithoutOnChange() {
        updateTasksRelatedFilter();
    }
    reloadTasksWithoutOnChange();

});
