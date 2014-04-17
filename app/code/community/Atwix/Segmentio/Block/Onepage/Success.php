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

class Atwix_Segmentio_Block_Onepage_Success extends Mage_Checkout_Block_Onepage_Success
{
    /**
     * Return order id by order increment id
     *
     * @param $orderIncriment
     * @return mixed
     */
    public function getOrderIds($orderIncriment)
    {
        $orderId = Mage::getModel('sales/order')->loadByIncrementId($orderIncriment)->getEntityId();
        return $orderId;
    }

    /**
     * Return order collection
     *
     * @param $orderIds
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getOrderCollection($orderIds)
    {
        $collection = Mage::getResourceModel('sales/order_collection')
            ->addFieldToFilter('entity_id', array('in' => $orderIds));

        return $collection;
    }

    /**
     * Return order info for 'Completed Order' track
     *
     * @param $collection
     * @return array
     */
    public function getOrderInfo($collection)
    {
        foreach ($collection as $order) {

            /* Add order information */
            $orderInformation[] = sprintf(
                "id: '%s',
                incrementId: '%s',
                created: '%s',
                grand_total: '%s',
                subtotal: '%s',
                shipping_description: '%s',
                shipping: '%s',
                discount: '%s',
                tax: '%s',
                ",
                $order->getEntityId(),
                $order->getIncrementId(),
                $order->getCreatedAt(),
                $order->getGrandTotal(),
                $order->getSubtotal(),
                $order->getShippingDescription(),
                $order->getShippingAmount(),
                $order->getDiscountAmount(),
                $order->getTaxAmount()
            );

            /* Add pusrchased items info */
            foreach ($order->getAllVisibleItems() as $item) {
                $productItems[] = sprintf(
                    "{id: '%s',
                     sku: '%s',
                     name: '%s',
                     price: '%s',
                     category: '%s'
                     },",
                    $item->getProductId(),
                    $item->getSku(),
                    $item->getName(),
                    $item->getPrice(),
                    Mage::helper('atwix_segmentio')->getCategoryItemProduct($item->getProductId())
                );
            }
            $result = array('orderInfo'=>$orderInformation, 'productItem' => $productItems);
            return $result;
        }
    }

    /**
     * Retrieve identifier of created order
     *
     * @return int
     */
    public function getOrderId()
    {
        return Mage::getSingleton('checkout/session')->getLastOrderId();
    }
}
