<?php

spl_autoload_register(function($class) {
	include 'inc/' . strtolower($class) . '_class.php';
});

$searchvalue='';

require 'tpl/head.inc.php';

session_start();

if(isset($_SESSION[LggrState::SESSIONNAME])) {
	$state = $_SESSION[LggrState::SESSIONNAME];
} else {
	$state = new LggrState();
} // if

$aColors=array(
	'emerg' =>	'#d9534f',
	'crit' =>	'#d9534f',
	'err' =>	'#d9534f',
	'warning' =>	'#f0ad4e',
	'notice' =>	'#337ab7',
	'info' =>	'#5cb85c'
);

$l = null;
try {
	$config = new Config();
	$l = new Lggr($state, $config);

	$aLevels = $l->getLevels();
	$aServers = $l->getServers();

	$aMsgPerHour = $l->getMessagesPerHour();
} catch(Exception $e) {
	echo '<div class="container"><div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div></div>';

	require 'tpl/foot.inc.php';

	exit;
}

require 'tpl/nav.inc.php';
?>

<div class="container">
  <div class="row">
    <div class="col-md-6"><h2>Messages per hour</h2><canvas id="chartMsgsPerHour"></canvas></div>
    <div class="col-md-6"><h2>Servers</h2><canvas id="chartServers"></canvas></div>
  </div>
  <div class="row">
    <div class="col-md-4"><h2>Message levels distribution</h2><canvas id="chartLevels"></canvas></div>
    <div class="col-md-4"><canvas id="myChart2"></canvas></div>
    <div class="col-md-4"><canvas id="myChart3"></canvas></div>
  </div>
</div><!-- container -->

<script>
<!-- dynamic data -->

<?php
$aTmp = array();
foreach($aMsgPerHour as $hour) {
	$aTmp[$hour->h] = $hour->c;
} // foreach
?>
var dataMsgsPerHour = {
	labels: ["<?= implode('","', array_keys($aTmp)) ?>"],
	datasets: [ {
		label: "Msgs per hour",
		fillColor: "rgba(220,220,220,0.5)",
		data: [<?= implode(',', array_values($aTmp)) ?>],
	} ]
};

<?php
$aTmp = array();
foreach($aServers as $server) {
	$aTmp[$server->host] = $server->c;
} // foreach
?>
var dataServers = {
	labels: ["<?= implode('","', array_keys($aTmp)) ?>"],
	datasets: [ {
		label: "Hostname",
		fillColor: "rgba(151,187,205,0.5)",
		data: [<?= implode(',', array_values($aTmp)) ?>],
	} ]
};

var dataLevels = [
<?php
foreach($aLevels as $level) {
	$newVal = round(log($level->c));
	$newCol = $aColors[$level->level];
	echo <<<EOL
	{
		value: $newVal,
		color: "$newCol",
		label: "{$level->level}",
	},

EOL;
} // foreach
?>
];

</script>

<?php
$aPerf = $l->getPerf();
require 'tpl/foot.inc.php'
?>
