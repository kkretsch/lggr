<?php

class Lggr {

	private $config=null;
	private $db=null;
	private $state=null;
	private $perfTime=null;
	private $perfCount=null;

	function __construct(LggrState $state, AbstractConfig $config) {
		$this->config = $config;
		$this->state = $state;

		$this->perfCount=0;
		$this->perfTime=0;

		if(!$this->state->isLocalCall())
			$this->checkSecurity();

		$this->db = new mysqli('localhost', $this->config->getDbUSer(), $this->config->getDbPwd(), $this->config->getDbName());
	} // constructor

	function __destruct() {
		if(null != $this->db) {
			$this->db->close();
		} // if
	} // destructor

	private function checkSecurity() {
		if(!isset($_SERVER['REMOTE_USER'])) {
			throw new Exception('You must enable basic authentication');
		} // if
	} // function

	private function getViewName() {
		switch($this->state->getRange()) {
			case 1:   return 'LastHour'; break;
			case 24:  return 'Today'; break;
			case 168: return 'Week'; break;
			default:  return 'Today'; break;
		}
	}

	function getLevels() {
		$this->perfCount++;
		$startTime = microtime(true);

		$v = $this->getViewName();
		$a = array();
		$sql = "
SELECT level, COUNT(*) AS c FROM $v
GROUP BY level
ORDER BY c DESC
";

		$res = $this->db->query($sql);
		if(false === $res) {
			throw new Exception($this->db->error);
		} // if
		while($row = $res->fetch_object()) {
			$a[] = $row;
		} // while
		$res->close();

		$sum = 0;
		foreach($a as $level) {
			$sum += $level->c;
		} // foreach
		foreach($a as $level) {
			$f = $level->c / $sum * 100;
			$level->f = round($f, 2);
		} // foreach

		$this->perfTime += microtime(true)-$startTime;

		return $a;
	} // function

	function getServers() {
		$this->perfCount++;
		$startTime = microtime(true);

		$v = $this->getViewName();
		$a = array();
		$sql = "
SELECT host, COUNT(*) AS c FROM $v
GROUP BY host
ORDER BY c DESC
";

		$res = $this->db->query($sql);
		if(false === $res) {
			throw new Exception($this->db->error);
		} // if
		while($row = $res->fetch_object()) {
			$a[] = $row;
		} // while
		$res->close();

		$sum = 0;
		foreach($a as $host) {
			$sum += $host->c;
		} // foreach
		foreach($a as $host) {
			$f = $host->c / $sum * 100;
			$host->f = round($f, 2);
		} // foreach

		$this->perfTime += microtime(true)-$startTime;

		return $a;
	} // function

	function getLatest($from=0, $count=LggrState::PAGELEN) {
		$this->perfCount += 2;
		$startTime = microtime(true);

		$v = $this->getViewName();

		$sqlSize = "SELECT COUNT(*) AS c FROM $v";
		$sqlData = "
SELECT * FROM $v
ORDER BY `date` DESC
LIMIT $from,$count";

		$this->getResultSize($sqlSize);
		$a = $this->sendResult($sqlData);

		$this->perfTime += microtime(true)-$startTime;

		return $a;
	} // function

	function getFiltered($host=null, $level=null, $from=0, $count=LggrState::PAGELEN) {
		$this->perfCount += 2;
		$startTime = microtime(true);

		$v = $this->getViewName();

		$sqlSize = "SELECT COUNT(*) AS c FROM $v";
		$sqlData = "SELECT * FROM $v";

		$aWhere = array();
		if(null != $host) {
			$sTmp = $this->db->escape_string($host);
			$aWhere[] = "host='$sTmp'";
		} // if
		if(null != $level) {
			$sTmp = $this->db->escape_string($level);
			$aWhere[] = "level='$sTmp'";
		} // if

		if(count($aWhere) > 0) {
			$sqlSize .= " WHERE " . implode(' AND ', $aWhere);
			$sqlData .= " WHERE " . implode(' AND ', $aWhere);
		} // if

		$sqlData .= " ORDER BY `date` DESC LIMIT $from,$count";

		$this->getResultSize($sqlSize);
		$a = $this->sendResult($sqlData);

		$this->perfTime += microtime(true)-$startTime;

		return $a;
	} // function

	function getText($q, $from=0, $count=LggrState::PAGELEN) {
		$this->perfCount++;
		$startTime = microtime(true);

		$v = $this->getViewName();
		$sTmp = $this->db->escape_string($q);

		$sql = "
SELECT * FROM $v
WHERE message LIKE '%{$sTmp}%'
ORDER BY `date` DESC
LIMIT $from,$count";

		$a = $this->sendResult($sql);

		$this->perfTime += microtime(true)-$startTime;

		return $a;
	} // function

	function getMessagesPerHour() {
		$this->perfCount++;
		$startTime = microtime(true);

		$sql = "
SELECT HOUR(TIME(`date`)) AS h, COUNT(*) AS c
FROM Today
GROUP BY h";

		$a = $this->sendResult($sql);

		$this->perfTime += microtime(true)-$startTime;

		return $a;
	} // function

	/* delete anything older than maxage hours, or 4 weeks */
	function purgeOldMessages($maxage=672) {
		$this->perfCount++;
		$startTime = microtime(true);

		$sql = "
DELETE FROM newlogs
WHERE `date` < (NOW() - INTERVAL $maxage hour)
";
		$res = $this->db->query($sql);
		if(false === $res) {
			throw new Exception($this->db->error);
		} // if

		$this->perfTime += microtime(true)-$startTime;

		return $this->db->affected_rows;
	} // function


	private function getResultSize($sql) {
		$res = $this->db->query($sql);
		if(false === $res) {
			throw new Exception($this->db->error);
		} // if
		if($row = $res->fetch_object()) {
			$i = $row->c;
			$this->state->setResultSize($i);
		} // if
		$res->close();
	} // function

	private function sendResult($sql) {
		$a = array();

		$res = $this->db->query($sql);
		if(false === $res) {
			throw new Exception($this->db->error);
		} // if
		while($row = $res->fetch_object()) {
			$a[] = $row;
		} // while

		$res->close();
		return $a;
	} // function

	public function getPerf() {
		return array(
			'count' => $this->perfCount,
			'time' =>  $this->perfTime
		);
	} // function

} // class
