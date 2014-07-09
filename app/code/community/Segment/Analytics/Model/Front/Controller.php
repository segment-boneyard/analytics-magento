<?php 
/**
* Front Controller for segment actions. Simple
* static class to avoid extra Magento perf. overhead
*/
class Segment_Analytics_Model_Front_Controller
{
    static protected $_instance;
    protected $_actions=array();
    protected $_actionsData=array();
    
    static public function getInstance()
    {
        if(!self::$_instance)
        {
            self::$_instance = new Segment_Analytics_Model_Front_Controller;
        }
        return self::$_instance;
    }
        
    public function addDeferredAction($action, $action_data=array())
    {
        Mage::Log("Adding Deferred Action $action");
        $session        = $this->_getSession();
        $deferred       = $session->getDeferredActions();
        $deferred       = $deferred ? $deferred : array();
        $deferred[]     = $action;
        $session->setDeferredActions($deferred);
        
        $data           = $session->getDeferredActionsData();
        $data           = $data ? $data : array();
        $data[$action]  = $action_data;
        $session->setDeferredActionsData($data);
        return $this;
    }
    
    public function clearDeferredActions()
    {
        Mage::getSingleton('segment_analytics/session')
        ->setDeferredActions(array())
        ->setDeferredActionsData(array());
        Mage::Log("Cleared Deferred Action");
        return $this;
    }
    
    public function getDeferredActions()
    {
        $actions = Mage::getSingleton('segment_analytics/session')
        ->getDeferredActions();
        $actions = $actions ? $actions : array();
        return $actions;
    }
    
    public function getDeferredActionsDataForAction($action)
    {
        
        $data = Mage::getSingleton('segment_analytics/session')
        ->getDeferredActionsData();
        $data = $data ? $data : array();        
        return array_key_exists($action, $data) ? $data[$action] : null;
    }
    
    protected function _getSession()
    {
        return Mage::getSingleton('segment_analytics/session');
    }
    
    public function addAction($action,$data=array())
    {
        $this->_actions[] = $action;
        $this->_actionsData[$action] = $data;
        return $this;
    }
    
    public function getBlocks()
    {
        $blocks[] = array();
        foreach($this->_actions as $action)
        {
            $class = 'Segment_Analytics_Model_Controller_' . ucwords($action);
            $controller = new $class;
            $controller->setName($action)
            ->setData($this->getDeferredActionsDataForAction($action));
            $blocks[$action] = $controller->dispatch();
        }
        
        foreach($this->getDeferredActions() as $action)
        {
            $class = 'Segment_Analytics_Model_Controller_' . ucwords($action);
            $controller = new $class;
            $controller->setName($action)
            ->setData($this->getDeferredActionsDataForAction($action));
            $blocks[$action] = $controller->dispatch();        
        }
        
        //reorder based on sort priority, filter out !blocks
        $blocks_ordered = array();
        $sort_order     = $this->getSortOrder();
        $orders         = array_keys($sort_order);
        sort($orders);
        foreach($orders as $order)
        {
            $action = $sort_order[$order];
            if(!array_key_exists($action, $blocks)) { continue; } 
            $blocks_ordered[$action] = $blocks[$action];
            unset($blocks[$action]);
        }
        
        //now any blocks not included in the sort order
        foreach($blocks as $block)
        {
            $blocks_ordered[] = $block;
        }
        $this->clearDeferredActions();
        
        // var_dump(array_keys($blocks_ordered));

        return $blocks_ordered;
    }
    
    protected function getSortOrder()
    {
        return array(
            10=>'init',
            30=>'alias',            
            20=>'page',            
            40=>'identity',
        );
    }
}