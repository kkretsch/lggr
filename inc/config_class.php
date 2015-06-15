<?php

class Config {

	const DBUSER = 'lggr';
	const DBPWD  = 'lggr';
	const DBNAME = 'lggrdev';

	public function getDbUser() {
		return self::DBUSER;
	}

	public function getDbPwd() {
		return self::DBPWD;
	}

	public function getDbName() {
		return self::DBNAME;
	}

} // class
