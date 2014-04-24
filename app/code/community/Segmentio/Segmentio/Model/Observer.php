<?php
/**
 * Segment.io
 *
 * @category    Segment.io Ext
 * @package     Segmentio_Segmentio
 * @author      Segment.io
 * @copyright   Copyright © 2014 Segment.io
 */

class Segmentio_Segmentio_Model_Observer
{
    /**
     * Add data to session after add product in to cart
     *
     * @param $observer
     */
    public function addToCartAfter($observer)
    {
        $product = $observer->getQuoteItem()->getProduct();
        $categories = $product->getCategoryIds();
        $categoryName =  Mage::getModel('catalog/category')->load($categories[0])->getName();

        $addedProduct = array(
            'id' => $product->getId(),
            'sku' => $product->getSku(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'category' => $categoryName,
            'event' => 'Added Product'
        );

        Mage::getSingleton('customer/session')->setData('addedToCart', $addedProduct);
    }

    /**
     * Add data to session after remove product from cart
     *
     * @param $observer
     */
    public function deleteFromCartAfter($observer)
    {
        $product = $observer->getQuoteItem()->getProduct();

        $categories = $product->getCategoryIds();
        $categoryName =  Mage::getModel('catalog/category')->load($categories[0])->getName();

        $deletedProduct = array(
            'id' => $product->getId(),
            'sku' => $product->getSku(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'category' => $categoryName,
            'event' => 'Removed Product'
        );

        Mage::getSingleton('customer/session')->setData('deletedFromCart', $deletedProduct);
    }

    /**
     * Add data to session after add product to wishlist
     *
     * @param $observer
     */
    public function wishlistFromCartAfter($observer)
    {
        $product = $observer->getProduct();

        $categories = $product->getCategoryIds();
        $categoryName =  Mage::getModel('catalog/category')->load($categories[0])->getName();

        $addWishlistProduct = array(
            'id' => $product->getId(),
            'sku' => $product->getSku(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'category' => $categoryName,
            'event' => 'Added Product to Wishlist'
        );

        Mage::getSingleton('customer/session')->setData('addWishlistProduct', $addWishlistProduct);
    }

    /**
     * Add data to session after review product
     *
     * @param $observer
     */
    public function reviewProductAfter($observer)
    {
        $productId = $observer->getObject()->getEntityPkValue();
        $product = Mage::getModel('catalog/product')->load($productId);

        $categories = $product->getCategoryIds();
        $categoryName =  Mage::getModel('catalog/category')->load($categories[0])->getName();

        $reviewProduct = array(
            'id' => $product->getId(),
            'sku' => $product->getSku(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'category' => $categoryName,
            'event' => 'Reviewed Product'
        );

        Mage::getSingleton('customer/session')->setData('reviewProduct', $reviewProduct);
    }

}