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
}