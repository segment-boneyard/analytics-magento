<?php
class Segment_Analytics_Model_Session extends Mage_Core_Model_Session_Abstract
{
    public function __construct()
    {
        if(!session_id())
        {
            Mage::throwException("No session id. Blocking rather than instantiate object early.");
        }        
        $this->init('segment_analytics');
    }
}