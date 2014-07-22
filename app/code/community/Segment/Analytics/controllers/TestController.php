<?php
class Segment_Analytics_TestController extends Mage_Core_Controller_Front_Action {
    static protected $_testing = true;
    public function preDispatch()
    {
        if(self::$_testing === false)
        {
            exit;
        }
        return parent::preDispatch();
    }
    
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function storeexceptionAction()
    {
        throw new Mage_Core_Model_Store_Exception('Test'); 
    }
    
    public function reportexceptionAction()
    {
        Mage::throwException('Test');
    }    
    
    public function fiveothreeAction()
    {
        require_once(Mage::getBaseDir() . DS . 'errors' . DS . '503.php');
    }        
}			
