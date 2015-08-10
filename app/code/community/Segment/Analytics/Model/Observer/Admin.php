<?php
class Segment_Analytics_Model_Observer_Admin
{
    public function addExplainationTextToConfigScreen($observer)
    {
        $action = $observer->getAction();
        if(!$action)
        {
            return;
        }

        if($action->getRequest()->getParam('section') !== 'segment_analytics')
        {
            return;
        }

        $layout  = Mage::getSingleton('core/layout');
        $content = $layout->getBlock('content');

        if(!$content)
        {
            return;
        }

        $json = new stdClass;
        $json->content = $layout->createBlock('adminhtml/template')
        ->setTemplate('segment_analytics/welcome.phtml')
        ->toHtml();
    }
}