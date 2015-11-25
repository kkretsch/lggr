<?php

spl_autoload_register(function($class) {
	$class = strtolower($class);
	$class = str_replace('\\', '/', $class);
	include __DIR__ . '/' . $class . '_class.php';
});

$config = new Config();

session_start();

if(isset($_SESSION[LggrState::SESSIONNAME])) {
	$state = $_SESSION[LggrState::SESSIONNAME];
} else {
	$state = new LggrState();
} // if


// Uebersetzungen via gettext vorbereiten
/*
 * Auf dem Server ausführen:
 * locale -a
 * sollte ergeben:
 * ar_AE.utf8
 * C
 * C.UTF-8
 * de_DE.utf8
 * en_GB.utf8
 * en_US.utf8
 * fr_FR.utf8
 * POSIX
 *
 * Ansonsten via dpkg-reconfigure locales die fehlenden locales nacherzeugen!
 */
$lang = $config->getLocale() . '.UTF-8';
putenv("LC_ALL=$lang");
$rc = setlocale(LC_ALL, $lang);
if(!$rc) error_log("setlocale failed! $lang");
bindtextdomain('messages', __DIR__ . '/../locale');
bind_textdomain_codeset('messages', 'UTF-8');
textdomain('messages');
