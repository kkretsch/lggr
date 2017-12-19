<?php

class Lggr {
	const LASTSTAT=5000;
	const ARCHIVEDSIZE='archivedSize';
	const INNERAND=' AND ';

	private $config=null;
	private $db=null;
	private $state=null;
	private $cache=null;
	private $aPerf=null;

	function __construct(LggrState $state, AbstractConfig $config) {
		$this->config = $config;
		$this->state = $state;
		$this->cache = new LggrCacheRedis();
		$this->aPerf = array(); // of type LggrPerf objects

		if(!$this->state->isLocalCall()) {
			$this->checkSecurity();
		}

		$this->db = new mysqli('localhost', $this->config->getDbUSer(), $this->config->getDbPwd(), $this->config->getDbName());
		$this->db->set_charset('utf8');
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
	    $rcView='';
		switch($this->state->getRange()) {
		    case 1:    $rcView = 'LastHour'; break;
		    case 24:   $rcView = 'Today'; break;
		    case 168:  $rcView = 'Week'; break;
		    case 8760: $rcView = 'Year'; break;
		    default:   $rcView = 'Today'; break;
		}
		return $rcView;
	}

	function getLevels() {
		$perf = new LggrPerf();

		$v = $this->getViewName();
		$sql = "
SELECT level, COUNT(*) AS c
FROM (SELECT level FROM $v ORDER BY `date` DESC LIMIT " . self::LASTSTAT . ") AS sub
GROUP BY level
ORDER BY c DESC
";

		$a = $this->cache->retrieve("levels$v");
		if(null != $a) {
			return $a;
		} // if
		$a = array();

		$perf->start($sql);

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

		$perf->stop();
		$this->aPerf[] = $perf;

		$this->cache->store("levels$v", $a);
		return $a;
	} // function

	function getAllServers() {
		$perf = new LggrPerf();

		$sql = "
SELECT DISTINCT host
FROM newlogs";

		$a = $this->cache->retrieve("allservers");
		if(null != $a) {
			return $a;
		} // if
		$a = array();

		$perf->start($sql);

		$res = $this->db->query($sql);
		if(false === $res) {
			throw new Exception($this->db->error);
		} // if
		while($row = $res->fetch_object()) {
			$a[] = $row;
		} // while
		$res->close();

		$perf->stop();
		$this->aPerf[] = $perf;

		$this->cache->store("allservers", $a);

		return $a;
	} // function

	function getServers() {
		$perf = new LggrPerf();

		$v = $this->getViewName();

		$sql = "
SELECT host, COUNT(*) AS c 
FROM (SELECT host FROM $v ORDER BY `date` DESC LIMIT " . self::LASTSTAT . ") AS sub
GROUP BY host
ORDER BY c DESC";

		$a = $this->cache->retrieve("servers$v");
		if(null != $a) {
			return $a;
		} // if
		$a = array();

		$perf->start($sql);

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

		$perf->stop();
		$this->aPerf[] = $perf;

		$this->cache->store("servers$v", $a);
		return $a;
	} // function

	function getArchived($from=0, $count=LggrState::PAGELEN) {
	    $iArchivedSize = $this->cache->retrieve(ARCHIVEDSIZE);
	    $aArchivedData = $this->cache->retrieve(ARCHIVEDSIZE . intval($from));

		if((null != $iArchivedSize) && (null != $aArchivedData)) {
			$this->state->setResultSize($iArchivedSize);
			return $aArchivedData;
		} // if


		$perfSize = new LggrPerf();
		$perfData = new LggrPerf();

		$sqlSize = "SELECT COUNT(*) AS c FROM Archived";
		$sqlData = "
SELECT * FROM Archived
ORDER BY `date` DESC
LIMIT $from,$count";

		$perfSize->start($sqlSize);
		$this->getResultSize($sqlSize);
		$perfSize->stop();

		$perfData->start($sqlData);
		$a = $this->sendResult($sqlData);
		$perfData->stop();

		$this->aPerf[] = $perfSize;
		$this->aPerf[] = $perfData;

		$this->cache->store("archivedSize", $this->state->getResultSize());
		$this->cache->store("archivedData" . intval($from), $a);

		return $a;
	} // function

	function getLatest($from=0, $count=LggrState::PAGELEN) {
		$perfSize = new LggrPerf();
		$perfData = new LggrPerf();

		$v = $this->getViewName();

		$sqlSize = "SELECT COUNT(*) AS c FROM $v";
		$sqlData = "
SELECT * FROM $v
ORDER BY `date` DESC
LIMIT $from,$count";

		$perfSize->start($sqlSize);
		$this->getResultSize($sqlSize);
		$perfSize->stop();

		$perfData->start($sqlData);
		$a = $this->sendResult($sqlData);
		$perfData->stop();

		$this->aPerf[] = $perfSize;
		$this->aPerf[] = $perfData;

		return $a;
	} // function

