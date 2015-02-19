<?php

class CSVParser
{
    private static $_instance;
    
    protected $_file = '';
    protected $_fileHandle;
    protected $_dataArray = array();
    
    public static function getInstance()
    {
        if(!self::$_instance)
        {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    private function __construct()
    {
        
    }
    
    public function OpenFile($file)
    {
        if($file != '')
        {
            $this->_fileHandle = fopen($file, 'r');
            
            $this->ParseFile();
        }
        else 
        {
            echo 'No CSV file to open!';
        }
    }
    
    public function ParseFile()
    {
        while(!feof($this->_fileHandle))
        {
            $this->_dataArray[] = fgetcsv($this->_fileHandle);
        }
        
        if(!empty($this->_dataArray))
        {
            return $this->_dataArray;
        }
    }
    
    public function CloseFile()
    {
        fclose($this->_fileHandle);
    }
    
    public function GetColumnsNames()
    {
        return $this->_dataArray[0];
    }
    
    public function GetData()
    {
        return $this->_dataArray;
    }
}