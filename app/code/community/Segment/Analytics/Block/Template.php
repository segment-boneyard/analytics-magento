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
    
    public function getContextJson()
    {
        $renderer = $this->getLayout()->createBlock('segment_analytics/json')
        ->setData(array(
            'library'=> array(
                'name'=>'analytics-magento',
                'version'=>'0.0.1'
        )));
            
//         var_dump();
//         
//         var_dump($renderer);            
        return $renderer->toJsonString();
    }
}