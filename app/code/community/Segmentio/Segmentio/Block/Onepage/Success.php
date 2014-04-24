<?php
/**
 * Segment.io
 *
 * @category    Segment.io Ext
 * @package     Segmentio_Segmentio
 * @author      Segment.io
 * @copyright   Copyright © 2014 Segment.io
 */

class Segmentio_Segmentio_Block_Onepage_Success extends Mage_Checkout_Block_Onepage_Success
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
            $orderInformation = array(
                'id'=>$order->getEntityId(),
                'incrementId'=>$order->getIncrementId(),
                'created'=>$order->getCreatedAt(),
                'grandTotal'=>$order->getGrandTotal(),
                'subtotal'=>$order->getSubtotal(),
                'shippingDescription'=>$order->getShippingDescription(),
                'shipping'=>$order->getShippingAmount(),
                'discount'=>$order->getDiscountAmount(),
                'tax'=>$order->getTaxAmount(),
            );

            /* Add pusrchased items info */
            foreach ($order->getAllVisibleItems() as $item) {
                $productItems[] = array(
                    'id'=>$item->getProductId(),
                    'sku'=>$item->getSku(),
                    'name'=>$item->getName(),
                    'price'=>$item->getPrice(),
                    'category'=>Mage::helper('segmentio_segmentio')->getCategoryItemProduct($item->getProductId())

                );
            }
            $result = array('orderInfo'=>$orderInformation, 'productItem'=>$productItems);
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
