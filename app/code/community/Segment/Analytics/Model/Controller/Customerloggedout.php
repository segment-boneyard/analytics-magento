<?php
class Segment_Analytics_Model_Controller_Customerloggedout extends Segment_Analytics_Model_Controller_Base
{
    public function getBlock($block)
    {
        $customer = $block->getCustomer();                
        foreach(array('password_hash') as $key)
        {
            if(!array_key_exists($key, $customer)) { continue; }
            unset($customer[$key]);
        }
        $customer = Mage::helper('segment_analytics')->getNormalizedCustomerInformation($customer);
        $block->setUserId($customer['entity_id']);   
        
        return $block;
    }
}