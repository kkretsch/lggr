<?php

class LggrCache {
	const MAXAGE = 300; // 5 minutes
	private $cachepath=null;

	function __construct() {
		$this->cachepath = __DIR__ . '/../cache/';
	} // constructor

	public function store($key, $value) {
		$fname = $this->getFilename($key);
		$s = serialize($value);
		file_put_contents($fname, $s);
	} // function

	public function retrieve($key) {
		$fname = $this->getFilename($key);
		if(file_exists($fname) && is_readable($fname)) {
			$ts = filemtime($fname);
			if(time() - $ts > self::MAXAGE) {
				unlink($fname);
				return null;
			} else {
				$s = file_get_contents($fname);
				$a = unserialize($s);
				return $a;
			} // if
		} else {
			return null;
		} // if
	} // function

	public function purge($key) {
		$fname = $this->getFilename($key);
		unlink($fname);
	} // function


	private function filterKey($key) {
		$sTmp = str_replace(' ', '-', $key);
		$sTmp = preg_replace('/[^A-Za-z0-9\-]/', '', $sTmp);
		return $sTmp;
	} // function

	private function getFilename($key) {
		return $this->cachepath . 'key_' . $this->filterKey($key) . '.data';
	} // function

}

