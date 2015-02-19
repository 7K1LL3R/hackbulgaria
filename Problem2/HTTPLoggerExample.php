<?php

include 'MyLogger.php';
include 'HTTPLogger.php';
include 'LogLevel.php';

function startLogger(MyLogger $o)
{
    echo $o->log(3, 'Imminent doom!!!');
}

$httpLogger = new HTTPLogger();

startLogger($httpLogger);