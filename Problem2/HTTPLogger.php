<?php

class HTTPLogger implements MyLogger
{
    public function log($level, $message)
    {
        $levelString = LogLevel::getString($level);
        
        if($levelString != '')
        {
            $output = $levelString . '::' . date(DATE_ISO8601) . '::' . $message;
            
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, 'http://hackbg.localhost:8080/Problem2/HTTPReceiver.php'); // Change the url !!!
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('data' => $output));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            
            $data = curl_exec($ch);
            
            curl_close($ch);
            
            return '<script>alert("' . $data . '");</script>';
        }
        else 
        {
            return '<script>alert("Invalid log level!");</script>';
        }
    }
}