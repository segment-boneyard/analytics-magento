<?php
class Segment_Analytics_Model_Analytics
{
    public function __construct()
    {
        //init the clinet side library    
        $path = Mage::getModuleDir('', 'Segment_Analytics') . DS . 'external-lib' . DS . 'Analytics.php';        
        include($path);        

        $key = Mage::helper('segment_analytics')->getWriteKey();

        Analytics::init($key, array(
          "on_error" => function ($code, $message) {
            error_log('Segment.io analytics error: [$code] $message');
          }
        ));
    }

    public function track($user_id, $event, $properties = null, $timestamp = null, $context = array()) 
    {
        return Analytics::track($user_id, $event, $properties, $timestamp, $context);
    }

    public static function identify($user_id, $traits = array(), $timestamp = null, $context = array()) 
    {
        return Analytics::identify($user_id, $traits, $timestamp, $context);
    }

    public static function alias($from, $to, $timestamp = null, $context = array()) {
        return Analytics::alias($from, $to, $timestamp, $context);
    }

    
}