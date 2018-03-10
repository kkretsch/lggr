<?php
spl_autoload_register(
    function ($class) {
        include __DIR__ . '/../inc/' . strtolower($class) . '_class.php';
    });

$iCountServers = 0;
$aPerf = array();
$l = null;
try {
    $config = new AdminConfig();
    
    $state = new LggrState();
    $state->setLocalCall(true);
    
    $l = new Lggr($state, $config);
    
    $iCountServers = $l->updateServers();
    
    $aPerf = $l->getPerf();
}
catch (LggrException $e) {
    die($e->getMessage());
} // try

$pCount = count($aPerf);
$pTime = 0;
foreach ($aPerf as $perf) {
    $aTmp = $perf->getPerf();
    $pTime += $aTmp['time'];
} // foreach

?>
Purging updating <?= $iCountServers ?> servers with <?= $pCount ?> queries in <?= $pTime ?> seconds.
