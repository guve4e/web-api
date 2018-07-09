<?php

interface Database
{
    /**
     * Begins a transaction.
     * Requires MySQL 5.6 and above,
     * and the InnoDB engine.
     */
    public function startTransaction();

    /**
     * Commit Transaction.
     */
    public function commit();

    /**
     * Roll Back Transaction.
     */
    public function rollback();

    /**
     * Checks the success of previous
     * sql query and if the query was NOT successful
     * then it rolls-back
     * @param $msg
     */
    public function rollbackOnFail($msg);

    /**
     * @param bool $verbose
     */
    public function setVerbose($verbose);

    /**
     * @return mixed
     */
    public function getResponse();

    /**
     * @return int
     * Returns the last updated ID
     * in the table
     * @throws Exception
     */
    public function getLastInsertId();

    /**
     * Generic function to call
     * the more specific type of
     * query function
     *
     * @param $sql: the sql query
     * @param $function: the name of the function
     * to be called
     * @return mixed: the result of the query
     * @throws ApiException
     */
    public function query($sql, $function);
}