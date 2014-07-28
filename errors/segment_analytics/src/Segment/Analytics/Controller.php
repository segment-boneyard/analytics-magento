<?php
/**
* Login class to keep things out of the template
*/
$path = dirname(__FILE__) . '/Block/Json/Simulate.php';
require_once realpath($path);
class Segment_Analytics_Controller
{
    public function page503Action()
    {                
        return $this->_prepareBlockForAction('503_action');   
    }
    
    public function page404Action()
    {    
        return $this->_prepareBlockForAction('404_action');
    }
    
    public function pageReportAction($report_id)
    {
        return $this->_prepareBlockForAction('report_action',$report_id);    
    }
    
    protected function _prepareBlockForAction($action,$report_id=false)
    {
        $title = 'Unknown';
        switch($action)
        {
            case '404_action':
                $title = '404 error: Page not found (Store Exception)';
                break;
            case '503_action':
                $title = '503 error: Service Temporarily Unavailable';
                break; 
            case 'report_action':
                $title = 'Error Report Page: ';
                if($report_id)
                {
                    $title .= $report_id;
                }
                break;                 
            default:
                break;
        }
        
        $block = new Segment_Analytics_Block_Json_Simulate;
        $block->setData('template', 
            'app/design/frontend/base/default/template/segment_analytics/init.phtml');    
        echo $block->toHtml();
    
        $block = new Segment_Analytics_Block_Json_Simulate;
        $block->setData('page_name', $title);
        
        $block->setData('template', 
            'app/design/frontend/base/default/template/segment_analytics/page.phtml');    
        return $block;    

    }
    
    /**
    * Need a way to grab base mage directory without
    * fully bootstrapped Magento
    */    
    static public function getBaseDir()
    {
        if(stripos( $_SERVER['REQUEST_URI'], 'report.php') !== false ||
        stripos(    $_SERVER['REQUEST_URI'], '404.php') !== false ||
        stripos(    $_SERVER['REQUEST_URI'], '503.php') !== false
        )
        {
            return '../';
        }
        return '';
    }    
}