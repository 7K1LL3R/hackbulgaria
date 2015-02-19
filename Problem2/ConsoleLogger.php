<?php

class ConsoleLogger implements MyLogger
{
    public function log($level, $message)
    {
        $levelString = LogLevel::getString($level);
        
        if($levelString != '')
        {
            $output = '<script>console.log("' . $levelString . '::' . date(DATE_ISO8601) . '::' . $message . '");</script>';
            
            return $output;
        }
        else 
        {
            return '<script>alert("Invalid log level!");</script>';
        }
    }
}