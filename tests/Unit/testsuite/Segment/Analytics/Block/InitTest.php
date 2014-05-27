<?php

class Segment_Analytics_Block_InitTest extends PHPUnit_Framework_TestCase
{
    const CUSTOMER_EMAIL = 'foo@test.com';

    /** @var  Segment_Analytics_Block_Init */
    protected $_block;
    protected $_app;
    protected $_layout;
    /** @var  Segment_Test_Service */
    protected $_service;

    public function setUp()
    {
        $this->_app = Mage::app('default');
        $this->_layout = $this->_app->getLayout();
        $this->_block = new Segment_Analytics_Block_Init();
        $this->_block->setLayout($this->_layout);
        $this->_service = new Segment_Test_Service();
    }

    public function testGetUserData()
    {
        /* Customer is not logged in */
        Mage::getSingleton('customer/session')->logout();
        $userData = $this->_block->getUserData();
        $this->assertEquals(NULL, $userData, 'No data for non-logged in customer');

        /* Customer is logged in */
        $customer = $this->_service->userRegister($this->_app->getWebsite()->getId());
        $userData = $this->_block->getUserData();
        $this->assertTrue((is_array($userData)), 'Get array of user data');
        $this->assertTrue((count($userData) > 0), 'The array of user data is not empty');
    }

    public function testGetUserId()
    {
        /* Customer is not logged in */
        Mage::getSingleton('customer/session')->logout();
        $userId = $this->_block->getUserId();
        $this->assertEquals(NULL, $userId, 'User is not logged in. No user id should be returned');

        /* Customer is logged in */
        $this->_service->userRegister($this->_app->getWebsite()->getId());
        $userId = $this->_block->getUserId();
        $this->assertTrue(($userId > 0), 'User id has been fetched');
    }

    public function testgetCustomerData()
    {
        /* Customer is not logged in */
        Mage::getSingleton('customer/session')->logout();
        $customerData = $this->_block->getCustomerData();
        $this->assertEquals(NULL, $customerData, 'User is not logged in, no customer data should be returned');

        /* Customer is logged in */
        $customer = $this->_service->userRegister($this->_app->getWebsite()->getId());
        $customerData = $this->_block->getCustomerData();
        $this->assertTrue((is_a($customerData, 'Mage_Customer_Model_Customer')), 'A customer model should be returned');
    }
}