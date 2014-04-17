<?php
/**
 * Atwix
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.

 * @category    Atwix Ext
 * @package     Atwix_Segmentio
 * @author      Atwix Core Team
 * @copyright   Copyright (c) 2014 Atwix (http://www.atwix.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Atwix_Segmentio_Block_Init extends Mage_Core_Block_Template
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
