<?php

abstract class AbstractConfig {

    protected $DBUSER;

    protected $DBPWD;

    protected $DBNAME;

    protected $URLBOOTSTRAP;

    protected $URLJQUERY;

    protected $URLJQUERYUI;

    protected $URLJATIMEPICKER;

    protected $URLCHARTJS;

    protected $URLJQCLOUD;

    protected $LOCALE;

    final public function getDbUser() {
        return $this->DBUSER;
    }

    final public function getDbPwd() {
        return $this->DBPWD;
    }

    final public function getDbName() {
        return $this->DBNAME;
    }

    final public function getUrlBootstrap() {
        return $this->URLBOOTSTRAP;
    }

    final public function getUrlJquery() {
        return $this->URLJQUERY;
    }

    final public function getUrlJqueryui() {
        return $this->URLJQUERYUI;
    }

    final public function getUrlJAtimepicker() {
        return $this->URLJATIMEPICKER;
    }

    final public function getUrlChartjs() {
        return $this->URLCHARTJS;
    }

    final public function getUrlJQCloud() {
        return $this->URLJQCLOUD;
    }

    final public function getLocale() {
        return $this->LOCALE;
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

    protected function setUrlBootstrap($s) {
        $this->URLBOOTSTRAP = $s;
    }

    protected function setUrlJquery($s) {
        $this->URLJQUERY = $s;
    }

    protected function setUrlJqueryui($s) {
        $this->URLJQUERYUI = $s;
    }

    protected function setUrlJAtimepicker($s) {
        $this->URLJATIMEPICKER = $s;
    }

    protected function setUrlChartjs($s) {
        $this->URLCHARTJS = $s;
    }

    protected function setUrlJQCloud($s) {
        $this->URLJQCLOUD = $s;
    }

    protected function setLocale($s) {
        $this->LOCALE = $s;
    }
} // class
