<?php
require 'inc/pre.inc.php';

define('TITLE', 'overview');
require 'tpl/head.inc.php';

define('INC_FOOTER', 'tpl/foot.inc.php');

define('TAG_STRONG_OPEN', '<strong>');
define('TAG_STRONG_CLOSE', '</strong>');
define('TAG_BUTTON_CLOSE', '</button>');
define('TAG_SPANBUTTON_CLOSE', '</span></button>');

$l = null;
try {
    $l = new Lggr($state, $config);
    
    $aLevels = $l->getLevels();
    $aServers = $l->getServers();
    $aAllServers = $l->getAllServers();
}
catch (LggrException $e) {
    echo '<div class="container"><div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div></div>';

    require INC_FOOTER;
    
    exit();
}

$aRanges = array(
    '1' => _('This hour'),
    '24' => _('Today'),
    '168' => _('Week'),
    '8760' => _('Year')
);

$page = $state->getPage();

try {
    if ($state->isSearch()) {
        
        $aEvents = $l->getText($state->getSearch(), $state->getSearchProg(),
            $page * LggrState::PAGELEN, LggrState::PAGELEN);
        $searchvalue = htmlentities($state->getSearch(),
            ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES);
        $searchvalueprog = htmlentities($state->getSearchProg(),
            ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES);
        $isSearch = true;
        $sFilter = _('Text search for');
        if ('' != $state->getSearch()) {
            $sFilter .= ' message ' . TAG_STRONG_OPEN . $searchvalue . TAG_STRONG_CLOSE;
        }
        if ('' != $state->getSearchProg()) {
            $sFilter .= ' program ' . TAG_STRONG_OPEN . $searchvalueprog . TAG_STRONG_CLOSE;
        }
    } elseif ($state->isFromTo() && $state->isHost()) {
        
        $host = $state->getHostName();
        
        $aEvents = $l->getHostFromTo($page * LggrState::PAGELEN,
            LggrState::PAGELEN);
        
        $sFilter = _('Filter by time range between') . ' ' . TAG_STRONG_CLOSE .
             htmlentities($state->getFrom(),
                 ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES) . TAG_STRONG_CLOSE . ' ' .
                 _('and') . ' ' . TAG_STRONG_OPEN .
             htmlentities($state->getTo(),
                 ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES) . TAG_STRONG_CLOSE . ', ';
                 $sFilter .= _('Filter by server') . ' ' . TAG_STRONG_OPEN .
             htmlentities($state->getHostName(),
                 ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES) . TAG_STRONG_CLOSE;
        $searchvalue = '';
        $searchvalueprog = '';
        $isSearch = false;
    } elseif ($state->isHost() || $state->isLevel()) {
        
        $host = $state->getHostName();
        $level = $state->getLevel();
        
        $aEvents = $l->getFiltered($host, $level, $page * LggrState::PAGELEN,
            LggrState::PAGELEN);
        $searchvalue = '';
        $searchvalueprog = '';
        $isSearch = false;
        $sFilter = '';
        if ($state->isHost()) {
            $sFilter .= _('Filter by server') . ' ' . TAG_STRONG_OPEN .
                 htmlentities($state->getHostName(),
                     ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES) . TAG_STRONG_CLOSE;
        }
        if ($state->isLevel()) {
            $sFilter .= _('Filter by level') . ' ' . TAG_STRONG_OPEN .
                 htmlentities($state->getLevel(),
                     ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES) . TAG_STRONG_CLOSE;
        }
    } elseif ($state->isFromTo()) {
        
        $aEvents = $l->getFromTo($page * LggrState::PAGELEN, LggrState::PAGELEN);
        $sFilter = _('Filter by time range between') . ' ' . TAG_STRONG_OPEN .
             htmlentities($state->getFrom(),
                 ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES) . TAG_STRONG_CLOSE . ' ' .
                 _('and') . ' ' . TAG_STRONG_OPEN .
             htmlentities($state->getTo(),
                 ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES) . TAG_STRONG_CLOSE;
        $searchvalue = '';
        $searchvalueprog = '';
        $isSearch = false;
    } else {
        
        $sFilter = null;
        
        $aEvents = $l->getLatest($page * LggrState::PAGELEN, LggrState::PAGELEN);
        $searchvalue = '';
        $searchvalueprog = '';
        $isSearch = false;
    } // if search
}
catch (LggrException $e) {
    echo '<div class="container"><div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div></div>';

    require INC_FOOTER;
    
    exit();
}

if (version_compare(phpversion(), '5.4', '<')) {
    echo '<div class="container"><div class="alert alert-danger" role="alert">Your PHP version ' .
         phpversion() . ' might be too old, expecting at least 5.4</div></div>';
} // if

require 'tpl/nav.inc.php';
?>

