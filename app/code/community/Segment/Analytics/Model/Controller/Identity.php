<?php
class Segment_Analytics_Model_Controller_Identity extends Segment_Analytics_Model_Controller_Base
{
    public function getBlock($block)
    {
        $customer = $this->_getCustomer();

        if(!$customer->getId())
        {
            return false;
        }
        
        $block->setUserId($customer->getId())
        ->setFullName($customer->getName())
        ->setName($customer->getName())
        ->setEmail($customer->getEmail())        
        ->setGroupId($customer->getGroupId())
        ->setTaxClassId($customer->getTaxClassId())
        ->setSharedStoreIds((array)$customer->getSharedStoreIds())
        ->setSharedWebsiteIds((array)$customer->getSharedWebsiteIds())
        ->addData($customer->getData())
        ->unsetData('password_hash');             
        
        return $block;
    }
    
}