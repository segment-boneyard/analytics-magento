<?php
class Segment_Analytics_Model_Controller_Reviewedproduct extends Segment_Analytics_Model_Controller_Base
{
    public function getBlock($block)
    {
        $review = $block->getReview();
        unset($review['customer_id']);
        unset($review['form_key']);
        $review = Mage::helper('segment_analytics')->normalizeReviewwData($review);
        $block->setReview($review);
        return $block;
    }
}