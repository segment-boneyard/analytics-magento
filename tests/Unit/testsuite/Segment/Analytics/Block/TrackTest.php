<?php

class Segment_Analytics_Block_TrackTest extends PHPUnit_Framework_TestCase
{
    /** @var  Segment_Analytics_Block_Track */
    protected $_block;
    protected $_app;
    protected $_layout;
    /** @var  Segment_Test_Service */
    protected $_service;

    public function setUp()
    {
        $this->_app = Mage::app('default');
        $this->_layout = $this->_app->getLayout();
        $this->_block = new Segment_Analytics_Block_Track();
        $this->_block->setLayout($this->_layout);
        $this->_service = new Segment_Test_Service();
    }

    public function testGetTrackSession()
    {
        $customer = $this->_service->userRegister($this->_app->getWebsite()->getId());

        /* Add a product to the shopping cart */

        $product = $this->_service->getTestProduct();

        if (false == $product->hasEntityId()) {
            throw new Exception('Cannot add product to cart. Check the sample data of your store');
        }
        /** @var Mage_Checkout_Model_Cart $cart */
        $cart = Mage::getModel('checkout/cart');

        $cart->init();
        $cart->addProduct($product, array('qty' => 1));
        $cart->save();

        Mage::getSingleton('checkout/session')->setCartWasUpdated(true);

        $trackInfo = $this->_block->getTrackSession();

        $this->assertTrue(($trackInfo['addedToCart']['id'] > 0), 'Track session should contain a product added to the shopping cart');


        /* Remove a products from cart */

        foreach(Mage::getSingleton('checkout/session')->getQuote()->getItemsCollection() as $item)
        {
            Mage::dispatchEvent('sales_quote_remove_item', array('quote_item' => $item));
        }

        $trackInfo = $this->_block->getTrackSession();
        $this->assertTrue(($trackInfo['deletedFromCart']['id'] > 0), 'Track session should contain a product removed from cart');

        /* Add a product to the wishlist */

        Mage::dispatchEvent('wishlist_add_product', array('product' => $product));
        $trackInfo = $this->_block->getTrackSession();


        $this->assertTrue(($trackInfo['addWishlistProduct']['id'] > 0), 'Track session should contain a product added to the wishlist');


        /* Add customer review */
        $storeId = $this->_app->getStore()->getId();

        $_review = Mage::getModel('review/review')
            ->setEntityPkValue($product->getId())
            ->setStatusId(1)
            ->setTitle('Test')
            ->setDetail('Test details')
            ->setEntityId(1)
            ->setStoreId($storeId)
            ->setStores(array($storeId))
            ->setCustomerId($customer->getId())
            ->setNickname($customer->getFirstname())
            ->save();

        $trackInfo = $this->_block->getTrackSession();

        $this->assertTrue(($trackInfo['reviewProduct']['id'] > 0), 'Track session should contain a review info');

    }

    public function testUnsetSessionData()
    {
        $reviewInfo = Mage::getSingleton('customer/session')->getData('reviewProduct');
        $this->assertTrue((count($reviewInfo) > 0), 'Track session should contain a product added to the wishlist');

        $this->_block->unsetSessionData('reviewProduct');

        $this->assertEquals(NULL , Mage::getSingleton('customer/session')->getData('reviewProduct'),
            'Track session should not contain a review info anymore');
    }
}