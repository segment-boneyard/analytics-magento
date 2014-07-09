<?php
class Segment_Analytics_Helper_Data
{
    public function getSecretKey()
    {
        return '0n1mip8hyd';
    }
    
    public function getAnonUserId()
    {
        return 'anon-' . (md5(session_id() . '::' . (string)Mage::getConfig()->getNode('global/crypt/key')));
    }
    
    public function isAdmin()
    {
        return Mage::app()->getStore()->isAdmin();
    }
}