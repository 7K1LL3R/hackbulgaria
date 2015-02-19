<?php

class FileLogger implements MyLogger
{
    public function log($level, $message)
    {
        $levelString = LogLevel::getString($level);
        
        if($levelString != '')
        {
            $output = $levelString . '::' . date(DATE_ISO8601) . '::' . $message . PHP_EOL;
            
            file_put_contents('log.txt', $output, FILE_APPEND | LOCK_EX);
        }
        else 
        {
            return '<script>alert("Invalid log level!");</script>';
        }
    }
}