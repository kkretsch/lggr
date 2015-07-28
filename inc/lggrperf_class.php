<?php

class LggrPerf {
	private $tsStart=null;
	private $tsEnd=null;
	private $tsLen=null;
	private $sQuery=null;

	function __construct() {
	} // constructor

	public function start($sql) {
		$this->sQuery = $sql;
		$this->tsStart = microtime(true);
	} // function

	public function stop() {
		$this->tsEnd = microtime(true);
		$this->tsLen = $this->tsEnd - $this->tsStart;
	} // function

	public function getPerf() {
		$a = array();

		$a['time'] = $this->tsLen;
		$a['query'] = $this->sQuery;

		$this->logperf();

		return $a;
	} // function

	private function logPerf() {
		// for debugging cases: error_log("Perf " . $this->tsLen . ", query " . $this->sQuery);
	} // function
}

