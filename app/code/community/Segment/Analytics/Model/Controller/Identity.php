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

        $address    = Mage::getModel('customer/address')->load(
            $customer->getDefaultBilling()
        );

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

        if($address)
        {
            $region = Mage::getModel('directory/region')->load($address->getRegionId());
            $street = $address->getStreet();
            $street = implode("\n", $street);
            $block
            ->setData('city',       $address->getCity())
            ->setData('country',    $address->getCountryId())
            ->setData('postalCode', $address->getPostcode())
            ->setData('state',      $region->getCode())
            ->setData('street',     $street);
        }
        $data = $block->getData();
        $data = Mage::helper('segment_analytics')->getNormalizedCustomerInformation($data);
        $block->setData($data);
        return $block;
    }

}