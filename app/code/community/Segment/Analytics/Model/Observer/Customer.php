<?php
class Segment_Analytics_Model_Observer_Customer
{
    const PARAM_CUSTOMER_ID = 'segment_customer_id';
    static protected $_customer;
    public function grabCustomerId($observer)
    {
        self::$_customer = $this->_getCustomer();
    }
    
    public function addCustomerIdParamater($observer)
    {
        $action = $observer->getControllerAction();
        $customer = self::$_customer;
        $action->setRedirectWithCookieCheck(
            '*/*/logoutSuccess', array(self::PARAM_CUSTOMER_ID=>$customer->getId())
        );
    }
    
    public function addCustomerTrackingIfIdPresent($observer)
    {
        $customer_id = Mage::app()->getRequest()->getParam(self::PARAM_CUSTOMER_ID);
        if(!$customer_id) { return; }
        
        $front = Segment_Analytics_Model_Front_Controller::getInstance();            
        $front->addDeferredAction('customerloggedout', array(
            'customer'=>$this->_getCustomerData($customer_id)
        ));        
    }
    
    protected function _getCustomerData($id)
    {
        $full_customer = Mage::getModel('customer/customer')->getCollection()
        ->addAttributeToSelect('*')->addFieldToFilter('entity_id', $id)
        ->getFirstItem();
        if($full_customer)
        {
            return $full_customer->getData();    
        }
        return array();
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