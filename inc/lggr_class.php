<?php

class Lggr {

	private $config=null;
	private $db=null;
	private $state=null;

	function __construct(LggrState $state) {
		$this->config = new Config();
		$this->state = $state;
		$this->db = new mysqli('localhost', $this->config->getDbUSer(), $this->config->getDbPwd(), $this->config->getDbName());

		$this->checkSecurity();
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

		return $a;
	} // function

	function getServers() {
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

		return $a;
	} // function

	function getLatest($from=0, $count=LggrState::PAGELEN) {
		$v = $this->getViewName();

		$sqlSize = "SELECT COUNT(*) AS c FROM $v";
		$sqlData = "
SELECT * FROM $v
ORDER BY `date` DESC
LIMIT $from,$count";

		$this->getResultSize($sqlSize);
		return $this->sendResult($sqlData);
	} // function

	function getFiltered($host=null, $level=null, $from=0, $count=LggrState::PAGELEN) {
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
		return $this->sendResult($sqlData);
	} // function

	function getByHost($host, $from=0, $count=LggrState::PAGELEN) {
		$v = $this->getViewName();
		$sTmp = $this->db->escape_string($host);

		$sqlSize = "SELECT COUNT(*) AS c FROM $v WHERE host='$sTmp'";
		$sqlData = "
SELECT * FROM $v
WHERE host='$sTmp'
ORDER BY `date` DESC
LIMIT $from,$count";

		$this->getResultSize($sqlSize);
		return $this->sendResult($sqlData);
	} // function

	function getByLevel($level, $from=0, $count=LggrState::PAGELEN) {
		$v = $this->getViewName();
		$sTmp = $this->db->escape_string($level);

		$sqlSize = "SELECT COUNT(*) AS c FROM $v WHERE level='$sTmp'";
		$sqlData = "
SELECT * FROM $v
WHERE level='$sTmp'
ORDER BY `date` DESC
LIMIT $from,$count";

		$this->getResultSize($sqlSize);
		return $this->sendResult($sqlData);
	} // function

	function getText($q, $from=0, $count=LggrState::PAGELEN) {
		$v = $this->getViewName();
		$sTmp = $this->db->escape_string($q);

		$sql = "
SELECT * FROM $v
WHERE message LIKE '%{$sTmp}%'
ORDER BY `date` DESC
LIMIT $from,$count";

		return $this->sendResult($sql);

	} // function

	function getMessagesPerHour() {
		$sql = "
SELECT HOUR(TIME(`date`)) AS h, COUNT(*) AS c
FROM Today
GROUP BY h";

		return $this->sendResult($sql);
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

} // class
