<?php
class Segment_Analytics_Model_Controller_Amlistfav extends Segment_Analytics_Model_Controller_Base
{
    public function getBlock($block)
    {        
        $info   = Mage::getModel('catalog/product_api')->info($block->getProductId());
        $info   = Mage::helper('segment_analytics')
        ->getNormalizedProductInformation($info);        
        $block->setParams($info);
        return $block;
    }
}