<?php
class Segment_Analytics_Model_Observer
{
    const CONTAINER_BLOCKNAME = 'segment_analytics_before_body_end';
    
    public function addContainerBlock($observer)
    {
        #Mage::Log($_SERVER['SCRIPT_URI']);
        #Mage::Log("Start addContainerBlocks");
        $layout = Mage::getSingleton('core/layout');
        if(!$layout)
        {
            Mage::Log("No Layout Object in " . __METHOD__);
            return;
        }
        
        $before_body_end = $layout->getBlock('before_body_end');
        if(!$before_body_end)
        {
            Mage::Log("No before body end in " . __METHOD__);
            return;
        }
        
        if(!Mage::helper('segment_analytics')->isEnabled())
        {
            return;
        }
        
        $container = $layout->createBlock('core/text_list', self::CONTAINER_BLOCKNAME);
        $before_body_end->append($container);
        $front = Segment_Analytics_Model_Front_Controller::getInstance();           
        $blocks = $front->getBlocks();
        // var_dump($blocks);
        foreach($blocks as $block)
        {
            if(!$block) { continue; }
            $items = $block = is_array($block) ? $block : array($block);
            
            foreach($items as $block)
            {
                $container->append($block);
            }
        }
        Mage::Log("Finished addContainerBlocks");
    }

    /**
    * Adds the "always" items
    */    
    public function addFrotnendScripts($observer)
    {
        $layout = $observer->getLayout();
        if(!$layout)
        {
            return;
        }    
        if(!Mage::helper('segment_analytics')->isEnabled())
        {
            return;
        }        
        
        $front = Segment_Analytics_Model_Front_Controller::getInstance();        
        $front->addAction('init');     
        $front->addAction('page');                
    }

    public function loggedIn($observer)    
    {
        $front = Segment_Analytics_Model_Front_Controller::getInstance();            
        $front->addDeferredAction('alias');
        $front->addDeferredAction('identity');
        $front->addDeferredAction('customerloggedin');
    }    
    
    public function loggedOut($observer)    
    {
        $front = Segment_Analytics_Model_Front_Controller::getInstance();            
        $front->addDeferredAction('customerloggedout', array(
            'customer'=>$this->_getCustomerData()
        ));
    }  
    
    public function addToCart($observer)
    {
        $product = $observer->getProduct();
        $front = Segment_Analytics_Model_Front_Controller::getInstance();            
        $front->addDeferredAction('addtocart',
            array('sku'=>$product->getSku())
        );    
    }
    
    public function removeFromCart($observer)
    {
        $product    = $observer->getQuoteItem()->getProduct();
        $front      = Segment_Analytics_Model_Front_Controller::getInstance();            
        $front->addDeferredAction('removefromcart',
            array('sku'=>$product->getSku())
        );    
    }
    
    public function customerRegistered($observer)
    {
        $customer = $observer->getCustomer();
        $front      = Segment_Analytics_Model_Front_Controller::getInstance();            
        $front->addDeferredAction('customerregistered',
            array('customer_id'=>$customer->getEntityId())
        );            
    }
    
    public function loadedSearch($observer)
    {
        $o = $observer->getDataObject();
        if(!$o){return;}
        $front      = Segment_Analytics_Model_Front_Controller::getInstance();            
        $front->addDeferredAction('searchedproducts',
            array('query'=>$o->getQueryText())
        );                    
    }
    
    public function categoryViewForFiltering($observer)
    {
        $action = $observer->getAction();
        if(!$action){ return; }
        
        $request = $action->getRequest();
        if(!$request) { return; }
        
        $params = $request->getParams();
        
        //use presense of "dir" to flag for filtering. 
        //no need for an action handle check
        if(!array_key_exists('dir', $params))
        {
            return;
        }
        
        $front      = Segment_Analytics_Model_Front_Controller::getInstance();            
        $front->addDeferredAction('filteredproducts',
            array('params'=>$params)
        );                    
        
    }
    
    public function productView($observer)
    {
        $action = $observer->getAction();
        if(!$action){ return; }
        
        $request = $action->getRequest();
        if(!$request) { return; }
        
        $params = $request->getParams();
        
        if (!in_array($action->getFullActionName(), array('catalog_product_view')))
        {
            return;
        }    
        
        $front      = Segment_Analytics_Model_Front_Controller::getInstance();            
        $front->addDeferredAction('viewedproduct',
            array('params'=>$params)
        );          
    }
    
