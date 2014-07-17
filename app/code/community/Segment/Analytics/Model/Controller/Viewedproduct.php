<?php
class Segment_Analytics_Model_Controller_Viewedproduct extends Segment_Analytics_Model_Controller_Base
{
    public function getBlock($block)
    {
        $params = $block->getParams();
        $product = Mage::getModel('catalog/product_api')->info($params['id']);
        
        $product['id'] = $params['id'];
        if(array_key_exists('cost',$product))
        {
            $product['revenue'] = $product['price'] - $product['cost'];
        }        
        $categories = Mage::getModel('catalog/category')->getCollection()
        ->addAttributeToSelect('name')
        ->addFieldToFilter('entity_id', array('in'=>$product['category_ids']));    
        foreach($categories as $category)
        {
            $product['categories'][] = $category->getName();            
        }
                
        $block->setProduct($product);
        return $block;
    }
}