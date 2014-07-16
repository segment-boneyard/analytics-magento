<?php
class Segment_Analytics_Block_Json extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        $var    = $this->getVarName();
        $json   = $this->toJsonString();
        
        if($this->getAsRawObject())
        {
            return $json;    
        }
        
        return '<script type="text/javascript">' . 
        '    var ' . $var . " = " . $json . ";\n" .         
        '</script>';
    }
    
    public function toJsonString($data=false)
    {
        $data = $data ? $data : $this->getDataWithoutExtras();
        $json   = Mage::helper("core")->jsonEncode($data);
        $json   = preg_replace('%[\r\n]%','',$json);
        return $json;                
    }
    
    public function getDataWithoutExtras()
    {
        $data = $this->getData();
        unset($data['type']);
        unset($data['module_name']);
        unset($data['var_name']);
        unset($data['as_raw_object']);
        return $data;
    }
}

