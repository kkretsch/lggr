<?php

class LggrState {

	const SESSIONNAME = 'LggrState';
	const PAGELEN = 100;

	private $bLocalCall=false;
	private $bSearch=false;
	private $sSearch=null;
	private $sSearchProg=null;
	private $iPage=0;
	private $sHost=null;
	private $sLevel=null;
	private $iRange=24;	// default 24h = today, sort of
	private $bFromTo=false;
	private $tsFrom=null;
	private $tsTo=null;
	private $iResultSize=0;	// result size of last query
	private $bPanelOpen=false;

	function __construct() {
		$this->bLocalCall=false;
		$this->iPage=0;
		$this->bSearch=false;
		$this->sSearch=null;
		$this->bSearchProg=false;
		$this->sSearchProg=null;
		$this->sHost=null;
		$this->sLevel=null;
		$this->iRange=24;
		$this->bFromTo=false;
		$this->tsFrom=null;
		$this->tsTo=null;
		$this->iResultSize=0;
		$this->bPanelOpen=false;
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

	public function setSearchProg($s) {
		if(null != $s) {
			$this->bSearch = true;
			$this->sSearchProg = $s;
		} // if
	}
	public function getSearchProg() {
		return $this->sSearchProg;
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

	public function setFromTo($tsFrom, $tsTo) {
		if(null==$tsFrom && null==$tsTo) {
			$this->bFromTo=false;
		} else {
			$this->bFromTo=true;
		} // if
		$this->tsFrom=$tsFrom;
		$this->tsTo=$tsTo;
	}
	public function isFromTo() {
		return $this->bFromTo;
	}
	public function getFrom() {
		return $this->tsFrom;
	}
	public function getTo() {
		return $this->tsTo;
	}

	public function setResultSize($i) {
		$this->iResultSize = $i;
	}
	public function getResultSize() {
		return $this->iResultSize;
	}

	public function setLocalCall($b) {
		$this->bLocalCall = $b;
	}
	public function isLocalCall() {
		return $this->bLocalCall;
	}

	public function setPanelOpen($b) {
		$this->bPanelOpen = $b;
	}
	public function isPanelOpen() {
		return $this->bPanelOpen;
	}

} // class
