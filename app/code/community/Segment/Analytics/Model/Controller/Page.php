<?php
class Segment_Analytics_Model_Controller_Page extends Segment_Analytics_Model_Controller_Base
{
    public function getBlock($block)
    {    
        $names      = $this->_getCategoryNames();
        $product    = $this->_getCurrentProduct();
        if($names)
        {
            $block->setCategoryNames($names)
            ->setPageTitle('Category Listing')
            ->setFullCategoryName(implode(' -> ',$names));
            return $block;
        }    
        
        if($product)
        {
            $block->setPageName('Product');
            return $block;
        }
        
        $title = Mage::getSingleton('core/layout')->getBlock('head')->getTitle();
        $title = Mage::helper('segment_analytics')->getNormalizedPageTitle($title);
        
        $block->setPageName($title);

        return $block;
    }

    protected function _getCurrentProduct()
    {
        return Mage::registry('product');
    }
    
    protected function _getCategoryPageHandles()
    {
        return array('catalog_category_view');
    }

    protected function _getCategoryNames()
    {        
        
        if(!in_array($this->getFullActionName(), $this->_getCategoryPageHandles()))
        {
            return;
        } 
        
        $current_category = Mage::registry('current_category');        
        $category   = $current_category;
        if(!$current_category)
        {
            return;
        }
        $ids        = array($category->getId());
        $categories = array($category);
        while($parent_id = $category->getParentId())
        {
            $ids[] = $parent_id;
            $category = Mage::getModel('catalog/category')->load($parent_id);
            $categories[] = $category;
        }
        //pop off root
        array_pop($categories);
        $names = array();
        foreach($categories as $category)
        {
            $names[] = $category->getName();
        }
        $names = array_reverse($names);
        return $names;
    }    
    
}