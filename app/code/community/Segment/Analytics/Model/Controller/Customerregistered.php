<?php
class Segment_Analytics_Model_Controller_Customerregistered extends Segment_Analytics_Model_Controller_Base
{
    public function getBlock($block)
    {    
        $data = $block->getData();        
        $data = Mage::helper('segment_analytics')->getNormalizedCustomerInformation($data);
        $block->setData($data);    
        return $block;
    }
}