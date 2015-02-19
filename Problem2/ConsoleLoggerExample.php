<?php

include 'MyLogger.php';
include 'ConsoleLogger.php';
include 'LogLevel.php';

function startLogger(MyLogger $o)
{
    echo $o->log(1, 'Hello World');
}

$consoleLogger = new ConsoleLogger();

startLogger($consoleLogger);