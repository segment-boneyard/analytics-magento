<?php
/**
 * Segment.io
 *
 * @category    Segment.io Ext
 * @package     Segmentio_Segmentio
 * @author      Segment.io
 * @copyright   Copyright © 2014 Segment.io
 */

class Segmentio_Segmentio_Block_Init extends Mage_Core_Block_Template
{
    /**
     * Get user data for identity user
     *
     * @return array
     */
    public function getUserData()
    {
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customerData = Mage::getSingleton('customer/session')->getCustomer();
            $userData = array(
                'customerId' => $customerData->getId(),
                'customerEmail' => $customerData->getEmail(),
                'customerFirstname' => $customerData->getFirstname(),
                'customerLastname' => $customerData->getLastname()
            );
            $defaultBilling = Mage::getSingleton('customer/session')->getCustomer()->getDefaultBilling();
            $customerAddress = Mage::getModel('customer/address')->load($defaultBilling);
            if ($customerAddress) {
                if ($customerAddress->getIsActive()) {
                    $userDataAddress = array(
                        'customerAddressCity' => $customerAddress->getCity(),
                        'customerAddressRegion' => $customerAddress->getRegion(),
                        'customerAddressCountry' => Mage::getModel('directory/country')
                            ->loadByCode($customerAddress->getCountry())->getName()
                    );
                } else {
                    $userDataAddress = array(
                        'customerAddressCity' => 'not detected',
                        'customerAddressRegion' => 'not detected',
                        'customerAddressCountry' => 'not detected'
                    );
                }
            }

            return array_merge($userData, $userDataAddress);
        }

        return NULL;
    }
}