    public function favSaved($observer)
    {
        $front  = Segment_Analytics_Model_Front_Controller::getInstance();            
        $item   = $observer->getData('data_object');
        
        if($item->getResourceName() == 'amlist/item')
        {
            $front->addDeferredAction('amlistfav',
                array('product_id'=>$item->getData('product_id'))
            );          
        }
    }
    
    public function reviewView($observer)
    {
        $action = $observer->getAction();
        if(!$action){ return; }
        
        $request = $action->getRequest();
        if(!$request) { return; }
        
        $params = $request->getParams();
        
        if (!in_array($action->getFullActionName(), array('review_product_list')))
        {
            return;
        }
        
        $front      = Segment_Analytics_Model_Front_Controller::getInstance();            
        $front->addDeferredAction('viewedreviews',
            array('params'=>$params)
        );  
        
    }
    
    public function newsletterSubscriber($observer)
    {
        $subscriber = $observer->getDataObject();
        if(!$subscriber->getSubscriberStatus())
        {
            return;
        }

        $front      = Segment_Analytics_Model_Front_Controller::getInstance();            
        $front->addDeferredAction('subscribenewsletter',
            array('subscriber'=>$subscriber->getData())
        );          
    }
    
    public function wishlistAddProduct($observer)
    {
        $product  = $observer->getProduct();
        $wishlist = $observer->getWishlist();
        
        $front      = Segment_Analytics_Model_Front_Controller::getInstance();            
        $front->addDeferredAction('addedtowishlist',
            array('params'=>array('product_id'=>$product->getId()))
        );          

    }

    public function viewedImageFrontendTrack($observer)
    {
        if(!Mage::helper('segment_analytics')->isEnabled())
        {
            return;
        }        
        
        $action = $observer->getAction();
        if(!$action){ return; } 
        
        $layout = Mage::getSingleton('core/layout');
        
        $content = $layout->getBlock('content');
        if(!$content) { return; }
        
        $content->append(
            $layout->createBlock('segment_analytics/template')
            ->setTemplate('segment_analytics/image-frontend.phtml')
        );
    }
    
    public function orderPlaced($observer)
    {
        $front      = Segment_Analytics_Model_Front_Controller::getInstance();            
        $front->addDeferredAction('orderplaced',
            array('params'=>array(
                'order_id'=>$observer->getOrder()->getId(),
                'increment_id'=>$observer->getOrder()->getIncrementId(),
            ))
        );      
    }

    public function logBlockHtml($observer)
    {
        if($observer->getBlock()->getNameInLayout() != self::CONTAINER_BLOCKNAME)
        {
            return;
        }

        Mage::Log($observer->getTransport()->getHtml(), Zend_Log::INFO, 'segment.log');
    }
    
    public function addClickedShareJavascript($observer)
    {
        $action = $observer->getAction();
        if(!$action){ return; }     
        
        if($action->getFullActionName() != 'catalog_product_view')
        {
            return;
        }

        $layout = Mage::getSingleton('core/layout');

        $content = $layout->getBlock('content');
        if(!$content)
        {
            return;
        }
        
        $block = $layout->createBlock('segment_analytics/template')
        ->setTemplate('segment_analytics/share-frontend.phtml');
        
        $content->append($block);    
    }
    
    public function addClickedReviewTabJavascript($observer)
    {
        $action = $observer->getAction();
        if(!$action){ return; }     
        
        if($action->getFullActionName() != 'catalog_product_view')
        {
            return;
        }
        
        $layout = Mage::getSingleton('core/layout');

        $content = $layout->getBlock('content');
        if(!$content)
        {
            return;
        }
        $block = $layout->createBlock('segment_analytics/template')
        ->setTemplate('segment_analytics/review-frontend.phtml');
        
        $content->append($block);
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
    
    protected function _getCustomerData()
    {
        $customer = $this->_getCustomer();
        if($customer)
        {
            $customer = Mage::helper('segment_analytics')->getNormalizedCustomerInformation($customer->getData());
            return $customer;
        }
        return array();
    }
}