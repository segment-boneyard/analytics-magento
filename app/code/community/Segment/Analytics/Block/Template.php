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
    
    public function renderDataAsJsonObject($key=false)
    {
        $data = $key ? $this->getData($key) : $this->getData();
        return $this->getLayout()->createBlock('segment_analytics/json')
        ->setData($data)
        ->setAsRawObject(true)
        ->toHtml();    
    }
    
    public function getContextJson()
    {
        $renderer = $this->getLayout()->createBlock('segment_analytics/json')
        ->setData(array(
            'library'=> array(
                'name'=>'analytics-magento',
                'version'=>(string) Mage::getConfig()->getNode()->modules->Segment_Analytics->version
        )));       
        return $renderer->toJsonString();
    }

    /**
    * Ensure safe JSON string, even for Magento systems still
    * running on PHP 5.2
    */    
    public function getPropertyAsJavascriptString($prop)
    {
        $data = (string) $this->getData($prop);        
        $data = json_encode($data);      
        $data = preg_replace('%[^ $:"\'a-z>0-9_-]%six','',$data);        
        return $data;
    }
}