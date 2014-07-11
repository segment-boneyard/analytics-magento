<?php
class Segment_Analytics_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getSecretKey()
    {
        
        return Mage::getStoreConfig('segment_analytics/options/key');
        // return '0n1mip8hyd';
    }
    
    public function getAnonUserId()
    {
        return 'anon-' . (md5(session_id() . '::' . (string)Mage::getConfig()->getNode('global/crypt/key')));
    }
    
    public function isAdmin()
    {
        return Mage::app()->getStore()->isAdmin();
    }
    
    public function isEnabled()
    {
        return $this->isAdmin() && $this->getSecretKey();
    }
}