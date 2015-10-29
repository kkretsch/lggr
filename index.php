<?php

spl_autoload_register(function($class) {
	include __DIR__ . '/inc/' . strtolower($class) . '_class.php';
});

$config = new Config();

define('TITLE', 'overview');
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
} catch(Exception $e) {
	echo '<div class="container"><div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div></div>';

	require 'tpl/foot.inc.php';

	exit;
}

$aRanges = array(
	'1' => 'This hour',
	'24' => 'Today',
	'168' => 'Week'
);

$page = $state->getPage();

try {
	if($state->isSearch()) {

		$aEvents = $l->getText($state->getSearch(), $state->getSearchProg(), $page*LggrState::PAGELEN, LggrState::PAGELEN);
		$searchvalue = htmlentities($state->getSearch(), ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES);
		$searchvalueprog = htmlentities($state->getSearchProg(), ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES);
		$isSearch=true;
		$sFilter = 'Text search for';
		if('' != $state->getSearch()) $sFilter .= ' message <strong>' . $searchvalue . '</strong>';
		if('' != $state->getSearchProg()) $sFilter .= ' program <strong>' . $searchvalueprog . '</strong>';

	} elseif($state->isHost() || $state->isLevel()) {

		$host = $state->getHost();
		$level = $state->getLevel();

		$aEvents = $l->getFiltered($host, $level, $page*LggrState::PAGELEN, LggrState::PAGELEN);
		$searchvalue='';
		$searchvalueprog='';
		$isSearch=false;
		$sFilter='';
		if($state->isHost())
			$sFilter .= 'Filter by server <strong>' . htmlentities($state->getHost(), ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES) . '</strong>';
		if($state->isLevel())
			$sFilter .= 'Filter by level <strong>' . htmlentities($state->getLevel(), ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES) . '</strong>';

	} elseif($state->isFromTo()) {

		$aEvents = $l->getFromTo($page*LggrState::PAGELEN, LggrState::PAGELEN);
		$sFilter = 'Filter by time range between <strong>' . htmlentities($state->getFrom(), ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES) . '</strong> and <strong>' . htmlentities($state->getTo(), ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES) . '</strong>';
		$searchvalue='';
		$searchvalueprog='';
		$isSearch=false;

	} else {

		$sFilter = null;

		$aEvents = $l->getLatest($page*LggrState::PAGELEN, LggrState::PAGELEN);
		$searchvalue='';
		$searchvalueprog='';
		$isSearch=false;

	} // if search
} catch(Exception $e) {
	echo '<div class="container"><div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div></div>';

	require 'tpl/foot.inc.php';

	exit;
}

if (version_compare(phpversion(), '5.4', '<')) {
	echo '<div class="container"><div class="alert alert-danger" role="alert">Your PHP version ' . phpversion() . ' might be too old, expecting at least 5.4</div></div>';
} // if

require 'tpl/nav.inc.php';
?>

    <div class="container" id="infoheader">

   <div id="accordion" class="panel-group">
    <div class="panel panel-default">
     <div class="panel-heading"><h4 class="panel-title"><span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true"></span><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Server status and filter ...</a></h4></div>
     <div id="collapseOne" class="panel-collapse collapse">
      <div class="panel-body">

      <div class="row">
        <div class="col-md-4">
          <h2 title="Levels of last up to <?= Lggr::LASTSTAT ?> entries"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> Levels</h2>
          <div class="progress">
<?php
$aLevelCount = array();
foreach($aLevels as $level) {
	$aLevelCount[$level->level] = $level->c;
	switch($level->level) {
	case 'emerg':
	case 'crit':
	case 'err':
		$label='progress-bar-danger';
		break;
	case 'warning':
		$label='progress-bar-warning';
		break;
	case 'notice':
		$label='progress-bar-primary';
		break;
	case 'info':
		$label='progress-bar-success';
		break;
	default: $label='';
	} // switch

	echo <<<EOL
<div class="progress-bar $label" style="width: {$level->f}%" title="{$level->level} {$level->f}%">
<span class="sr-only">{$level->f}%</span>
</div>
EOL;
} // foreach
?>
</div>
	<p>Distribution of selected event levels.</p>
	<p class="lggr-level-buttons">
<?php
if(isset($aLevelCount['emerg'])) {
	echo '<button class="btn btn-sm btn-danger" type="button">Emergency <span class="badge">' . $aLevelCount['emerg'] . '</span></button> ';
}
if(isset($aLevelCount['crit'])) {
	echo '<button class="btn btn-sm btn-danger" type="button">Critical <span class="badge">' . $aLevelCount['crit'] . '</span></button> ';
}
if(isset($aLevelCount['err'])) {
	echo '<button class="btn btn-sm btn-danger" type="button">Error <span class="badge">' . $aLevelCount['err'] . '</span></button> ';
}
if(isset($aLevelCount['err'])) {
	echo '<button class="btn btn-sm btn-warning" type="button">Warning <span class="badge">' . $aLevelCount['warning'] . '</span></button> ';
}
if(isset($aLevelCount['notice'])) {
	echo '<button class="btn btn-sm btn-primary" type="button">Notice <span class="badge">' . $aLevelCount['notice'] . '</span></button> ';
}
?>
	</p>
        </div>

        <div class="col-md-4">
          <h2 title="Reporting servers of last up to <?= Lggr::LASTSTAT ?> entries"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span> Servers</h2>
