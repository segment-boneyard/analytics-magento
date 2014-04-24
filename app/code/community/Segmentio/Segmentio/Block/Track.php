<?php
/**
 * Segment.io
 *
 * @category    Segment.io Ext
 * @package     Segmentio_Segmentio
 * @author      Segment.io
 * @copyright   Copyright © 2014 Segment.io
 */
class Segmentio_Segmentio_Block_Track extends Mage_Core_Block_Template
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
