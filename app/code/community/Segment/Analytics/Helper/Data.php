<?php
class Segment_Analytics_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getWriteKey()
    {     
        return Mage::getStoreConfig('segment_analytics/options/write_key');
    }
    
    public function isAdmin()
    {
        return Mage::app()->getStore()->isAdmin();
    }
    
    public function isEnabled()
    {
        return !$this->isAdmin() && $this->getWriteKey();
    }
    
    public function getCategoryNamesFromIds($ids)
    {
        $ids = is_array($ids) ? $ids : array($ids);
        $categories = Mage::getModel('catalog/category')->getCollection()
        ->addAttributeToSelect('name')
        ->addFieldToFilter('entity_id', array('in'=>$ids));    
        
        $names = array();
        foreach($categories as $category)
        {
            $names[] = $category->getName();       
        }
        return $names;        
    }

    /**
    * Changes standard page titles per segment API.  Hopefully 
    * this is kept to a minimum
    * @todo refactor if this goes beyond page
    */    
    public function getNormalizedPageTitle($title)
    {
        if(strpos($title, $this->__('Search results for')) !== false)
        {
            $title = 'Search Results';
        }
        
        return $title;
    }
    
    public function getNormalizedProductInformation($product)
    {
        //if passed id, load the product
        if(!is_array($product))
        {
            $product = Mage::getModel('catalog/product_api')->info($product);
        }
        
        //calculate revenue, if present 
        $product['id'] = $product['product_id'];
        if(array_key_exists('cost',$product))
        {
            $product['revenue'] = $product['price'] - $product['cost'];
        }        
        
        //ensure category names/labels are sent along
        $categories = Mage::getModel('catalog/category')->getCollection()
        ->addAttributeToSelect('name')
        ->addFieldToFilter('entity_id', array('in'=>$product['category_ids']));    
        foreach($categories as $category)
        {
            $product['categories'][] = $category->getName();            
        }
        
        //cast numerics as floats per segment requirements
        $as_float = array('price','weight');
        foreach($as_float as $key)
        {
            if(!array_key_exists($key, $product)) { continue; }
            $product[$key] = (float) $product[$key];
        }

        //segments wants "id" not product_id
        if(array_key_exists('product_id', $product))       
        {
            $product['id'] = $product['product_id'];
            unset($product['product_id']);
        }
        return $product;
    }
}