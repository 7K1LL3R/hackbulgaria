<?php

class TableBuilder
{
    protected $_data = '';
    protected $_html = '';
    
    public function __construct($data)
    {
        $this->_data = $data;
        
        $this->init();
    }
    
    public function init()
    {
        $this->BuildHTML();
    }
    
    private function BuildHTML()
    {
        $html = '';
        $style = '';
        $counter = 0;
        
        $html .= '<table class="data">';
        
        foreach($this->_data as $row)
        {
            if($counter == 0)
            {
                $style = 'header';
                
                $html .= '<tr class="' . $style . '">';
                
                foreach($row as $value)
                {
                    $html .= '<th>' . $value . '</th>';
                }
                
                $html .= '</tr>';
            }
            else 
            {
                if($counter % 2 == 1)
                {
                    $style = 'odd';
                }
                else 
                {
                    $style = 'even';
                }
                
                $html .= '<tr class="' . $style . '">';
                
                foreach($row as $value)
                {
                    $html .= '<td>' . $value . '</td>';
                }
                
                $html .= '</tr>';
            }
            
            $counter++;
        }
        
        $html .= '</table>';
        
        $this->_html = $html;
    }
    
    public function PrintHTML()
    {
        return $this->_html;
    }
}