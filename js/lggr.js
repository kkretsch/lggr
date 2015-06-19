/* */

$(document).ready(function() {

$("#dialog").dialog({
	autoOpen: false,
	width: 500
});

$('div.datarow tt').on('click', function() {
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

$('button.newlog-range').on('click', function() {
	var iRange = $(this).attr('data-range');
	window.location.href = "./do.php?a=range&range=" + iRange;
});

});
