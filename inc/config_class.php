<?php

class Config extends AbstractConfig {

	function __construct() {
		$this->setDbUser('lggr');
		$this->setDbPwd('lggr');
		$this->setDbName('lggrdev');

		$this->setUrlBootstrap('/contrib/bootstrap/');
		$this->setUrlJquery('/contrib/jquery/');
		$this->setUrlJqueryui('/contrib/jqueryui/');
		$this->setUrlJAtimepicker('/contrib/timepicker/');
		$this->setUrlChartjs('/contrib/chartjs/');
	} // constructor

} // class
