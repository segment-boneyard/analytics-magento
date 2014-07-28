<?php
class Segment_Analytics_Model_Controller_Layerednavfilter extends Segment_Analytics_Model_Controller_Base
{
    public function getBlock($block)
    {
        $params = $block->getParams();
        
        $params['category'] = Mage::helper('segment_analytics')
        ->getCategoryNamesFromIds($params['request']['id']);
        $block->setParams($params);
        
        return $block;
    }
}