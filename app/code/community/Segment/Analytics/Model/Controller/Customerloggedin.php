<?php
class Segment_Analytics_Model_Controller_Customerloggedin extends Segment_Analytics_Model_Controller_Base
{
    public function getBlock($block)
    {
        $customer = $this->_getCustomer();
        $block->setUserId($customer->getId());
        
        $data = $block->getData();        
        $data = Mage::helper('segment_analytics')->getNormalizedCustomerInformation($data);
        $block->setData($data);
        
        return $block;
    }
}