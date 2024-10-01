(function($) {
"use strict";

$('#report_months').on('change', function() {
	 var val = $(this).val();
	 var report_from = $('#report_from');
	 var report_to = $('#report_to');
	 var date_range = $('#date-range');
	 
	 report_to.val('');
	 report_from.val('');
	 if (val == 'custom') {
		date_range.addClass('fadeIn').removeClass('hide');
		return;
	} else {
		if (!date_range.hasClass('hide')) {
			date_range.removeClass('fadeIn').addClass('hide');
		}
		$('.table-si-leads').DataTable().ajax.reload();
	}
	if(val!='')
		$("#date_by_wrapper").removeClass('hide');
	else
		$("#date_by_wrapper").addClass('hide');	
});
$('input[name="report_from"]').on('change',function(){
	if($('input[name="report_to"]').val() !=''){
		$('.table-si-leads').DataTable().ajax.reload();
		return false;
	}	
});
$('#si_lf_save_filter').on('click',function(){
	var checked = this.checked;
	$('#si_lf_filter_name').attr('disabled',!checked);
});
$('#si_form_lead_filter').on('submit',function(){
	if($('#si_lf_save_filter').is(":checked") && $('#si_lf_filter_name').val()=='')
	{
		$('#si_lf_filter_name').focus();
		return false;
	}
});
$(document).ready(function() {
	var table = $('.dt-table').DataTable();
	var hide_view = [];
	$('.dt-table thead tr th').each(function(i,a) { 
		if( $(this).hasClass('not-export'))
			hide_view.push($(this).index());	
	});
	table.button().add( 0, 'colvis' );
	table.columns( hide_view ).visible( false );
	$('.buttons-colvis').addClass('btn-sm');//for Perfex version 3.0
});
$(".buttons-colvis").text("Columns");
})(jQuery);	

//added for kanban view
si_leads_kanban();

if ($('body').hasClass('kan-ban-body') && $('body').hasClass('si_lead_filters')) {
	$('#si_form_lead_filter select,#si_form_lead_filter #report_to').on('change',function(){
		si_leads_kanban();
	});
	
}

//init lead kanban
function si_leads_kanban()
{
	init_kanban_si_leads("si_lead_filters/kanban",si_leads_kanban_update,".leads-status",290,360,init_leads_status_sortable);
}

function init_kanban_si_leads(url, callbackUpdate, connect_with, column_px, container_px, callback_after_load) 
{
    if ($('#kan-ban').length === 0) {
        return;
    }
    delay(function () {
       $("body").append('<div class="dt-loader"></div>');
	   $.post(url, $('#si_form_lead_filter').serialize()).done(function (response) {
			$('#kan-ban').html(response);
            fix_kanban_height(column_px, container_px);
            var scrollingSensitivity = 20,
                scrollingSpeed = 60;

            if (typeof (callback_after_load) != 'undefined') {
                callback_after_load();
            }

            $(".status").sortable({
                connectWith: connect_with,
                helper: 'clone',
                appendTo: '#kan-ban',
                placeholder: "ui-state-highlight-card",
                revert: 'invalid',
                scrollingSensitivity: 50,
                scrollingSpeed: 70,
                sort: function (event, uiHash) {
                    var scrollContainer = uiHash.placeholder[0].parentNode;
                    // Get the scrolling parent container
                    scrollContainer = $(scrollContainer).parents('.kan-ban-content-wrapper')[0];
                    var overflowOffset = $(scrollContainer).offset();
                    if ((overflowOffset.top + scrollContainer.offsetHeight) - event.pageY < scrollingSensitivity) {
                        scrollContainer.scrollTop = scrollContainer.scrollTop + scrollingSpeed;
                    } else if (event.pageY - overflowOffset.top < scrollingSensitivity) {
                        scrollContainer.scrollTop = scrollContainer.scrollTop - scrollingSpeed;
                    }
                    if ((overflowOffset.left + scrollContainer.offsetWidth) - event.pageX < scrollingSensitivity) {
                        scrollContainer.scrollLeft = scrollContainer.scrollLeft + scrollingSpeed;
                    } else if (event.pageX - overflowOffset.left < scrollingSensitivity) {
                        scrollContainer.scrollLeft = scrollContainer.scrollLeft - scrollingSpeed;

                    }
                },
                change: function () {
                    var list = $(this).closest('ul');
                    var KanbanLoadMore = $(list).find('.kanban-load-more');
                    $(list).append($(KanbanLoadMore).detach());
                },
                start: function (event, ui) {
                    $('body').css('overflow', 'hidden');

                    $(ui.helper).addClass('tilt');
                    $(ui.helper).find('.panel-body').css('background', '#fbfbfb');
                    // Start monitoring tilt direction
                    tilt_direction($(ui.helper));
                },
                stop: function (event, ui) {
                    $('body').removeAttr('style');
                    $(ui.helper).removeClass("tilt");
                    // Unbind temporary handlers and excess data
                    $("html").off('mousemove', $(ui.helper).data("move_handler"));
                    $(ui.helper).removeData("move_handler");
                },
                update: function (event, ui) {
                    callbackUpdate(ui, this);
                }
            });

            $('.status').sortable({
                cancel: '.not-sortable'
            });

        });

    }, 200);
}

