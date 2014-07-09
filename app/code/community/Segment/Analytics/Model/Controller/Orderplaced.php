<?php
class Segment_Analytics_Model_Controller_Orderplaced extends Segment_Analytics_Model_Controller_Base
{
    public function getBlock($block)
    {
        $params = $block->getParams();
        
        $info    = Mage::getModel('sales/order_api')
        ->info($params['increment_id']);
        
        $params['total']            = $info['grand_total'];
        $params['status']           = $info['status'];        
        $params['shipping']         = $info['shipping_amount'];
        $params['tax']              = $info['tax_amount'];
        $params['products']         = array();
        foreach($info['items'] as $item)
        {
            $tmp = array();      
            $tmp['sku']           = $item['sku'];
            $tmp['name']          = $item['name'];;
            $tmp['price']         = $item['price'];
            $tmp['quantity']      = $item['qty_ordered'];
            $tmp['product_id']    = $item['product_id'];            

            $params['products'][] = $tmp;
        }

        //too much information?
        //$block->setParams($info);
        
        //the serialized information
        $block->setParams($params);
        
        return $block;
    }
}