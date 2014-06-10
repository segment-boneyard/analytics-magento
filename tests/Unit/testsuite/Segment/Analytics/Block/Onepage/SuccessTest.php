<?php

class Segment_Analytics_Block_Onepage_SuccessTest extends PHPUnit_Framework_TestCase
{
    const CUSTOMER_EMAIL = 'foo@test.com';

    /** @var  Segment_Analytics_Block_Onepage_Success */
    protected $_block;
    protected $_app;
    protected $_layout;
    /** @var  Segment_Test_Service */
    protected $_service;
    /** @var  Mage_Sales_Model_Order */
    protected $_order;
    protected $_ordersCollection;

    public function setUp()
    {
        $this->_app = Mage::app('default');
        $this->_layout = $this->_app->getLayout();
        $this->_block = new Segment_Analytics_Block_Onepage_Success();
        $this->_block->setLayout($this->_layout);
        $this->_service = new Segment_Test_Service();

        /* Create a test order */

         $this->_order = $this->_service->createTestOrder();
    }

    public function testGetOrderIds()
    {
        $orderId = $this->_block->getOrderIds($this->_order->getIncrementId());
        $this->assertEquals($this->_order->getEntityId(), $orderId, 'Correct order id should be returned');
    }

    public function testGetOrderCollection()
    {
        $this->_ordersCollection = $this->_block->getOrderCollection(array($this->_order->getEntityId()));
        $this->assertTrue(is_object($this->_ordersCollection), 'Orders collection should be returned');
        $this->assertTrue($this->_ordersCollection->getSize() > 0);
    }

    public function testGetOrderInfo()
    {
        $this->_ordersCollection = $this->_block->getOrderCollection(array($this->_order->getEntityId()));
        $orderInfo = $this->_block->getOrderInfo($this->_ordersCollection);
        $this->assertEquals($this->_order->getEntityId(), $orderInfo['orderInfo']['id'], 'Order info should be correct');
        $this->assertEquals($this->_service->getTestProductSku(), $orderInfo['productItem'][0]['sku'],
            'Product info shold be correct');
    }

    public function testGetOrderId()
    {
        Mage::getModel('checkout/session')->setLastOrderId($this->_order->getEntityId());
        $orderId = $this->_block->getOrderId();
        $this->assertEquals($this->_order->getEntityId(), $orderId, 'Order id should be correct');
    }
}