	function getCloud() {
		$perf = new LggrPerf();

		$v = $this->getViewName();

		$a = $this->cache->retrieve("cloud$v");
		if(null != $a) {
			return $a;
		} // if

		$sql = "SELECT COUNT(*) AS c, program FROM $v GROUP BY program HAVING CHAR_LENGTH(program)>2 ORDER BY c DESC";
		$perf->start($sql);
		$a = $this->sendResult($sql);
		$perf->stop();

		$this->aPerf[] = $perf;

		$this->cache->store("cloud$v", $a);

		return $a;
	} // function

	function getNewer($id) {
		$perf = new LggrPerf();

		$sqlData = "
SELECT * FROM LastHour
WHERE id>$id
ORDER BY `date` DESC
LIMIT " . LggrState::PAGELEN;

		$perf->start($sqlData);
		$a = $this->sendResult($sqlData);
		$perf->stop();

		$this->aPerf[] = $perf;

		return $a;
	} // function

	function getEntry($id) {
		$perf = new LggrPerf();

		$sqlData = "
SELECT * FROM LastHour
WHERE id=$id";

		$perf->start($sqlData);
		$a = $this->sendResult($sqlData);
		$perf->stop();

		$this->aPerf[] = $perf;
		return $a;
	} // function

	function getFromTo($from=0, $count=LggrState::PAGELEN) {
		$perfSize = new LggrPerf();
		$perfData = new LggrPerf();

		$sFrom = $this->db->escape_string($this->state->getFrom());
		$sTo   = $this->db->escape_string($this->state->getTo());

		$sqlSize = "
SELECT COUNT(*) AS c FROM newlogs
WHERE `date` BETWEEN '$sFrom' AND '$sTo'";

		$sqlData = "
SELECT * FROM newlogs
WHERE `date` BETWEEN '$sFrom' AND '$sTo'
ORDER BY `date` DESC
LIMIT $from,$count";

		$perfSize->start($sqlSize);
		$this->getResultSize($sqlSize);
		$perfSize->stop();

		$perfData->start($sqlData);
		$a = $this->sendResult($sqlData);
		$perfData->stop();

		$this->aPerf[] = $perfSize;
		$this->aPerf[] = $perfData;

		return $a;
	} // function

	function getHostFromTo($from=0, $count=LggrState::PAGELEN) {
		$perfSize = new LggrPerf();
		$perfData = new LggrPerf();

		$sHost = $this->db->escape_string($this->state->getHost());
		$sFrom = $this->db->escape_string($this->state->getFrom());
		$sTo   = $this->db->escape_string($this->state->getTo());

		$sqlSize = "
SELECT COUNT(*) AS c FROM newlogs
WHERE `date` BETWEEN '$sFrom' AND '$sTo'
AND host='$sHost'";

		$sqlData = "
SELECT * FROM newlogs
WHERE `date` BETWEEN '$sFrom' AND '$sTo'
AND host='$sHost'
ORDER BY `date` DESC
LIMIT $from,$count";

		$perfSize->start($sqlSize);
		$this->getResultSize($sqlSize);
		$perfSize->stop();

		$perfData->start($sqlData);
		$a = $this->sendResult($sqlData);
		$perfData->stop();

		$this->aPerf[] = $perfSize;
		$this->aPerf[] = $perfData;

		return $a;
	} // function

	function getFiltered($host=null, $level=null, $from=0, $count=LggrState::PAGELEN) {
		$perfSize = new LggrPerf();
		$perfData = new LggrPerf();

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
		    $sqlSize .= " WHERE " . implode(INNERAND, $aWhere);
		    $sqlData .= " WHERE " . implode(INNERAND, $aWhere);
		} // if

		$sqlData .= " ORDER BY `date` DESC LIMIT $from,$count";

		$perfSize->start($sqlSize);
		$this->getResultSize($sqlSize);
		$perfSize->stop();

		$perfData->start($sqlData);
		$a = $this->sendResult($sqlData);
		$perfData->stop();

		$this->aPerf[] = $perfSize;
		$this->aPerf[] = $perfData;

