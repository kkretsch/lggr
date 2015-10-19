<?php

class Config extends AbstractConfig {

	function __construct() {
		$this->setDbUser('lggr');
		$this->setDbPwd('lggr');
		$this->setDbName('lggrdev');

/* remote storage */
/*
		$this->setUrlBootstrap('//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/');
		$this->setUrlJquery('//code.jquery.com/');
		$this->setUrlJqueryui('//code.jquery.com/ui/1.11.4/');
		$this->setUrlJAtimepicker('//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/');
		$this->setUrlChartjs('//cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/');
*/

/* local storage */
		$this->setUrlBootstrap('/contrib/bootstrap/');
		$this->setUrlJquery('/contrib/jquery/');
		$this->setUrlJqueryui('/contrib/jqueryui/');
		$this->setUrlJAtimepicker('/contrib/timepicker/');
		$this->setUrlChartjs('/contrib/chartjs/');

	} // constructor

} // class