// Update lead when action is performed from leads kan ban eq order or status change
function si_leads_kanban_update(ui, object) {
	if (object !== ui.item.parent()[0]) {
	  return;
	}
  
	var data = {
	  status: $(ui.item.parent()[0]).attr("data-lead-status-id"),
	  leadid: $(ui.item).attr("data-lead-id"),
	  order: [],
	};
  
	$.each($(ui.item).parents(".leads-status").find("li"), function (idx, el) {
	  var id = $(el).attr("data-lead-id");
	  if (id) {
		data.order.push([id, idx + 1]);
	  }
	});
  
	setTimeout(function () {
		//lead_mark_as(data.status, data.leadid);
	  $.post(admin_url + "leads/update_lead_status", data).done(function (
		response
	  ) {
		update_kan_ban_total_when_moving(ui, data.status);
		si_leads_kanban();
	  });
	}, 200);
}

// Kan ban leads sorting
function si_leads_kanban_sort(type) {
    kan_ban_sort(type, si_leads_kanban);
}

// Kanban load more
function si_leads_kanban_load_more(status_id, e, url, column_px, container_px) {
    var LoadMoreParameters = {};
    //var search = $('input[name="search"]').val();
    var _kanban_param_val;
    var page = $(e).attr('data-page');
    var total_pages = $('[data-col-status-id="' + status_id + '"]').data('total-pages');
    if (page <= total_pages) {

        var sort_type = $('input[name="sort_type"]');
        var sort = $('input[name="sort"]').val();
        if (sort_type.length != 0 && sort_type.val() !== '') {
            LoadMoreParameters['sort_type'] = sort_type.val();
            LoadMoreParameters['sort'] = sort;
        }

        /* if (typeof(search) != 'undefined' && search !== '') {
            LoadMoreParameters['search'] = search;
        } */

        $.each($('._hidden_inputs._filters input,._hidden_inputs._filters select'), function() {
            if ($(this).attr('type') == 'checkbox') {
                _kanban_param_val = $(this).prop('checked') === true ? $(this).val() : '';
            } else {
                _kanban_param_val = $(this).val();
            }
            if (_kanban_param_val !== '') {
                LoadMoreParameters[$(this).attr('name')] = _kanban_param_val;
            }
        });

        LoadMoreParameters['status'] = status_id;
        LoadMoreParameters['page'] = page;
        LoadMoreParameters['page']++;
		console.log(LoadMoreParameters);	
		$.post(admin_url + url, LoadMoreParameters).done(function (response) {
        //requestGet(buildUrl(admin_url + url, LoadMoreParameters)).done(function(response) {
            page++;
            $('[data-load-status="' + status_id + '"]').before(response);
            $(e).attr('data-page', page);
            fix_kanban_height(column_px, container_px);
        }).fail(function(error) {
            alert_float('danger', error.responseText);
        });
        if (page >= total_pages - 1) {
            $(e).addClass("disabled");
        }
    }
}
//end kanban view