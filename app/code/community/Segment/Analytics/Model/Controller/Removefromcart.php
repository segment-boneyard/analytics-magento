<?php
class Segment_Analytics_Model_Controller_Removefromcart extends Segment_Analytics_Model_Controller_Base
{
    public function getBlock($block)
    {
        $product = $block->getProduct();

        $product   = Mage::helper('segment_analytics')
        ->getNormalizedProductInformation($product);

        $block->setProduct($product);

        return $block;
    }
}