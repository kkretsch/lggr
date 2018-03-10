<?php

abstract class AbstractLggrCache {

    abstract protected function store($key, $value);

    abstract protected function retrieve($key);

    abstract protected function purge($key);
}