<div class="container" id="infoheader">

    <div id="accordion" class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <span class="glyphicon glyphicon-circle-arrow-down"
                        aria-hidden="true"></span><a
                        data-toggle="collapse" data-parent="#accordion"
                        href="#collapseOne"><?= _('Server status and filter') ?> ...</a>
                </h4>
            </div>
            <div id="collapseOne"
                class="panel-collapse collapse <?= $state->isPanelOpen()?'in':'' ?>">
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-4 lggr-col-level">
                            <h2
                                title="Levels of last up to <?= Lggr::LASTSTAT ?> entries">
                                <span class="glyphicon glyphicon-tasks"
                                    aria-hidden="true"></span> <?= _('Levels') ?></h2>
                            <div class="progress">
<?php
$aLevelCount = array();
foreach ($aLevels as $level) {
    $aLevelCount[$level->level] = $level->c;
    $level->f = round($level->f);
    switch ($level->level) {
        case MessageLevel::EMERG:
        case MessageLevel::CRIT:
        case MessageLevel::ERR:
            $label = 'progress-bar-danger';
            break;
        case MessageLevel::WARNING:
            $label = 'progress-bar-warning';
            break;
        case MessageLevel::NOTICE:
            $label = 'progress-bar-primary';
            break;
        case MessageLevel::INFO:
            $label = 'progress-bar-success';
            break;
        default:
            $label = '';
    } // switch
    
    echo <<<EOL
<div class="progress-bar $label" role="progressbar" aria-valuenow="{$level->f}" style="width: {$level->f}%" title="{$level->level} {$level->f}%">
<span class="sr-only">{$level->f}%</span>
</div>
EOL;
} // foreach
?>
</div>
                            <p><?= _('Distribution of selected event levels.') ?></p>
                            <p class="lggr-level-buttons">
<?php
if (isset($aLevelCount[MessageLevel::EMERG])) {
    echo '<button class="btn btn-sm btn-danger" type="button">Emergency <span class="badge">' .
        $aLevelCount[MessageLevel::EMERG] . TAG_SPANBUTTON_CLOSE . ' ';
}
if (isset($aLevelCount[MessageLevel::CRIT])) {
    echo '<button class="btn btn-sm btn-danger" type="button">Critical <span class="badge">' .
        $aLevelCount[MessageLevel::CRIT] . TAG_SPANBUTTON_CLOSE . ' ';
}
if (isset($aLevelCount[MessageLevel::ERR])) {
    echo '<button class="btn btn-sm btn-danger" type="button">Error <span class="badge">' .
        $aLevelCount[MessageLevel::ERR] . TAG_SPANBUTTON_CLOSE . ' ';
}
if (isset($aLevelCount[MessageLevel::WARNING])) {
    echo '<button class="btn btn-sm btn-warning" type="button">Warning <span class="badge">' .
        $aLevelCount[MessageLevel::WARNING] . TAG_SPANBUTTON_CLOSE . ' ';
}
if (isset($aLevelCount[MessageLevel::NOTICE])) {
    echo '<button class="btn btn-sm btn-primary" type="button">Notice <span class="badge">' .
        $aLevelCount[MessageLevel::NOTICE] . TAG_SPANBUTTON_CLOSE . ' ';
}
?>
    </p>
                        </div>

                        <div class="col-md-4 lggr-col-server">
                            <h2
                                title="Reporting servers of last up to <?= Lggr::LASTSTAT ?> entries">
                                <span
                                    class="glyphicon glyphicon-align-left"
                                    aria-hidden="true"></span> <?= _('Servers') ?></h2>
<?php
foreach ($aServers as $server) {
    if ($server->f < 5) {
        continue;
    }
    
    $server->f = round($server->f);
    
    echo <<<EOL
<div class="progress">
    <div class="progress-bar" role="progressbar" aria-valuenow="{$server->f}" aria-valuemin="0" aria-valuemax="100" style="width: {$server->f}%; min-width: 3em" title="{$server->host} {$server->f}%">
{$server->host} {$server->f}%
    </div>
</div>
EOL;
} // foreach
?>
          <p><?= _('Most reporting servers (5% or more).') ?></p>
                        </div>

                        <div class="col-md-4 lggr-col-filter">
                            <h2>
                                <span class="glyphicon glyphicon-filter"
                                    aria-hidden="true"></span> Filter
                            </h2>
                            <div class="dropdown lggr-formelement">
                                <button
                                    class="btn btn-default dropdown-toggle"
                                    type="button" id="dropdownMenu1"
                                    data-toggle="dropdown"
                                    aria-expanded="true">
                                    Server <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu"
                                    aria-labelledby="dropdownMenu1">
<?php
$aServerList = array();
foreach ($aAllServers as $server) {
    $aServerList[$server->host] = $server->id;
} // foreach
ksort($aServerList);
foreach ($aServerList as $servername => $serverid) {
    echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="./do.php?a=host&hostid=' .
         $serverid . '">' . $servername . '</a></li>';
} // foreach
?>
  </ul>
                            </div>
                            <!-- dropdown -->

                            <div
                                class="btn-group btn-group-xs lggr-formelement"
                                role="group" aria-label="level">
