<?php

class Rdbms
{
    private $strategy = NULL;

    public function __construct(string $rdbmsType, Connection $connection)
    {
        switch ($rdbmsType)
        {
            case "Mysql":
                $this->strategy = new Mysql($connection);
                break;
        }
    }

    public function startTransaction() {
        $this->strategy->startTransaction();
    }

    public function commit() {
        $this->strategy->commit();
    }

    public function rollback() {
        $this->strategy->rollback();
    }

    public function rollbackOnFail(string $msg) {
        $this->strategy->rollbackOnFail($msg);
    }

    public function setVerbose(bool $verbose) {
        $this->strategy->setVerbose($verbose);
    }

    public function getResponse() {
        $this->strategy->getResponse();
    }

    public function getLastInsertId() {
        $this->strategy->getLastInsertId();
    }

    public function query(string $sql, string $function) {
        $this->strategy->query($sql, $function);
    }
}