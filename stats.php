<?php

require 'inc/pre.inc.php';

$searchvalue='';

define('TITLE', 'statistics');
require 'tpl/head.inc.php';

$l = null;
try {
	$l = new Lggr($state, $config);

	$aLevels = $l->getLevels();
	$aServers = $l->getServers();

	$aStatistic = $l->getStatistic();
	$aStatistic = $aStatistic[0];

	$aArchivedStatistic = $l->getArchivedStatistic();
	$aArchivedStatistic = $aArchivedStatistic[0];

	$aMsgPerHour = $l->getMessagesPerHour();
} catch(Exception $e) {
	echo '<div class="container"><div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div></div>';

	require 'tpl/foot.inc.php';

	exit;
}

require 'tpl/nav.inc.php';
?>

<div id="statsheader" class="container">
  <div class="row">
    <div class="col-md-6"><h2><span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?= _('Messages per hour') ?></h2><canvas id="chartMsgsPerHour"></canvas></div>
    <div class="col-md-6"><h2><span class="glyphicon glyphicon-cd" aria-hidden="true"></span> <?= _('Servers') ?></h2><canvas id="chartServers"></canvas></div>
  </div>
  <div class="row">
    <div class="col-md-4"><h2><span class="glyphicon glyphicon-signal" aria-hidden="true"></span> <?= _('Message levels relative distribution') ?></h2><canvas id="chartLevels"></canvas></div>
    <div class="col-md-4"><h2><span class="glyphicon glyphicon-cd" aria-hidden="true"></span> <?= _('Messages by server') ?></h2><canvas id="chartServersPie"></canvas></div>
    <div class="col-md-4">
      <h2><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> <?= _('Database') ?></h2>
      <p><?= _('Events in DB') ?>: <?= number_format($aStatistic->cnt) ?><br><?= _('Oldest entry') ?>: <?= $aStatistic->oldest ?></p>
      <p><?= _('Archived') ?>: <?= number_format($aArchivedStatistic->cnt) ?></p>
    </div>
  </div>
</div><!-- container -->

<?php
$aPerf = $l->getPerf();
require 'tpl/foot.inc.php'
?>
