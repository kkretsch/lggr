<?php

class LggrState {

	const SESSIONNAME = 'LggrState';

	private $bSearch=false;
	private $sSearch=null;
	private $iPage=0;
	private $sHost=null;
	private $sLevel=null;
	private $iRange=24; // default 24h = today, sort of

	function __construct() {
		$this->iPage=0;
		$this->bSearch=false;
		$this->sSearch=null;
		$this->sHost=null;
		$this->sLevel=null;
		$this->iRange=24;
	} // constructor

	public function setSearch($s) {
		if(null != $s) {
			$this->bSearch = true;
			$this->sSearch = $s;
		}
	}
	public function isSearch() {
		return $this->bSearch;
	}
	public function getSearch() {
		return $this->sSearch;
	}

	public function setPage($i) {
		$this->iPage = $i;
	}
	public function getPage() {
		return $this->iPage;
	}

	public function setHost($s) {
		$this->sHost = $s;
	}
	public function getHost() {
		return $this->sHost;
	}
	public function isHost() {
		return null != $this->sHost;
	}

	public function setLevel($s) {
		$this->sLevel = $s;
	}
	public function getLevel() {
		return $this->sLevel;
	}
	public function isLevel() {
		return null != $this->sLevel;
	}

	public function setRange($i) {
		$this->iRange = $i;
	}
	public function getRange() {
		return $this->iRange;
	}

} // class
