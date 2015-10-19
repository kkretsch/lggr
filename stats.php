<?php

spl_autoload_register(function($class) {
	include 'inc/' . strtolower($class) . '_class.php';
});

$config = new Config();

$searchvalue='';

define('TITLE', 'statistics');
require 'tpl/head.inc.php';

session_start();

if(isset($_SESSION[LggrState::SESSIONNAME])) {
	$state = $_SESSION[LggrState::SESSIONNAME];
} else {
	$state = new LggrState();
} // if


$l = null;
try {
	$l = new Lggr($state, $config);

	$aLevels = $l->getLevels();
	$aServers = $l->getServers();

	$aStatistic = $l->getStatistic();
	$aStatistic = $aStatistic[0];

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
    <div class="col-md-6"><h2><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Messages per hour</h2><canvas id="chartMsgsPerHour"></canvas></div>
    <div class="col-md-6"><h2><span class="glyphicon glyphicon-cd" aria-hidden="true"></span> Servers</h2><canvas id="chartServers"></canvas></div>
  </div>
  <div class="row">
    <div class="col-md-4"><h2><span class="glyphicon glyphicon-signal" aria-hidden="true"></span> Message levels relative distribution</h2><canvas id="chartLevels"></canvas></div>
    <div class="col-md-4"><h2><span class="glyphicon glyphicon-cd" aria-hidden="true"></span> Messages by server</h2><canvas id="chartServersPie"></canvas></div>
    <div class="col-md-4">
      <h2><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Database</h2>
      <p>Events in DB: <?= number_format($aStatistic->cnt) ?><br>Oldest entry: <?= $aStatistic->oldest ?></p>
    </div>
  </div>
</div><!-- container -->

<?php
$aPerf = $l->getPerf();
require 'tpl/foot.inc.php'
?>
