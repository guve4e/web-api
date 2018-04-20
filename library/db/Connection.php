<?php

interface Connection
{
    /**
     * Connects to Database
     */
    public function connect();

    /**
     * Disconnects from Database
     */
    public function disconnect();

    public function query(string $sql);

    public function multiQuery(string $sql);

    public function storeResult();

    public function nextResult();

    public function moreResult();


}
