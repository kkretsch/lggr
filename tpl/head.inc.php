<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<?php
$title = '';
if (defined('TITLE')) {
    $title = TITLE;
} // if
?>
    <title>Lggr.io <?= $title ?></title>
<!-- Bootstrap -->
<link href="<?= $config->getUrlBootstrap() ?>css/bootstrap.min.css"
	rel="stylesheet" media="screen">
<link rel="stylesheet"
	href="<?= $config->getUrlJqueryui() ?>themes/smoothness/jquery-ui.css"
	media="screen">
<link rel="stylesheet"
	href="<?= $config->getUrlJAtimepicker() ?>jquery-ui-timepicker-addon.min.css"
	media="screen">
<link rel="stylesheet"
	href="<?= $config->getUrlJQCloud() ?>jqcloud.min.css" media="screen">
<link href="css/lggr.css" rel="stylesheet" media="screen">
<link href="css/lggr_print.css" rel="stylesheet" media="print">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<link rel="apple-touch-icon" sizes="57x57"
	href="/icos/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60"
	href="/icos/apple-touch-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72"
	href="/icos/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76"
	href="/icos/apple-touch-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114"
	href="/icos/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120"
	href="/icos/apple-touch-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144"
	href="/icos/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152"
	href="/icos/apple-touch-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180"
	href="/icos/apple-touch-icon-180x180.png">
<link rel="icon" type="image/png" href="/icos/favicon-32x32.png"
	sizes="32x32">
<link rel="icon" type="image/png" href="/icos/favicon-194x194.png"
	sizes="194x194">
<link rel="icon" type="image/png" href="/icos/favicon-96x96.png"
	sizes="96x96">
<link rel="icon" type="image/png"
	href="/icos/android-chrome-192x192.png" sizes="192x192">
<link rel="icon" type="image/png" href="/icos/favicon-16x16.png"
	sizes="16x16">
<link rel="manifest" href="/icos/manifest.json">
<meta name="msapplication-TileColor" content="#ffc40d">
<meta name="msapplication-TileImage" content="/icos/mstile-144x144.png">
<meta name="theme-color" content="#ff0000">
</head>
<body>