/* */

$(document).ready(function() {

function htmlEntities(str) {
	return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

Date.prototype.timeNow = function () {
	return ((this.getHours() < 10)?"0":"") + this.getHours() +":"+ ((this.getMinutes() < 10)?"0":"") + this.getMinutes() +":"+ ((this.getSeconds() < 10)?"0":"") + this.getSeconds();
}

function loadLatest() {
	var lastid = $('div.datablock div.datarow:first-child').attr('data-id');
	$.ajax({
		url: 'api.php',
		type: 'GET',
		data: 'a=latest&id='+lastid,
		dataType: 'json',
		success: function(data) {
			var iEvents = data.length;
			var sTmp = '';
			for(var i=0; i<iEvents; i++) {
				var oEvent = data[i];

				var label='';
				switch(oEvent.level) {
					case 'emerg': label = '<span class="label label-danger">Emergency</span>'; break;
					case 'crit': label = '<span class="label label-danger">Critical</span>'; break;
					case 'err': label = '<span class="label label-danger">Error</span>'; break;
					case 'warning': label = '<span class="label label-warning">Warning</span>'; break;
					case 'notice': label='<span class="label label-primary">Notice</span>'; break;
					case 'info': label = '<span class="label label-success">Info</span>'; break;
					default: label = '<span class="label label-default">' + oEvent.level + '</span>';
				} // switch

				var host = htmlEntities(oEvent.host);
				var program = htmlEntities(oEvent.program);
				var msg = htmlEntities(oEvent.message);

				sTmp += '<div class="row datarow" data-id="' + oEvent.id + '">';
				sTmp += '<div class="col-md-2 col-xs-6 newlog-date">' + oEvent.date + '</div>';
				sTmp += '<div class="col-md-1 col-xs-2">' + oEvent.facility + '</div>';
				sTmp += '<div class="col-md-1 col-xs-2">' + label + '</div>';
				sTmp += '<div class="col-md-1 col-xs-2">' + host + '</div>';
				sTmp += '<div class="col-md-2 col-xs-12">' + program + '</div>';
				sTmp += '<div class="col-md-5 col-xs-12 newlog-msg" title="' + msg + '"><tt>' + msg + '</tt></div>';
				sTmp += '</div>\n';
			} // for

			if(iEvents > 0) {
				$('div.datablock').children().slice(-iEvents).remove();
				$('div.datablock').prepend(sTmp);
			} // if

			var ts = new Date();
			$('#tslatest').text(ts.timeNow());

			setTimeout(loadLatest, 5000);
		},
		error: function(e) {
			console.log(e.message);
			alert('Ajax error ' + e.message);
		}
	});
} // function


	setTimeout(loadLatest, 5000);

});
