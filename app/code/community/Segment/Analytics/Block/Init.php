<?php
/**
 * Segment.io
 *
 * @category    Segment.io Ext
 * @package     Segment_Analytics
 * @author      Segment.io
 * @copyright   Copyright © 2014 Segment.io
 */

class Segment_Analytics_Block_Init extends Mage_Core_Block_Template
{
    /**
     * Get user data for identity user
     *
     * @return array
     */
    public function getUserData()
    {
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customerData = $this->getCustomerData();
            $userData = array(
                'email' => $customerData->getEmail(),
                'firstName' => $customerData->getFirstname(),
                'lastName' => $customerData->getLastname()
            );
            $defaultBilling = Mage::getSingleton('customer/session')->getCustomer()->getDefaultBilling();
            $customerAddress = Mage::getModel('customer/address')->load($defaultBilling);
            if ($customerAddress) {
                if ($customerAddress->getIsActive()) {
                    $userDataAddress = array( 'address' => $customerAddress->getCity()." ".
                        $customerAddress->getRegion()." ".
                        Mage::getModel('directory/country')->loadByCode($customerAddress->getCountry())->getName()
                    );
                } else {
                    $userDataAddress = array('address'=>'');
                }
            }

            return array_merge($userData, $userDataAddress);
        }

        return NULL;
    }

    /**
     * Get user Id
     *
     * @return string
     */
    public function getUserId()
    {
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customerData = $this->getCustomerData();
            $userId = $customerData->getId();

            return $userId;
        } else {
            return NULL;
        }
    }

    /**
     * Get customer Data if customer logged in
     *
     * @return mixed
     */
    public function getCustomerData()
    {
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customerData = Mage::getSingleton('customer/session')->getCustomer();

            return $customerData;
        }  else {
            return NULL;
        }

    }
}
