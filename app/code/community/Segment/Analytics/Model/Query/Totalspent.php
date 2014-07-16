<?php
class Segment_Analytics_Model_Query_Totalspent
{
    public function fetchTotalSpent($customer_id)
    {
        $core_resource  = Mage::getModel('core/resource');
        $read 	        = $core_resource->getConnection('core_read');
        $sql    = 'SELECT sum(grand_total) as total FROM ' . $core_resource->getTableName('sales/order') . ' ' .
        'WHERE customer_id = ?';
        $query  = $read->query($sql,$customer_id);        
        $result = $query->execute();
        $row = $query->fetch();    
        
        return array_key_exists('total', $row) ? $row['total'] : 0;
    
    }
}