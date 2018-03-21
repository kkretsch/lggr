<?php

class AdminConfig extends AbstractConfig {

    function __construct() {
        $this->setDbUser('loggeradmin');
        $this->setDbPwd('lggr');
        $this->setDbName('lggr');
    } // constructor
} // class
