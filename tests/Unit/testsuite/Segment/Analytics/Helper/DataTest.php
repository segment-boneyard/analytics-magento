<?php

class Segment_Analytics_Helper_DataTest extends PHPUnit_Framework_TestCase
{
    protected $_app;
    /** @var  Segment_Test_Service */
    protected $_service;
    /** @var  Segment_Analytics_Helper_Data */
    protected $_helper;
    public function setUp()
    {
        $this->_app = Mage::app('default');
        $this->_helper = Mage::helper('segment_analytics');
        $this->_service = new Segment_Test_Service();
    }

    public function testGetConfig()
    {
        $config = $this->_helper->getConfig('web/unsecure/base_url', $this->_app->getStore()->getId());
        $this->assertTrue(!empty($config), 'Configuration value should not be empty');
    }

    public function testGetCountry()
    {
        $country = $this->_helper->getCountry();
        $this->assertTrue(!empty($country));
    }

    public function testGetCategoryItemProduct()
    {
        $product = $this->_service->getTestProduct();
        $productId = $product->getId();

        if (false == ($productId > 0)) {
            throw new Exception('Cannot add product to cart. Check the sample data of your store');
        }

        $categoryName = $this->_helper->getCategoryItemProduct($productId);
        $this->assertEquals('Cell Phones', $categoryName);
    }

    public function testGetProductData()
    {
        $product = $this->_service->getTestProduct();
        $productData = $this->_helper->getProductData($product);
        $this->assertEquals('Nokia 2610 Phone', $productData['name'], 'The product name should be Nokia 2610 Phone');

        $emptyProduct = new Varien_Object();
        $productData = $this->_helper->getProductData($emptyProduct);
        $this->assertTrue(($productData === NULL), 'No data should be returned for incorrect product record');
    }
}