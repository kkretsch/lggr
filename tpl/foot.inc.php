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
        <p class="debugfooter"><?= $pCount ?> queries in <?= $pTime ?> seconds. Session: <?= $dbgsession ?> by <?= htmlentities($_SERVER['REMOTE_USER']) ?></p>
        <p>&copy; <a href="http://lggr.io" target="_blank">lggr.io</a> 2015</p>
      </footer>
    </div> <!-- /container -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<?php
switch(basename($_SERVER['SCRIPT_NAME'], '.php')) {
case 'stats':
	$ts = time();
	echo <<<EOM
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
    <script src="js/lggr_stat_data.php?{$ts}"></script>
    <script src="js/lggr_stats.js"></script>
EOM;
	break;

case 'live':
	echo <<<EOM
    <script src="js/lggr_live.js"></script>
EOM;
	break;
} // switch

?>
    <script src="js/lggr.js"></script>
  </body>
</html>
