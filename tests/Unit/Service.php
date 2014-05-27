<?php

class Segment_Test_Service
{
    const CUSTOMER_EMAIL = 'foo@test.com';
    const TEST_PRODUCT_SKU = 'n2610';

    /**
     * Registers a test user if doesn't exist. Otherwise the user is being
     * logged in.
     *
     * @param int $websiteId
     * @return false|Mage_Customer_Model_Customer
     * @throws Exception
     */
    public function userRegister($websiteId)
    {
        /* Create test user if it does not exist */
        $customer = Mage::getModel('customer/customer');
        $password = '111111';
        $email = self::CUSTOMER_EMAIL;
        $customer->setWebsiteId($websiteId);
        $customer->loadByEmail($email);

        if(NULL !== $customer->hasEntityId()) {
            $customer->setEmail($email);
            $customer->setFirstname('Jonathan');
            $customer->setLastname('Davis');
            $customer->setPassword($password);
            try {
                $customer->save();
                $customer->setConfirmation(null);
                $customer->save();
            } catch (Exception $e) {
                throw new Exception('Unable to create test customer account ' . $e->getMessage());
            }
        }

        Mage::getSingleton('customer/session')->loginById($customer->getId());

       return $customer;
    }

    /**
     * Creates a test order
     *
     * @return int
     * @throws Exception
     */
    public function createTestOrder()
    {
        $product = $this->getTestProduct();

        if (false == $product->hasEntityId()) {
            throw new Exception('Cannot add product to cart. Check the sample data of your store');
        }

        $customer = $this->userRegister(Mage::app('default')->getWebsite()->getId());
        $addressData = array(
            'firstname'  => $customer->getFirstname(),
            'lastname'   => $customer->getLastname(),
            'address'    => 'No address',
            'street'     => 'No street',
            'city'       => 'No city',
            'postcode'   => '29000',
            'telephone'  => '696969696',
            'country_id' => 'US',
            'region_id'  => 'CA'
        );

        /** @var Mage_Sales_Model_Quote $quote */
        $quote = Mage::getModel('sales/quote')->setStoreId(Mage::app('default')->getStore('default')->getId());
        $quote->assignCustomer($customer);

        $billingAddress = $quote->getBillingAddress()->addData($addressData);
        $shippingAddress = $quote->getShippingAddress()->addData($addressData);
        $shippingAddress->setCollectShippingRates(true)->collectShippingRates()
            ->setShippingMethod('flatrate_flatrate')->setPaymentMethod('checkmo');
        $quote->getPayment()->importData(array('method' => 'checkmo'));

        $buyInfo = array(
            'qty' => 1
        );

        $quote->addProduct($product, new Varien_Object($buyInfo));

        try {
            $quote->collectTotals()->save();
            $service = Mage::getModel('sales/service_quote', $quote);
            $service->submitAll();
            /** @var Mage_Sales_Model_Order $order */
            $order = $service->getOrder();
            $order->save();

            return $order;
        } catch (Exception $e) {
            throw new Exception('Cannot create a test order. ' . $e->getMessage());
        }
    }

    /**
     * Returns the test product object. Creates the product if does not exist
     *
     * @return false|Mage_Catalog_Model_Product
     * @throws Exception
     */
    public function getTestProduct()
    {
        $productId = Mage::getModel('catalog/product')->getIdBySku($this->getTestProductSku());
        if ($productId > 0) {
            return $product = Mage::getModel('catalog/product')->load($productId);
        } else {
            try{
                Mage::app('default');
                Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
                $productData = array(
                    'name' => "Nokia 2610 Phone",
                    'sku' => $this->getTestProductSku(),
                    'description' => "Nokia 2610 Phone",
                    'short_description' => "Nokia 2610 Phone",
                    'weight' => 3.2000,
                    'status' => 1,
                    'visibility' => '4',
                    'attribute_set_id' => 4,
                    'type_id' => 'simple',
                    'price' => 149.99,
                    'tax_class_id' => 2,
                    'page_layout' => 'one_column',
                );

                $product = Mage::getModel('catalog/product');
                $product->addData($productData);
                $product->setWebsiteIds(array(1));

                $product->setStockData(array(
                    'is_in_stock' => 1,
                    'qty' => 200,
                ));                

                /* Create the test category if doesn't exist */
                Mage::register("isSecureArea", 1);
                $category = Mage::getModel('catalog/category')->loadByAttribute('name', 'Cell Phones');
                if (false == is_object($category)) {
                    $category = Mage::getModel('catalog/category');
                    $category->setName('Cell Phones');
                    $category->setUrlKey('cell-phones');
                    $category->setIsActive(1);
                    $category->setDisplayMode('PRODUCTS');
                    $category->setIsAnchor(1);
                    $category->setStoreId(1);

                    $rootCatId = Mage::app()->getWebsite(1)->getDefaultStore()->getRootCategoryId();
                    $parentCategory = Mage::getModel('catalog/category')->load($rootCatId);
                    $category->setPath($parentCategory->getPath());
                    $category->save();
                    $categoryId = $category->getId();                    
                }
                $product->setCategoryIds(array($category->getId()));
                $product->save();
                Mage::app()->setCurrentStore(1);

                /* Fix for wrong stock item after adding to the shopping cart */
                $product = Mage::getModel('catalog/product')->load($product->getId());

                return $product;

            } catch(Exception $e){
                throw new Exception('Cannot create test product ' . $e);
            }
        }
    }

    /**
     * Returns the test product sku
     *
     * @return string
     */
    public function getTestProductSku()
    {
        return self::TEST_PRODUCT_SKU;
    }
}