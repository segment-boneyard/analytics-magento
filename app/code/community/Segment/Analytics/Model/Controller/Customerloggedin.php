<?php
class Segment_Analytics_Model_Controller_Customerloggedin extends Segment_Analytics_Model_Controller_Base
{
    public function getBlock($block)
    {
        $customer = $this->_getCustomer();
        $block->setUserId($customer->getId());
        return $block;
    }
}