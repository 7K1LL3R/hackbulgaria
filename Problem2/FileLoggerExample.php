<?php

include 'MyLogger.php';
include 'FileLogger.php';
include 'LogLevel.php';

function startLogger(MyLogger $o)
{
    echo $o->log(2, 'Insert warning message here.');
}

$fileLogger = new FileLogger();

startLogger($fileLogger);