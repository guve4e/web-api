<?php

interface Connection
{
    public function connect();

    public function disconnect();

    public function query(string $sql);

    public function multiQuery(string $sql);

    public function beginTransaction();

    public function rollBack();

    public function getAffectedRows();

    public function getLastInsertId();

    public function getDataSet($set);

    public function storeResult();

    public function nextResult();

    public function moreResult();
}
