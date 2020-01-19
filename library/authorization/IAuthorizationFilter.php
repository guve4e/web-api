<?php


interface IAuthorizationFilter
{
    /**
     * Provide authorization method
     * @return mixed
     */
    public function authorize();
}