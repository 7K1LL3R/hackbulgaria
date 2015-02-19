<?php

include 'CSVParser.php';
include 'QueryParser.php';
include 'TableBuilder.php';

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $query = $_POST['query'];
    
    $csvParser = CSVParser::getInstance();
    $csvParser->OpenFile('data.csv');
    
    $queryParser = new QueryParser($query);
    
    $data = $queryParser->ParseQuery();
    
    $tableBuilder = new TableBuilder($data);
    
    echo json_encode(array(
                        'html' => $tableBuilder->PrintHTML(), 
                        'errors' => $queryParser->GetErrorMessages()
                    )
            );
}