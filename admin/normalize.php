<?php

spl_autoload_register(function($class) {
	include __DIR__ . '/../inc/' . strtolower($class) . '_class.php';
});

$iCount=0;
$a=array();
$l = null;
try {
	$config = new AdminConfig();

	$state = new LggrState();
	$state->setLocalCall(true);

	$l = new Lggr($state, $config);

	$l->normalizeHosts();

	$a = $l->getPerf();
} catch(Exception $e) {
	die($e->getMessage());
} // try

