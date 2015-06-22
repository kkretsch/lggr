<?php

spl_autoload_register(function($class) {
	include '../inc/' . strtolower($class) . '_class.php';
});

$iCount=0;
$a=array();
$l = null;
try {
	$config = new AdminConfig();

	$state = new LggrState();
	$state->setLocalCall(true);

	$l = new Lggr($state, $config);

	$iCount = $l->purgeOldMessages();
	$a = $l->getPerf();
} catch(Exception $e) {
	die($e->getMessage());
} // try

?>
Purging <?= $iCount ?> old messages with <?= $a['count'] ?> query in <?= $a['time'] ?> seconds.

