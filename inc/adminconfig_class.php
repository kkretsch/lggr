<?php

class AdminConfig extends AbstractConfig {

	function __construct() {
		$this->setDbUser('lggradmin');
		$this->setDbPwd('lggr');
		$this->setDbName('lggrdev');
	} // constructor

} // class
