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

if (isset($_SESSION[LggrState::SESSIONNAME])) {
    $state = $_SESSION[LggrState::SESSIONNAME];
} else {
    $state = new LggrState();
} // if
  
// Are we talking to the user or is this an internal call?
$isAjax = false;

switch ($_REQUEST['a']) {
    
    case 'reset':
        $state = new LggrState();
        break;
    
    case 'search':
        $state = new LggrState();
        if ('' != trim($_POST['prog'])) {
            $state->setSearchProg(trim($_POST['prog']));
        }
        if ('' != trim($_POST['q'])) {
            $state->setSearch(trim($_POST['q']));
        } // if
        break;
    
    case 'host':
        $config = new Config();
        $l = new Lggr($state, $config);
        $id = intval($_GET['hostid']);
        $state->setHostName($l->getServersName($id));
        $state->setHostId($id);
        $state->setPage(0);
        $state->setResultSize(0);
        break;
    
    case 'level':
        $state->setLevel($_GET['level']);
        $state->setPage(0);
        $state->setResultSize(0);
        break;
    
    case 'range':
        $state->setRange(intval($_GET['range']));
        $state->setPage(0);
        $state->setResultSize(0);
        $state->setFromTo(null, null);
        break;
    
    case 'fromto':
        $sFrom = $_POST['tsfrom'];
        $sTo = $_POST['tsto'];
        $state->setFromTo($sFrom, $sTo);
        $state->setRange(null);
        $state->setPage(0);
        $state->setResultSize(0);
        break;
    
    case 'paginate':
        if (isset($_GET['page'])) {
            $i = intval($_GET['page']);
            if ($i < 0) {
                $i = 0;
            }
            
            $page = $i;
            $state->setPage($page);
        } // if
        break;
    
    case 'panelopen':
        $state->setPanelOpen(true);
        $isAjax = true;
        $sAjaxReply = 'OK';
        break;
    
    case 'panelclose':
        $state->setPanelOpen(false);
        $isAjax = true;
        $sAjaxReply = 'OK';
        break;
    
    case 'archive':
        $config = new AdminConfig();
        $l = new Lggr($state, $config);
        $iID = intval($_REQUEST['id']);
        $l->setArchive($iID, true);
        $isAjax = true;
        $sAjaxReply = $iID;
        break;
    
    case 'unarchive':
        $config = new AdminConfig();
        $l = new Lggr($state, $config);
        $iID = intval($_REQUEST['id']);
        $l->setArchive($iID, false);
        $isAjax = true;
        $sAjaxReply = $iID;
        break;
    
    case 'exportarchive':
        $config = new Config();
        $l = new Lggr($state, $config);
        $csv = new LggrCsv($l);
        $csv->generiere();
        exit();
        break;
    
    default:
        // no idea what to do, just ignore it
        break;
} // switch

$_SESSION[LggrState::SESSIONNAME] = $state;

if ($isAjax) {
    header('Content-Type: text/plain');
    echo $sAjaxReply;
} else {
    header('Location: index.php');
} // if

