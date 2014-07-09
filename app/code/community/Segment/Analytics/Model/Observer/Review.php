<?php
class Segment_Analytics_Model_Observer_Review
{
    static protected $_review;
    public function markReviewSaved($observer)
    {
        self::$_review = $observer->getObject();
    }
    
    public function reviewedProduct($observer)
    {
        $review = self::$_review;
        if(!$review->getId())
        {   
            return;
        }
        
        $front      = Segment_Analytics_Model_Front_Controller::getInstance();            
        $front->addDeferredAction('reviewedproduct',
            array('review'=>$review->getData())
        );                    
    }
    
    
}