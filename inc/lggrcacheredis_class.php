<?php

class LggrCacheRedis extends AbstractLggrCache {
	const MAXAGE = 300; // 5 minutes
	const REDISHOST = 'localhost';
	const REDISDB = 0;
	const REDISPFX = 'lggr_';
	private $r=null;

	function __construct() {
		$this->r = new Redis();
		$this->r->connect(self::REDISHOST);
		$this->r->select(self::REDISDB);
	} // constructor

	function __destruct() {
		$this->r->close();
	} // destructor

	public function store($key, $value) {
		$s = serialize($value);
		$this->r->setex(SELF::REDISPFX . $key, self::MAXAGE, $s);
	} // function

	public function retrieve($key) {
		$value = $this->r->get(SELF::REDISPFX . $key);
		if(false === $value) {
		    return null;
		}
		return unserialize($value);
	} // function

	public function purge($key) {
		$this->r->delete(SELF::REDISPFX . $key);
	} // function

} // class
