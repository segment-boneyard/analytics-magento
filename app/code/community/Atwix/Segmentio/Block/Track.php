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
class Atwix_Segmentio_Block_Track extends Mage_Core_Block_Template
{
    /**
     * Get session data for events track
     *
     * @return array
     */
    public function getTrackSession()
    {
        $dataArray = array(
            'addedToCart' => Mage::getSingleton('customer/session')->getData('addedToCart'),
            'deletedFromCart' => Mage::getSingleton('customer/session')->getData('deletedFromCart'),
            'addWishlistProduct' => Mage::getSingleton('customer/session')->getData('addWishlistProduct'),
            'reviewProduct' => Mage::getSingleton('customer/session')->getData('reviewProduct')
        );
        return $dataArray;
    }

    /**
     * Unset session data after track
     *
     * @param string $key
     */
    public function unsetSessionData($key)
    {
        Mage::getSingleton('customer/session')->unsetData($key);
    }
}
