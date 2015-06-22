<?php

abstract class AbstractConfig {

	protected $DBUSER;
	protected $DBPWD;
	protected $DBNAME;

	final public function getDbUser() {
		return $this->DBUSER;
	}
	final public function getDbPwd() {
		return $this->DBPWD;
	}
	final public function getDbName() {
		return $this->DBNAME;
	}

	protected function setDbUser($s) {
		$this->DBUSER = $s;
	}
	protected function setDbPwd($s) {
		$this->DBPWD = $s;
	}
	protected function setDbname($s) {
		$this->DBNAME = $s;
	}

} // class
