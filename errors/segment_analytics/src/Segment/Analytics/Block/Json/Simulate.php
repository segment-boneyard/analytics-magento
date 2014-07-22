<?php
/**
* Simulating a real Magneto block so we can use phtml
* templates in the errors mini-theme systems
*/
$base = Segment_Analytics_Controller::getBaseDir();
require_once $base . 'lib/Varien/Object.php';
class Segment_Analytics_Block_Json_Simulate extends Varien_Object
{   
    protected $_key=false;
    public function renderDataAsJsonVar($var)
    {
        $json            = new stdClass;
        $json->key       = $this->getSegmentWriteKey();
        $json->page_name = $this->getPageName();
        
        $json       = json_encode($json);
        
        return '<script type="text/javascript">' . 
        '    var ' . $var . " = " . $json . ";\n" .         
        '</script>';            
    }

    /**
    * Loads database credentials from app/etc (since we don't 
    * have a guarentee of a boostrapped Mage enviornment)
    * Globs up configuration just like magento's normal behaviors
    * and uses last file
    */    
    protected function _loadDatabaseCredentialsFromAppEtc()
    {
        $glob  = Segment_Analytics_Controller::getBaseDir() . 'app/etc/*.xml';
        $files = glob($glob);
        $files = array_reverse($files);
        $host = $dbname = $username = $password = '';
        
        //grab database connect information
        foreach($files as $file)
        {
            $xml = simplexml_load_file($file);
            $nodes = $xml->xpath('global/resources/default_setup/connection');
            if(count($nodes) == 0)
            {
                continue;
            }
            $node       = array_shift($nodes);
            $host       = (string) $node->host;
            $dbname     = (string) $node->dbname;
            $username   = (string) $node->username;
            $password   = (string) $node->password;
            
            $host   = (string) $node->host;
            break;
        }
        
        //grab the prefix (seperate loop to account for possible merges)
        foreach($files as $file)
        {
            $xml = simplexml_load_file($file);
            $nodes = $xml->xpath('global/resources/db/table_prefix');
            if(count($nodes) == 0)
            {
                continue;
            }
            $node       = array_shift($nodes);
            $prefix     = (string) (string) $node;
            break;
        }        
        
        return array($host, $dbname, $username, $password, $prefix);
    }
    
    protected function _fetchSegmentKey()
    {
        list($host, $dbname, $username, $password, $prefix) = 
            $this->_loadDatabaseCredentialsFromAppEtc();
                
        //we can't be sure of a bootstrapped Magento here, so we 
        //need to query the database raw to get the segment write key


        $db = new PDO("mysql:host=" . $host . ";dbname=" . $dbname . ";charset=utf8", $username, $password, 
                        array(  PDO::ATTR_EMULATE_PREPARES => false, 
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                             )
                     );

        $sql = 'SELECT value FROM '.$prefix.'core_config_data
                WHERE path = "segment_analytics/options/write_key"'; 

        $result = $db->query($sql);
        
        $key = '';
        foreach($result as $row)
        {
            $key = $row['value'];
        }
        
        return $key;
                
    }
    
    public function getSegmentWriteKey()
    {
        if(!$this->_key)
        {
            $this->_key = $this->_fetchSegmentKey();
        }
        return $this->_key;
    }
    
    public function toHtml()
    {
        $template = $this->getData('template');
        include Segment_Analytics_Controller::getBaseDir() . $template;
    }
}