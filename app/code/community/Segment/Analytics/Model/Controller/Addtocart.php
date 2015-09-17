<?php
class Segment_Analytics_Model_Controller_Addtocart extends Segment_Analytics_Model_Controller_Base
{
    public function getBlock($block)
    {
        $params = $block->getParams();
        $product   = Mage::getModel('catalog/product_api')
        ->info($params['product_id']);

        $product = Mage::helper('segment_analytics')
        ->getNormalizedProductInformation($product);

        $block->setProduct($product);

        return $block;
    }
}