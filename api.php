<?php
spl_autoload_register(
    function ($class) {
        include 'inc/' . strtolower($class) . '_class.php';
    });

session_start();

if (! isset($_REQUEST['a'])) {
    header('Location: index.php');
    exit();
} // if

$config = new Config();
$state = new LggrState();
$l = new Lggr($state, $config);

switch ($_REQUEST['a']) {
    
    case 'latest':
        $id = intval($_REQUEST['id']);
        $aEvents = $l->getNewer($id);
        
        header('Content-Type: application/json; charset=utf8');
        
        echo json_encode($aEvents);
        
        break;
    
    default:
        // ignore
        break;
} // switch

