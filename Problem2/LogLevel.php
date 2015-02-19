<?php

class LogLevel
{
    public static function getString($level)
    {
        $levelString = '';
        
        switch((int) $level)
        {
            case 1:
                $levelString = 'INFO';
                
                break;
            
            case 2:
                $levelString = 'WARNING';
                
                break;
            
            case 3:
                $levelString = 'PLSCHECKFFS';
                
                break;
            
            default:
                break;
        }
        
        return $levelString;
    }
}