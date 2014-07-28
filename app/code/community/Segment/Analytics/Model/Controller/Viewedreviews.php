<?php
class Segment_Analytics_Model_Controller_Viewedreviews extends Segment_Analytics_Model_Controller_Base
{
    public function getBlock($block)
    {
        $params = $block->getParams();
        
        $info   = Mage::getModel('catalog/product_api')
        ->info($params['id']);
        
        $info   = Mage::helper('segment_analytics')
        ->getNormalizedProductInformation($info); 
        
        $want = array('sku', 'price', 'name');
        foreach($info as $key=>$value)
        {
            if(!in_array($key, $want)){continue;}
            $params[$key] = $value;
        }
        
        $block->setParams($params);
        return $block;
    }
}