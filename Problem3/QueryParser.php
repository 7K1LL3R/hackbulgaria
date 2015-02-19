<?php

class QueryParser
{
    protected $_query = '';
    protected $_csvInstance = null;
    protected $_queryColumns = array();
    protected $_limit = 0;
    protected $_sumColumn = array();
    protected $_search = '';
    protected $_errorMessages = array();

    public function __construct($query)
    {
        $this->_query = $query;
        
        $this->init();
    }
    
    private function init()
    {
        if($this->_query == '')
        {
            $this->_errorMessages[] = 'No query to parse!';
        }
        
        $this->_csvInstance = CSVParser::getInstance();
    }
    
    public function ParseQuery()
    {
        if($this->CheckKeyword('SELECT'))
        {
            if($this->CheckKeyword('LIMIT'))
            {
                $this->FindQueryColumns('SELECT', 'LIMIT');
                
                $this->FindLimit('LIMIT');
            }
            else
            {
                $this->FindQueryColumns('SELECT');
            }
            
            if(!empty($this->_queryColumns))
            {
                $proceed = true;
                
                foreach($this->_queryColumns as $queryColumn)
                {
                    if(!in_array($queryColumn, $this->_csvInstance->GetColumnsNames()))
                    {
                        $this->_errorMessages[] = 'Non-existent column name!';
                        
                        $proceed = false;
                    }
                }
                
                if($proceed)
                {
                    $data = $this->_csvInstance->GetData();
                    
                    // Get the difference between the arrays to find which columns NOT to return
                    $diffColumns = array_diff($this->_csvInstance->GetColumnsNames(), $this->_queryColumns);
                    
                    foreach($diffColumns as $key => $value)
                    {
                        foreach($data as &$row)
                        {
                            unset($row[$key]);
                        }
                    }
                    
                    if($this->_limit > 0)
                    {
                        $data = array_slice($data, 0, $this->_limit + 1);
                    }
                    
                    return $data;
                }
            }
        }
        else if($this->CheckKeyword('SUM'))
        {
            $this->FindSum('SUM');
            
            if(!empty($this->_sumColumn))
            {
                $sum = 0;
                $count = 0;
                $proceed = true;
                
                $data = $this->_csvInstance->GetData();
                
                foreach($data as $row)
                {
                    if($count > 0)
                    {
                        if(is_numeric($row[$this->_sumColumn[0]]))
                        {
                            $sum += (int) $row[$this->_sumColumn[0]];
                        }
                        else 
                        {
                            $proceed = false;
                        }
                    }
                    
                    $count++;
                }
                
                if($proceed)
                {
                    return array(array($this->_sumColumn[1]), array($sum));
                }
                else 
                {
                    $this->_errorMessages[] = 'SUM column type is not an integer!';
                }
            }
        }
        else if($this->CheckKeyword('SHOW'))
        {
            return array($this->_csvInstance->GetColumnsNames());
        }
        else if($this->CheckKeyword('FIND'))
        {
            $this->FindSearch('FIND');
            
            if($this->_search != '')
            {
                $count = 0;
                $foundRows = array();
                $result = array();

                $data = $this->_csvInstance->GetData();

                foreach($data as $row)
                {
                    if($count > 0)
                    {
                        if(is_numeric($this->_search))
                        {
                            if(in_array($this->_search, $row))
                            {
                                $foundRows[] = $count;
                            }
                        }
                        else 
                        {
                            foreach($row as $value)
                            {
                                if(stripos($value, $this->_search) !== false)
                                {
                                    $foundRows[] = $count;
                                }
                            }
                        }
                    }

                    $count++;
                }
                
                // Remove duplicates from array
                $foundRows = array_unique($foundRows);

                $result[] = $data[0];

                foreach($foundRows as $foundRow)
                {
                    $result[] = $data[$foundRow];
                }

                return $result;
            }
        }
        else 
        {
            $this->_errorMessages[] = 'Invalid syntax!';
        }
    }
    
    private function FindQueryColumns($start, $end = null)
    {
        $result = '';

        $startPos = strpos($this->_query, $start);

        if($startPos === false)
        {
            return $result;
        }

        $startPos += strlen($start);

        if(isset($end))
        {
            $length = strpos($this->_query, $end, $startPos) - $startPos;

            $result = substr($this->_query, $startPos, $length);
        }
        else 
        {
            $result = substr($this->_query, $startPos);
        }
        
        $resultArray = explode(',', $result);

        $this->_queryColumns = array_map('trim', $resultArray);
    }
    
    private function FindLimit($keyword = 'LIMIT')
    {
        $limit = substr($this->_query, strpos($this->_query, $keyword) + strlen($keyword));
        
        if((int) trim($limit) > 0)
        {
            $this->_limit = $limit;
        }
        else 
        {
            $this->_errorMessages[] = 'Invalid LIMIT value!';
        }
    }
    
    private function FindSum($keyword = 'SUM')
    {
        $sumColumn = trim(substr($this->_query, strpos($this->_query, $keyword) + strlen($keyword)));
        
        if(in_array($sumColumn, $this->_csvInstance->GetColumnsNames()))
        {
            foreach($this->_csvInstance->GetColumnsNames() as $key => $value)
            {
                if($sumColumn === $value)
                {
                    $this->_sumColumn = array($key, $sumColumn);
                }
            }
        }
        else 
        {
            $this->_errorMessages[] = 'Non-existent column name in SUM query!';
        }
    }
    
    private function FindSearch($keyword = 'FIND')
    {
        $search = trim(substr($this->_query, strpos($this->_query, $keyword) + strlen($keyword)), ' \'\"');
        
        $this->_search = $search;
    }
    
    private function CheckKeyword($keyword)
    {
        $startPos = strpos($this->_query, $keyword);
        
        if($startPos !== false)
        {
            if(($startPos + strlen($keyword) !== strlen($this->_query)) && (substr($this->_query, $startPos + strlen($keyword), 1) != ' '))
            {
                return false;
            }
            
            return true;
        }
        else 
        {
            return false;
        }
    }
    
    public function GetErrorMessages()
    {
        return $this->_errorMessages;
    }
}
