<?php

use PHPUnit\Util\Json;

interface IHttpRequest
{
    /**
     * Sets URL.
     * @param string $url
     * @return mixed
     */
    public function setUrl(string $url);

    /**
     * Sets Request Method.
     * @param string $method
     * @return mixed
     */
    public function setMethod(string $method);

    /**
     * Sets Content Type,
     * part of headers fields.
     * @param string $contentType
     * @return mixed
     */
    public function setContentType(string $contentType);

    /**
     * Sets Headers.
     * Accepts array of key, value
     * pairs.
     * @param array $headers
     * @return mixed
     */
    public function setHeaders(array $headers);

    /**
     * Adds to the headers by
     * field name and field value.
     * @param string $fieldName
     * @param string $fieldValue
     * @return mixed
     */
    public function addHeader(string $fieldName, string $fieldValue);

    /**
     * Adds body to the request.
     * Data to be send to the server.
     * @param array $data
     * @return mixed
     */
    public function addBodyJson(array $data);

    /**
     * Sends the HTTP Request.
     */
    public function send();

    /**
     * Gives back the response
     * form the server as string.
     * @return string
     */
    public function getResponseAsString();

    /**
     * Gives back the response
     * form the server as an array.
     * @return string
     */
    public function getResponseAsArray();

    /**
     * Gives back the response
     * form the server as JSON object.
     * @return Json
     */
    public function getResponseAsJson();

    /**
     * Gives back the response
     * form the server as a packed
     * Rest Response object, that holds
     * some information about the request.
     * @return RestResponse object
     */
    public function getResponseWithInfo();
}