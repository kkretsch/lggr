/* */

$(document).ready(function() {

$("#dialog").dialog({
	autoOpen: false,
	width: 500
});

$('div.datablock').on('click', 'tt', function() {
	var sTxt = $(this).html();
	var sTitle = $(this).parent().parent().find('.newlog-date').text();

	$('#dialog').html(sTxt).
		dialog("option", "title", sTitle).
		dialog("open");
});

$('button.newlog-level').on('click', function() {
	var sLevel = $(this).text();
	window.location.href = "./do.php?a=level&level=" + sLevel;
});

$('button#btnspecialrange').on('click', function(e) {
	e.stopImmediatePropagation();
	$(this).parent().find('.btn').removeClass('btn-primary').addClass('btn-default');
	$(this).addClass('btn-primary');
	$('#tsfromto').show('fast');
	return false;
});
$('button.newlog-range').on('click', function() {
	var iRange = $(this).attr('data-range');
	window.location.href = "./do.php?a=range&range=" + iRange;
});

$('#tsfrom, #tsto').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'HH:mm:00'
});

$("body").keydown(function(e) {
	// only if not focus inside input text fields
	var oFocused = $(document.activeElement);
	var sFocused = oFocused.attr('id');
	if(('prog' == sFocused) || ('q' == sFocused)) return;

	var oLink = null;
	if(e.which == 37) { // left
		oLink = $('div.datablock nav:first-child ul.pagination a.pageleft');
	} else if(e.which == 39) { // right
		oLink = $('div.datablock nav:first-child ul.pagination a.pageright');
	} // if

	if(null != oLink) {
		var s = oLink.attr('href');
		location.href = s;
	} // if
});

});