<?php
foreach($aServers as $server) {
	if($server->f < 5) continue;

        echo <<<EOL
<div class="progress">
	<div class="progress-bar" role="progressbar" aria-valuenow="{$server->f}" aria-valuemin="0" aria-valuemax="100" style="width: {$server->f}%; min-width: 3em" title="{$server->host} {$server->f}%">
{$server->host} {$server->f}%
	</div>
</div>
EOL;
} // foreach
?>
          <p>Most reporting servers (5% or more).</p>
        </div>

        <div class="col-md-4">
          <h2><span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filter</h2>
<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
    Server
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
<?php
$aServerList = array();
foreach($aServers as $server) {
	$aServerList[] = $server->host;
} // foreach
sort($aServerList);
foreach($aServerList as $server) {
	echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="./do.php?a=host&host=' . urlencode($server) . '">' . $server . '</a></li>';
} // foreach
?>
  </ul>
</div><!-- dropdown -->

<p><div class="btn-group btn-group-xs" role="group" aria-label="level">
<?php
foreach($aLevels as $level) {
	if($state->isLevel() && ($level->level == $state->getLevel())) {
		echo '<button type="button" class="btn btn-primary newlog-level">' . $level->level . '</button>';
	} else {
		echo '<button type="button" class="btn btn-default newlog-level">' . $level->level . '</button>';
	}
} // foreach
?>
</div></p>

<p><div class="btn-group" role="group" aria-label="range">
<?php
foreach($aRanges as $rangeValue => $rangeText) {
	if($state->getRange() == $rangeValue) {
		echo '<button type="button" class="btn btn-primary newlog-range" data-range="' . $rangeValue . '">' . $rangeText . '</button>';
	} else {
		echo '<button type="button" class="btn btn-default newlog-range" data-range="' . $rangeValue . '">' . $rangeText . '</button>';
	}
} // foreach

if($state->isFromTo()) {
	echo '<button type="button" class="btn btn-primary newlog-range" id="btnspecialrange">Special</button>';
} else {
	echo '<button type="button" class="btn btn-default newlog-range" id="btnspecialrange">Special</button>';
} // if

?>
</div></p>

<form action="do.php" method="post" id="tsfromto">
<input type="hidden" name="a" value="fromto">
<input type="text" name="tsfrom" id="tsfrom" class="tspick" placeholder="from">
<input type="text" name="tsto" id="tsto" class="tspick" placeholder="to">
<button type="submit" class="btn btn-default">filter</button>
</form>


<p><a type="button" role="button" href="./do.php?a=reset" class="btn btn-default">
  <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Reset
</a></p>
        </div>
      </div><!-- row -->

</div><!-- panel-body -->
</div><!-- collapseOne -->
</div><!-- panel -->
</div><!-- panel-group -->

    </div> <!-- /container -->

<div class="container">
<?php

if(null != $sFilter) {
	echo '<div class="alert alert-info" role="alert">' . $sFilter . '</div>';
} // if

if(0 == count($aEvents)) {
	echo '<div class="alert alert-danger" role="alert">empty result</div>';
} // if

?>
</div>

<!-- class container for fixed max width, or container-fluid for maximum width -->
<div class="container-fluid datablock">
<?php

if(!$isSearch)
	include 'tpl/paginate.inc.php';

$i=0;
foreach($aEvents as $event) {
	$i++;

	if(0 == $i % 2) {
		$rowclass='even';
	} else {
		$rowclass='odd';
	} // if

	switch($event->level) {
	case 'emerg': $label = '<span class="label label-danger">Emergency</span>'; break;
	case 'crit': $label = '<span class="label label-danger">Critical</span>'; break;
	case 'err': $label = '<span class="label label-danger">Error</span>'; break;
	case 'warning': $label = '<span class="label label-warning">Warning</span>'; break;
	case 'notice': $label='<span class="label label-primary">Notice</span>'; break;
	case 'info': $label = '<span class="label label-success">Info</span>'; break;
	default: $label = '<span class="label label-default">' . $event->level . '</span>';
	} // switch

	$host = htmlentities($event->host, ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES);
	$program = htmlentities($event->program, ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES);
	$msg = htmlentities($event->message, ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES);

	echo <<<EOL
<div class="row datarow $rowclass" data-id="{$event->id}">
	<div class="col-md-2 col-xs-6 newlog-date">{$event->date}</div>
	<div class="col-md-1 col-xs-2">{$event->facility}</div>
	<div class="col-md-1 col-xs-2">$label</div>
	<div class="col-md-1 col-xs-2">$host</div>
	<div class="col-md-2 col-xs-12">$program</div>
	<div class="col-md-5 col-xs-12 newlog-msg" title="$msg"><tt>{$msg}</tt></div>
</div><!-- row -->
EOL;

} // foreach
?>
<div id="dialog" title="Details">I'm a dialog</div>

<?php
if(!$isSearch)
	include 'tpl/paginate.inc.php';
?>

</div>

<?php
$aPerf = $l->getPerf();
require 'tpl/foot.inc.php'
?>
