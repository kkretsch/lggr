<?php

require 'inc/pre.inc.php';

define('TITLE', _('Archived'));
require 'tpl/head.inc.php';

$l = null;
try {
	$l = new Lggr($state, $config);
} catch(Exception $e) {
	echo '<div class="container"><div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div></div>';

	require 'tpl/foot.inc.php';

	exit;
}

$page = $state->getPage();

try {
	$aEvents = $l->getArchived($page*LggrState::PAGELEN, LggrState::PAGELEN);
	$searchvalue='';
	$searchvalueprog='';
	$isSearch=false;
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
	<?= _('Archived') ?>, <a href="/do.php?a=exportarchive" target="_blank">export</a> to csv.
</div>

<div class="container">
<?php

if(0 == count($aEvents)) {
	echo '<div class="alert alert-danger" role="alert">' . _('empty result') . '</div>';
} // if

?>
</div>

<!-- class container for fixed max width, or container-fluid for maximum width -->
<div class="container-fluid datablock">
<?php

if(!$isSearch)
	include 'tpl/paginate.inc.php';

include 'tpl/containerhead.inc.php';

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

	switch($event->archived) {
	case 'Y': $archived = '<span id="arch' . $event->id . '" class="lggr-archived glyphicon glyphicon-warning-sign" aria-hidden="true" title="archived"></span>'; break;
	case 'N': $archived = '<span id="arch' . $event->id . '" class="lggr-notarchived glyphicon glyphicon-pushpin" aria-hidden="true" title=""></span>'; break;
	default:  $archived = '?';
	} // switch

	$host = htmlentities($event->host, ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES);
	$program = htmlentities($event->program, ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES);
	$msg = htmlentities($event->message, ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES);

	echo <<<EOL
<div class="row datarow $rowclass" data-id="{$event->id}">
	<div class="col-md-2 col-xs-6 newlog-date">{$event->date}</div>
	<div class="col-md-1 col-xs-2">{$event->facility}</div>
	<div class="col-md-1 col-xs-2">$archived $label</div>
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
