    <div class="container">
      <hr>
      <footer>
<?php
$pCount = count($aPerf);
$pTime  = 0;
foreach($aPerf as $perf) {
	$aTmp = $perf->getPerf();

	$pTime += $aTmp['time'];
} // foreach

$pTime = round($pTime, 4);

if(isset($_COOKIE['PHPSESSID'])) {
	$dbgsession = $_COOKIE['PHPSESSID'];
} else {
	$dbgsession = '-';
} // if

?>
        <p class="debugfooter"><?= $pCount ?> <?= _('queries in') ?> <?= $pTime ?> <?= _('seconds') ?>. <?= _('Session') ?>: <?= $dbgsession ?> <?= _('by') ?> <?= htmlentities($_SERVER['REMOTE_USER']) ?></p>
        <p>&copy; <a href="http://lggr.io" target="_blank">lggr.io</a> 2016</p>
      </footer>
    </div> <!-- /container -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?= $config->getUrlJquery() ?>jquery-1.11.3.min.js"></script>
    <script src="<?= $config->getUrlJqueryui() ?>jquery-ui.min.js"></script>
    <script src="<?= $config->getUrlJAtimepicker() ?>jquery-ui-timepicker-addon.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?= $config->getUrlBootstrap() ?>js/bootstrap.min.js"></script>
<?php
switch(basename($_SERVER['SCRIPT_NAME'], '.php')) {
case 'stats':
	$urlchartjs = $config->getUrlChartjs();
	$urljqcloud = $config->getUrlJQCloud();
	$ts = time();
	echo <<<EOM
    <script src="{$urlchartjs}Chart.min.js"></script>
    <script src="{$urljqcloud}jqcloud.min.js"></script>
    <script src="js/lggr_stat_data.php?{$ts}"></script>
    <script src="js/lggr_stats.js"></script>
EOM;
	break;

case 'live':
	echo <<<EOM
    <script src="js/lggr_live.js"></script>
EOM;
	break;

default:
    // ignore
    break;
} // switch

?>
    <script src="js/lggr.js"></script>
  </body>
</html>
