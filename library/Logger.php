<?php
/**
* Simple Logger
* @author Valentin Kormanov
* @license http://www.opensource.org/licenses/gpl-license.php 
* @package logger
* @example 
* $l = new Logger("a.txt");         // make object
* $l->addRow("This is a test", 2);  // add row
* $l->commit();                     // commit
*/
class Logger{

	// attributes 
    private $file;        // file to make to store logs
    private $content;     // content in the file
    private $writeFlag;   // flag, to append or make overwrite
    private $dir;         // logs directory
    private $endRow;	  // how to end the row, linux or windows versions
    private $path;        // path
    
    /**
    * Constructor that has some parameters that if not provided have default value
    */
    public function __construct($file, $endRow = "\n", $writeFlag = FILE_APPEND){         
        $this->file = $file;        
        $this->writeFlag = $writeFlag;       
        $this->endRow = $endRow;
        $this->dir = LOG_PATH;
        $this->path = $this->dir . "/" . $this->file;
    }

    /**
    * Call this function and suply string as content 
    * @param string content
    * @param how many new lines at the end
    */   
    public function AddRow($content= "", $newLines = 1){
        $newRow = "";
        for ($m = 0; $m < $newLines; $m++){        
            $newRow .= $this->endRow;
        }
        
        $this->content .= $content . $newRow;        
    }
    
    /**
    * @param title
    * @return string
    */
    public function MakeTitleLine($title)
    {
        $a =  "*********" . $title . " " . date('Y-m-d H:i:s') . " " ."*********";
        $this->AddRow($a,1);
        $this->Commit();
    }

    /**
    * Prints Associative array
    * @param $a
    * @param name of array
    */
    public function PrintArray($array, $title)
    {
        $t = "[" . $title . "]";
        $this->AddRow($t,1);
        $a = print_r($array, true);
        $this->AddRow($a,1);
        $this->Commit();
    } 
    
    /**
    * Commit the log
    */
    public function Commit(){  
        return file_put_contents($this->path, $this->content, $this->writeFlag);       
    }
    
    /**
    * On error
    */
    public function LogError($error,$newLines = 1)
    {
        if ($error != ""){        
            $this->AddRow($error,$newLines);          
        }                
    }

}// end Logger class

