<?php
header('Content-Type: text/javascript');

spl_autoload_register(function ($class) {
    include '../inc/' . strtolower($class) . '_class.php';
});

$searchvalue = '';

session_start();

define('COLORALERT', 'd9534f');

if (isset($_SESSION[LggrState::SESSIONNAME])) {
    $state = $_SESSION[LggrState::SESSIONNAME];
} else {
    $state = new LggrState();
} // if

$aColors = array(
    'emerg' => COLORALERT,
    'crit' => COLORALERT,
    'err' => COLORALERT,
    'warning' => '#f0ad4e',
    'notice' => '#337ab7',
    'info' => '#5cb85c'
);

$l = null;
try {
    $config = new Config();
    $l = new Lggr($state, $config);
    
    $aLevels = $l->getLevels();
    $aServers = $l->getServers();
    
    $aStatistic = $l->getStatistic();
    $aStatistic = $aStatistic[0];
    
    $aMsgPerHour = $l->getMessagesPerHour();
    
    $aCloud = $l->getCloud();
} catch (LggrException $e) {
    
    exit();
}

?>

<!-- dynamic data -->

<?php
$aTmp = array();
foreach ($aMsgPerHour as $hour) {
    $aTmp[$hour->h] = $hour->c;
} // foreach
?>
var dataMsgsPerHour = {
	labels: ["<?= implode('","', array_keys($aTmp)) ?>"],
	datasets: [ {
		label: "Msgs per hour",
		fillColor: "rgba(220,220,220,0.5)",
		data: [<?= implode(',', array_values($aTmp)) ?>],
	} ]
};

<?php
$aTmp = array();
foreach ($aServers as $server) {
    $aTmp[$server->host] = $server->c;
} // foreach
?>
var dataServers = {
	labels: ["<?= implode('","', array_keys($aTmp)) ?>"],
	datasets: [ {
		label: "Hostname",
		fillColor: "rgba(151,187,205,0.5)",
		data: [<?= implode(',', array_values($aTmp)) ?>],
	} ]
};

var dataLevels = [
<?php
foreach ($aLevels as $level) {
    $newVal = round(log($level->c));
    $newCol = $aColors[$level->level];
    echo <<<EOL
    {
        value: $newVal,
        color: "$newCol",
        label: "{$level->level}",
    },

EOL;
} // foreach
?>
];

var dataServersPie = [
<?php
foreach ($aServers as $server) {
    $sHash = md5($server->host);
    $cHash = $sHash[0] . '0' . $sHash[1] . '0' . $sHash[2] . '0';
    $cHashHigh = $sHash[0] . 'f' . $sHash[1] . 'f' . $sHash[2] . 'f';
    echo <<<EOL
    {
        value: {$server->c},
        color: "#$cHash",
        highlight: "#$cHashHigh",
        label: "{$server->host}"
    },

EOL;
} // foreach
?>
];


var dataCloudWords = [
<?php
foreach ($aCloud as $entry) {
    $prog = $entry->program;
    if (false !== strpos($prog, '&')) {
        continue;
    }
    $prog = htmlspecialchars($prog, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
    echo <<<EOL
        {
            text: "{$prog}",
            weight: {$entry->c}
        },

EOL;
} // foreach

?>
];
