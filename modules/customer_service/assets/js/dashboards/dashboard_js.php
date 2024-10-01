<script type="text/javascript">

	report_by_ticket_on_hold_closed('report_by_ticket_on_hold_closed', '', '');
	report_by_ticket_status();
	report_by_ticket_category('report_by_ticket_category','', '');

	function report_by_ticket_on_hold_closed(id, value, title_c){
		'use strict';

		var months_report = $('select[name="mo_months-report"]').val(); 
		var report_from = $('input[name="mo_report-from"]').val();
		var report_to = $('input[name="mo_report-to"]').val();

		requestGetJSON('customer_service/report_by_ticket_on_hold_closed?months_report='+months_report+'&report_from='+report_from+'&report_to='+report_to).done(function (response) {

			/*get data for hightchart*/

			Highcharts.setOptions({
				chart: {
					style: {
						fontFamily: 'inherit !important',
						fill: 'black'
					}
				},
				colors: [ '#119EFA','#15f34f','#ef370dc7','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
			});
			Highcharts.chart(id, {
				chart: {
					type: 'column'
				},
				title: {
					text: '<?php echo _l('cs_closed_on_hold_tikets_by_month'); ?>'
				},
				credits: {
					enabled: false
				},
				xAxis: {
					categories: response.categories,
					crosshair: true
				},
				yAxis: {
					min: 0,
					title: {
						text: '<?php echo _l('cs_qty'); ?>'
					}
				},
				tooltip: {
					headerFormat: '<span class="font-size-10">{point.key}</span><table>',
					pointFormat: '<tr><td class="padding-0" style="color:{series.color}">{series.name}: </td>' +
					'<td class="padding-0"><b>{point.y:.1f}</b> <?php echo _l('cs_qty'); ?></td></tr>',
					footerFormat: '</table>',
					shared: true,
					useHTML: true
				},
				plotOptions: {
					column: {
						pointPadding: 0.2,
						borderWidth: 0
					}
				},
				series: [{
					name: '<?php echo _l('cs_on_hold'); ?>',
					data: response.on_hold

				},{
					name: '<?php echo _l('cs_closed'); ?>',
					data: response.closed

				}]
			});


		});
	}

	function ticket_total_hours(){
		'use strict';

		var months_report = $('select[name="mo_months-report"]').val(); 
		var report_from = $('input[name="mo_report-from"]').val();
		var report_to = $('input[name="mo_report-to"]').val();

		requestGetJSON('customer_service/ticket_total_hours?months_report='+months_report+'&report_from='+report_from+'&report_to='+report_to).done(function (response) {

			$('.ticket_total_hours').html('');
			$('.ticket_total_hours').append(response.total_hours);
			$('.ticket_avg_resolution_time').html('');
			$('.ticket_avg_resolution_time').append(response.avg_resolution_time);

		});
	}

	function report_by_ticket_status(){
		'use strict';
		var months_report = $('select[name="mo_months-report"]').val(); 
		var report_from = $('input[name="mo_report-from"]').val();
		var report_to = $('input[name="mo_report-to"]').val();

		requestGetJSON('customer_service/report_by_ticket_status?months_report='+months_report+'&report_from='+report_from+'&report_to='+report_to).done(function (response) {
			
			Highcharts.chart('report_by_ticket_status', {
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false,
					type: 'pie'
				},
				credits: {
			 		enabled: false
			 	},
				title: {
					text: '<?php echo _l("cs_report_by_ticket_status")?> '
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				accessibility: {
					point: {
						valueSuffix: '%'
					}
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: true
						},
						showInLegend: true
					}
				},
				series: [{
					name: '<?php echo _l('ratio'); ?>',
					colorByPoint: true,
					data: response.data_result
				}]
			});

		});
	}


	function report_by_ticket_category(id, value, title_c){
		"use strict"; 

		var months_report = $('select[name="mo_months-report"]').val(); 
		var report_from = $('input[name="mo_report-from"]').val();
		var report_to = $('input[name="mo_report-to"]').val();

		requestGetJSON('customer_service/report_by_ticket_category?months_report='+months_report+'&report_from='+report_from+'&report_to='+report_to).done(function (response) {

			Highcharts.setOptions({
				chart: {
					style: {
						fontFamily: 'inherit !important',
						fontWeight:'normal',
						fill: 'black'
					}
				},
				colors: [ '#119EFA','#ef370dc7','#15f34f','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
			});

			Highcharts.chart(id, {
				chart: {
					backgroundcolor: '#fcfcfc8a',
					type: 'column'
				},
				accessibility: {
					description: null
				},
				title: {
					text: '<?php echo _l('cs_report_by_ticket_category'); ?>'
				},
				credits: {
					enabled: false
				},
				tooltip: {
					pointFormat: '<span style="color:{series.color}">'+<?php echo json_encode(_l('invoice_table_quantity_heading')); ?>+'</span>: <b>{point.y}</b> <br/>',
					shared: true
				},
				legend: {
					enabled: true
				},
				xAxis: {
					categories: response.categories,
					crosshair: true
				},
				yAxis: {
					title: {
						text: ''
					}

				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						depth: 35,
						dataLabels: {
							enabled: true,
							format: '{point.name}'
						}        
					}
				},
				series: [{
					name: '<?php echo _l('cs_categories'); ?>',
					data: response.data_result 

				}]
			});
		});
	}

	var mo_report_from = $('input[name="mo_report-from"]');
	var mo_report_to = $('input[name="mo_report-to"]');
	var mo_date_range = $('#mo_date-range');

	$('select[name="mo_months-report"]').on('change', function() {
		'use strict';

		var val = $(this).val();
		mo_report_to.attr('disabled', true);
		mo_report_to.val('');
		mo_report_from.val('');
		if (val == 'custom') {
			mo_date_range.addClass('fadeIn').removeClass('hide');
			return;
		} else {
			if (!mo_date_range.hasClass('hide')) {
				mo_date_range.removeClass('fadeIn').addClass('hide');
			}
		}
		 ticket_closed_on_hold_gen_reports();
		 ticket_total_hours();
		 ticket_category_gen_reports();
		 report_by_ticket_status();
	});

	mo_report_from.on('change', function() {
		'use strict';

		var val = $(this).val();
		var report_to_val = mo_report_to.val();
		if (val != '') {
			mo_report_to.attr('disabled', false);
			if (report_to_val != '') {
				 ticket_closed_on_hold_gen_reports();
				 ticket_total_hours();
				 ticket_category_gen_reports();
				 report_by_ticket_status();
				 
			}
		} else {
			mo_report_to.attr('disabled', true);
		}
	});

	mo_report_to.on('change', function() {
		'use strict';

		var val = $(this).val();
		if (val != '') {
			 ticket_closed_on_hold_gen_reports();
			 ticket_total_hours();
			 ticket_category_gen_reports();
			 report_by_ticket_status();

		}
	});

	function  ticket_closed_on_hold_gen_reports() {
		'use strict';
		report_by_ticket_on_hold_closed('report_by_ticket_on_hold_closed', '', '');
	}

	function  ticket_category_gen_reports() {
		'use strict';
		report_by_ticket_category('report_by_ticket_category','', '');
	}
	

</script>