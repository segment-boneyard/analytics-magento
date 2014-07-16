<?php
class Segment_Analytics_Block_Template extends Mage_Core_Block_Template
{
    public function renderDataAsJsonVar($var_name)
    {
        return $this->getLayout()->createBlock('segment_analytics/json')
        ->setData($this->getData())
        ->setVarName($var_name)
        ->toHtml();
    }
    
    public function renderDataAsJsonObject()
    {
        return $this->getLayout()->createBlock('segment_analytics/json')
        ->setData($this->getData())
        ->setVarName($var_name)
        ->setAsRawObject(true)
        ->toHtml();    
    }
    
    public function renderPropertyAsJavascriptSafeStringWithQuotes($prop)
    {        
        $prop = (string) $this->getData($prop);
        $prop .= "'\n";    
        $prop = filter_var($prop,FILTER_UNSAFE_RAW,FILTER_FLAG_STRIP_LOW);  
        $prop = str_replace("'", "\\'", $prop);        
        return "'$prop'";
    }
}