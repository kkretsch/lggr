<?php

require 'inc/lggr_class.php';
require 'inc/lggrstate_class.php';

session_start();

if(!isset($_GET['a'])) {
	header('Location: index.php');
	exit;
} // if

if(isset($_SESSION[LggrState::SESSIONNAME])) {
	$state = $_SESSION[LggrState::SESSIONNAME];
} else {
	$state = new LggrState();
} // if


switch($_GET['a']) {

	case 'reset':
		$state = new LggrState();
		break;

	case 'search':
		$state = new LggrState();
		$state->setSearch($_POST['q']);
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
