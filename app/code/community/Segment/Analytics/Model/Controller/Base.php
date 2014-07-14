<?php
abstract class Segment_Analytics_Model_Controller_Base
{
    protected $_name;
    protected $_data;
    abstract public function getBlock($block);        
    
    public function dispatch()
    {
        $block = $this->_getDefaultBlock();
        return $this->getBlock($block);
    }
    
    protected function _getDefaultBlock()
    {
        $key = Mage::helper('segment_analytics')->getWriteKey();    
        $block = $this->getLayout()->createBlock('segment_analytics/template')
        ->setTemplate('segment_analytics/'.$this->getName().'.phtml')
        ->setKey($key);
        if(is_array($this->getData()))
        {
            $block->addData($this->getData());
        }
        return $block;
    }
    
    public function setData($value)
    {
        $this->_data = $value;
        return $this;
    }

    public function getData()
    {
        return $this->_data;
    }
    
    public function setName($value)
    {
        $this->_name = $value;
        return $this;
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function getLayout()
    {
        return Mage::getSingleton('core/layout');
    }
    
    public function getFullActionName($delimiter='_')
    {
        return $this->getRequest()->getRequestedRouteName().$delimiter.
            $this->getRequest()->getRequestedControllerName().$delimiter.
            $this->getRequest()->getRequestedActionName();
    }
    
    public function getRequest()
    {
        return Mage::app()->getRequest();
    }    
    
    
    protected function _getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }
    
    protected function _getCustomer()
    {
        $customer       = Mage::getSingleton('customer/session')->getCustomer();            
        
        //pull entire customer, including eav attributes not initially populated
        $full_customer = Mage::getModel('customer/customer')->getCollection()
        ->addAttributeToSelect('*')->addFieldToFilter('entity_id', $customer->getId())
        ->getFirstItem();
        return $full_customer;
    }  
}