		return $a;
	} // function

	function getText($msg='', $prog='', $from=0, $count=LggrState::PAGELEN) {
		$perf = new LggrPerf();

		$v = $this->getViewName();
		$sTmpMsg = $this->db->escape_string($msg);
		$sTmpProg = $this->db->escape_string($prog);

		$aWhere = array();
		if('' != $msg) {
			$aWhere[] = "message LIKE '%{$sTmpMsg}%'";
		} // if
		if('' != $prog) {
			$aWhere[] = "program LIKE '%{$sTmpProg}%'";
		} // if
		$sWhere = implode(' AND ', $aWhere);

		$sql = "
SELECT * FROM $v
WHERE $sWhere
ORDER BY `date` DESC
LIMIT $from,$count";

		$perf->start($sql);
		$a = $this->sendResult($sql);
		$perf->stop();

		$this->aPerf[] = $perf;

		return $a;
	} // function

	function getMessagesPerHour() {
		$perf = new LggrPerf();

		$sql = "
SELECT HOUR(TIME(`date`)) AS h, COUNT(*) AS c
FROM Today
GROUP BY h";

		$a = $this->cache->retrieve('mph');
		if(null != $a) {
			return $a;
		} // if

		$perf->start($sql);
		$a = $this->sendResult($sql);
		$perf->stop();

		$this->aPerf[] = $perf;

		$this->cache->store('mph', $a);
		return $a;
	} // function

	function getArchivedStatistic() {
		$perf = new LggrPerf();

		$sql = "
SELECT COUNT(*) AS cnt
FROM Archived
";

		$a = $this->cache->retrieve('archivedstats');
		if(null != $a) {
			return $a;
		} // if

		$perf->start($sql);
		$a = $this->sendResult($sql);
		$perf->stop();

		$this->aPerf[] = $perf;

		$this->cache->store('archivedstats', $a);
		return $a;
	} // function

	function getStatistic() {
		$perf = new LggrPerf();

		$sql = "
SELECT COUNT(*) AS cnt, MIN(`date`) AS oldest
FROM newlogs
";

		$a = $this->cache->retrieve('stats');
		if(null != $a) {
			return $a;
		} // if

		$perf->start($sql);
		$a = $this->sendResult($sql);
		$perf->stop();

		$this->aPerf[] = $perf;

		$this->cache->store('stats', $a);
		return $a;
	} // function

	/* delete anything older than maxage hours, or 4 weeks */
	function purgeOldMessages($maxage=672) {
		$perf = new LggrPerf();

		$sql = "
DELETE FROM newlogs
WHERE `date` < (NOW() - INTERVAL $maxage hour)
AND archived='N'
";

		$perf->start($sql);
		$res = $this->db->query($sql);
		if(false === $res) {
			throw new Exception($this->db->error);
		} // if
		$perf->stop();
		$this->aPerf[] = $perf;

		return $this->db->affected_rows;
	} // function

	function setArchive($iID, $bIsArchived) {
		$iID = intval($iID);
		if($bIsArchived) {
			$sArchive = 'Y';
		} else {
			$sArchive = 'N';
		} // if

		$sql = "UPDATE newlogs SET archived='$sArchive' WHERE id=$iID LIMIT 1";
		$res = $this->db->query($sql);
		if(false === $res) {
			throw new Exception($this->db->error);
		} // if

		$this->cache->purge(ARCHIVEDSIZE);
		$this->cache->purge("archivedData0");
	} // function

	function normalizeHosts() {

		// Find any new hostnames
		$sql = "
SELECT newlogs.host
FROM newlogs
LEFT JOIN hosts ON hosts.name=newlogs.host
WHERE hosts.id IS NULL
GROUP BY newlogs.host";
		$aEmpty = $this->sendResult($sql);
		foreach($aEmpty as $o) {
			$host = $o->host;
			$host = $this->db->escape_string($host);

			$sql = "INSERT INTO hosts (name) VALUES ('$host')";
			$res = $this->db->query($sql);
			if(false === $res) {
				throw new Exception($this->db->error);
			} // if
			$id = $this->db->insert_id;

			$sql = "UPDATE newlogs SET idhost=$id WHERE host='$host'";
			$res = $this->db->query($sql);
			if(false === $res) {
				throw new Exception($this->db->error);
			} // if
		} // foreach

		// read current list of hostnames and ids
		$sql = "
SELECT *
FROM hosts";
		$aTmp = $this->sendResult($sql);
		$aHosts = array();
		foreach($aTmp as $o) {
			$hostId = $o->id;
			$hostName = $o->name;
			$aHosts[$hostName] = $hostId;
		} // foreach

		// search any new entry without hostid and update it
		foreach($aHosts as $hostName => $hostId) {
			$hostName = $this->db->escape_string($hostName);
			$sql = "
UPDATE newlogs
SET idhost=$hostId
WHERE idhost IS NULL
AND host='$hostName'
";
			$res = $this->db->query($sql);
			if(false === $res) {
				throw new Exception($this->db->error);
			} // if
		} // foreach

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
		return $this->aPerf;
	} // function

} // class
