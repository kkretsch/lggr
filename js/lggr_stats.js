/* */

$(document).ready(function() {

Chart.defaults.global.responsive = true;
var options = {
	animateRotate: true
};


var ctx = document.getElementById("chartMsgsPerHour").getContext("2d");
var chartMsgsPerHour = new Chart(ctx).Bar(dataMsgsPerHour, options);

ctx = document.getElementById("chartServers").getContext("2d");
var chartServers = new Chart(ctx).Bar(dataServers, options);

ctx = document.getElementById("chartLevels").getContext("2d");
var chartLevels = new Chart(ctx).PolarArea(dataLevels, options);

});
