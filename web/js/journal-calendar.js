jQuery(function() {
	var calendar = jQuery('#journal_calendar');
	var existing = {};
	var minDate, maxDate;
	function append(year, month, day, link) {
		minDate = new Date(year, month-1, day);
		!maxDate && (maxDate = minDate);
		if(!existing[year]) {
			existing[year] = {};
		}
		if(!existing[year][month]) {
			existing[year][month] = {};
		}
		existing[year][month][day] = link;
	}
	calendar.find('li a.calendar_day').each(function() {
		var day = jQuery(this);
		append(day.data('year'), day.data('month'), day.data('day'), day.attr('href'));
	});
	calendar.empty();
	calendar.datepicker({
		changeMonth: true,
		changeYear: true,
		minDate: minDate,
		maxDate: maxDate,
		beforeShowDay: function(date) {
			var enabled = !!existing[date.getFullYear()] && existing[date.getFullYear()][date.getMonth()+1] && existing[date.getFullYear()][date.getMonth()+1][date.getDate()];
			return [enabled];
		},
		onSelect: function(date, instance) {
			date = calendar.datepicker('getDate');
			var link = existing[date.getFullYear()] && existing[date.getFullYear()][date.getMonth()+1] && existing[date.getFullYear()][date.getMonth()+1][date.getDate()];
			location.href = link;
		}
	});
});