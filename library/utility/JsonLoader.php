<?php

/**
 * Loads json configuration file
 */
require_once(UTILITY_PATH . "/File.php");

final class JsonLoader
{
    /**
     * @var mixed
     * Data extracted
     * from json file.
     */
    private $data;

    /**
     * @var string
     * The name of the
     * json file to be loaded.
     */
    private $fileName;

    /**
     * @var object
     * Provides file system
     * functionality
     */
    private $file;

    /**
     * Loads JSON from file.
     *
     * @return mixed representation
     * of the json data
     * @throws Exception
     */
    public function loadJsonFile() : array
    {
        $stringFileContent = $this->file->loadFileContent($this->fileName);

        if ($stringFileContent == "")
            throw new FileException("Empty Configuration File: {$this->fileName}");

        $json = $this->file->jsonDecode($stringFileContent);
        return $json;
    }

    /**
     * JsonLoader Constructor.
     *
     * @param File $fileSystem
     * @param string $fileName : the name of the file
     * to be encoded
     * @throws Exception
     */
    public function __construct(File $fileSystem, string $fileName)
    {
        if (!isset($fileSystem) || $fileName == "")
            throw new FileException("Bad parameters in JsonLoader Constructor!");

        $this->file = $fileSystem;

        if (!$this->file->fileExists($fileName))
            throw new FileException("File Not found: " . $fileName);

        // set filename
        $this->fileName = $fileName;

        // set data
        $this->data = $this->loadJsonFile();
    }

    /**
     * Getter.
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Getter.
     * @return mixed
     */
    public function getDataAsJson() : stdClass
    {
        $objectData = json_decode(json_encode($this->data));
        return $objectData;
    }
}