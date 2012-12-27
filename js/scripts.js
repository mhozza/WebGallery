$(function() {
    $('.datepicker').datepicker({ dateFormat: "yy/mm/dd" });
    $('.datetimepicker').datetimepicker({
	addSliderAccess: true,
	sliderAccessArgs: { touchonly: false },
	dateFormat: "yy/mm/dd",
	timeFormat: "HH:mm"});
	filterModules()
});

