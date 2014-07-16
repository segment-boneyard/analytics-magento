<?php
class Segment_Analytics_Model_Query_Totalpurchased
{
    public function fetchTotalOrders($customer_id)
    {
        $core_resource  = Mage::getModel('core/resource');
        $read 	        = $core_resource->getConnection('core_read');
        $sql    = 'SELECT count(customer_id) as total FROM ' . $core_resource->getTableName('sales/order') . ' ' .
        'WHERE customer_id = ?';
        $query  = $read->query($sql,$customer_id);
        
        $result = $query->execute();
        $row = $query->fetch();    
        
        return array_key_exists('total', $row) ? $row['total'] : 0;
    }    
}