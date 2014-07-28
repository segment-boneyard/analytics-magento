<?php
class Segment_Analytics_Model_Observer_Layered
{
    static $_isFiltered;
    public function flagIsFiltered($observer)
    {
        self::$_isFiltered = true;
    }

    public function addLayeredLimitation($observer)
    {
        if(!self::$_isFiltered) { return; }
        
        $action = $observer->getAction();
        if(!$action){ return; }    
        
        if (!in_array($action->getFullActionName(), array('catalog_category_view')))
        {
            return;
        }        
        
        $front      = Segment_Analytics_Model_Front_Controller::getInstance();            
        $front->addDeferredAction('layerednavfilter',
            array('params'=>array('request'=>Mage::app()->getRequest()->getParams()))
        );          
    }
    
}
