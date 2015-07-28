<?php

spl_autoload_register(function($class) {
	include __DIR__ . '/inc/' . strtolower($class) . '_class.php';
});

define('TITLE', 'live');
require 'tpl/head.inc.php';

session_start();

if(isset($_SESSION[LggrState::SESSIONNAME])) {
	$state = $_SESSION[LggrState::SESSIONNAME];
} else {
	$state = new LggrState();
} // if

$l = null;
try {
	$config = new Config();
	$l = new Lggr($state, $config);

	$aEvents = $l->getLatest(0, LggrState::PAGELEN);
} catch(Exception $e) {
	echo '<div class="container"><div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div></div>';

	require 'tpl/foot.inc.php';

	exit;
}

$searchvalueprog='';
$searchvalue='';

require 'tpl/nav.inc.php';
?>

<div class="container">
<?php

if(0 == count($aEvents)) {
	echo '<div class="alert alert-danger" role="alert">empty result</div>';
} // if

?>
</div>

<div class="container">
	<h2><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Live view <span id="tslatest"></span></h2>
</div>

<div class="container-fluid datablock">
<?php

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

</div>

<?php
$aPerf = $l->getPerf();
require 'tpl/foot.inc.php'
?>
