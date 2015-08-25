<?php

spl_autoload_register(function($class) {
	include 'inc/' . strtolower($class) . '_class.php';
});

session_start();

if(!isset($_REQUEST['a'])) {
	header('Location: index.php');
	exit;
} // if

if(isset($_SESSION[LggrState::SESSIONNAME])) {
	$state = $_SESSION[LggrState::SESSIONNAME];
} else {
	$state = new LggrState();
} // if


switch($_REQUEST['a']) {

	case 'reset':
		$state = new LggrState();
		break;

	case 'search':
		$state = new LggrState();
		if('' != trim($_POST['prog'])) {
			$state->setSearchProg(trim($_POST['prog']));
		}
		if('' != trim($_POST['q'])) {
			$state->setSearch(trim($_POST['q']));
		} // if
		break;

	case 'host':
		$state->setHost($_GET['host']);
		$state->setPage(0);
		$state->setResultSize(0);
		break;

	case 'level':
		$state->setLevel($_GET['level']);
		$state->setPage(0);
		$state->setResultSize(0);
		break;

	case 'range':
		$i = intval($_GET['range']);
		$state->setRange($i);
		$state->setPage(0);
		$state->setResultSize(0);
		$state->setFromTo(null,null);
		break;

	case 'fromto':
		$sFrom = $_POST['tsfrom'];
		$sTo   = $_POST['tsto'];
		$state->setFromTo($sFrom, $sTo);
		$state->setRange(null);
		break;
	
	case 'paginate':
		if(isset($_GET['page'])) {
			$i = intval($_GET['page']);
			if($i<0) $i=0;

			$page = $i;
			$state->setPage($page);
		} // if
		break;

} // switch

$_SESSION[LggrState::SESSIONNAME] = $state;

header('Location: index.php');
