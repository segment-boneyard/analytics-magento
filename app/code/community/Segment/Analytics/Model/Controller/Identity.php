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

        $gender_label = $customer->getResource()
        ->getAttribute('gender')
        ->getSource()
        ->getOptionText($customer->getData('gender'));
        
        $block->setUserId($customer->getId())
        ->addData($customer->getData())
        ->setFullName($customer->getName())
        ->setName($customer->getName())
        ->setEmail($customer->getEmail())        
        ->setGroupId($customer->getGroupId())
        ->setTaxClassId($customer->getTaxClassId())
        ->setSharedStoreIds((array)$customer->getSharedStoreIds())
        ->setSharedWebsiteIds((array)$customer->getSharedWebsiteIds())
        ->setGender($gender_label)
        ->setFirstName($customer->getFirstname())
        ->setLastName($customer->getLastname())
        ->setMiddleName($customer->getMiddlename())        
        ->setTotalOrders(
            Mage::getSingleton('segment_analytics/query_totalpurchased')
            ->fetchTotalOrders($customer->getId())
        )
        ->setTotalSpent(
            Mage::getSingleton('segment_analytics/query_totalspent')
            ->fetchTotalSpent($customer->getId())
        )
        ->unsetData('password_hash')
        ->unsetData('firstname')
        ->unsetData('lastname')
        ->unsetData('middlename');
        
        $data = $block->getData();        
        $data = Mage::helper('segment_analytics')->getNormalizedCustomerInformation($data);
        $block->setData($data);
        return $block;
    }
    
}