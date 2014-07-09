<?php
class Segment_Analytics_Model_Controller_Alias extends Segment_Analytics_Model_Controller_Base
{
    public function getBlock($block)
    {
        $customer = $this->_getCustomerSession();
        $block->setUserId($customer->getId());                        
        return $block;       
    }

    
}