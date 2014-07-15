<?php
class Segment_Analytics_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getWriteKey()
    {     
        return Mage::getStoreConfig('segment_analytics/options/write_key');
    }
    
    public function isAdmin()
    {
        return Mage::app()->getStore()->isAdmin();
    }
    
    public function isEnabled()
    {
        return !$this->isAdmin() && $this->getWriteKey();
    }
}