<?php
foreach ($aLevels as $level) {
    if ($state->isLevel() && ($level->level == $state->getLevel())) {
        echo '<button type="button" class="btn btn-primary newlog-level">' .
            $level->level . TAG_BUTTON_CLOSE;
    } else {
        echo '<button type="button" class="btn btn-default newlog-level">' .
            $level->level . TAG_BUTTON_CLOSE;
    }
} // foreach
?>
</div>

                            <div class="btn-group lggr-formelement"
                                role="group" aria-label="range">
<?php
foreach ($aRanges as $rangeValue => $rangeText) {
    if ($state->getRange() == $rangeValue) {
        echo '<button type="button" class="btn btn-primary newlog-range" data-range="' .
            $rangeValue . '">' . $rangeText . TAG_BUTTON_CLOSE;
    } else {
        echo '<button type="button" class="btn btn-default newlog-range" data-range="' .
            $rangeValue . '">' . $rangeText . TAG_BUTTON_CLOSE;
    }
} // foreach

if ($state->isFromTo()) {
    echo '<button type="button" class="btn btn-primary newlog-range" id="btnspecialrange">' .
        _('Special') . TAG_BUTTON_CLOSE;
} else {
    echo '<button type="button" class="btn btn-default newlog-range" id="btnspecialrange">' .
        _('Special') . TAG_BUTTON_CLOSE;
} // if

?>
</div>

                            <form action="do.php" method="post"
                                id="tsfromto">
                                <input type="hidden" name="a"
                                    value="fromto"> <input type="text"
                                    name="tsfrom" id="tsfrom"
                                    class="tspick" placeholder="from"> <input
                                    type="text" name="tsto" id="tsto"
                                    class="tspick" placeholder="to">
                                <button type="submit"
                                    class="btn btn-default">filter</button>
                            </form>


                            <p>
                                <a type="button" role="button"
                                    href="./do.php?a=reset"
                                    class="btn btn-default"> <span
                                    class="glyphicon glyphicon-refresh"
                                    aria-hidden="true"></span> <?= _('Reset') ?></a>
                            </p>
                        </div>
                    </div>
                    <!-- row -->

                </div>
                <!-- panel-body -->
            </div>
            <!-- collapseOne -->
        </div>
        <!-- panel -->
    </div>
    <!-- panel-group -->

</div>
<!-- /container -->

<div class="container">
<?php

if (null != $sFilter) {
    echo '<div class="alert alert-info" role="alert">' . $sFilter . '</div>';
} // if

if (0 == count($aEvents)) {
    echo '<div class="alert alert-danger" role="alert">' . _('empty result') .
         '</div>';
} // if

?>
</div>

<!-- class container for fixed max width, or container-fluid for maximum width -->
<div class="container-fluid datablock">
<?php

if (! $isSearch && (0 < count($aEvents))) {
    include 'tpl/paginate.inc.php';
}

if (0 < count($aEvents)) {
    include 'tpl/containerhead.inc.php';
}

$i = 0;
foreach ($aEvents as $event) {
    $i ++;
    
    if (0 == $i % 2) {
        $rowclass = 'even';
    } else {
        $rowclass = 'odd';
    } // if
    
    switch ($event->level) {
        case 'emerg':
            $label = '<span class="label label-danger">Emergency</span>';
            break;
        case 'crit':
            $label = '<span class="label label-danger">Critical</span>';
            break;
        case 'err':
            $label = '<span class="label label-danger">Error</span>';
            break;
        case 'warning':
            $label = '<span class="label label-warning">Warning</span>';
            break;
        case 'notice':
            $label = '<span class="label label-primary">Notice</span>';
            break;
        case 'info':
            $label = '<span class="label label-success">Info</span>';
            break;
        default:
            $label = '<span class="label label-default">' . $event->level .
                 '</span>';
    } // switch
    
    switch ($event->archived) {
        case 'Y':
            $archived = '<span id="arch' . $event->id .
                 '" class="lggr-archived glyphicon glyphicon-warning-sign" aria-hidden="true" title="archived"></span>';
            break;
        case 'N':
            $archived = '<span id="arch' . $event->id .
                 '" class="lggr-notarchived glyphicon glyphicon-pushpin" aria-hidden="true" title=""></span>';
            break;
        default:
            $archived = '?';
    } // switch
    
    $host = htmlentities($event->host, ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES);
    $program = htmlentities($event->program,
        ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES);
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

if (0 < count($aEvents)) {
    include 'tpl/containerhead.inc.php';
}

?>
<div id="dialog" title="Details">I'm a dialog</div>

<?php
if (! $isSearch && (0 < count($aEvents))) {
    include 'tpl/paginate.inc.php';
}
?>

</div>

<?php
$aPerf = $l->getPerf();
require INC_FOOTER;
?>
