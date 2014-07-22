<?php
$path_local = Mage::getBaseDir() . '/errors/local.xml';
$extra      = '';
if(file_exists($path_local))
{
    //local.xml already exists, create file as segment.xml with info
    $path_local = Mage::getBaseDir() . '/errors/segment_analytics_local.xml';
    $extra      = 'Replace your current errors/local.xml with this file to enable Segment Tracking';
}

$xml = '<?xml version="1.0"?>
    <config>
        <!-- ' . $extra . '-->
        <skin>segment_analytics</skin>
    </config>
';

if(is_writable(dirname($path_local)))
{
    file_put_contents($path_local, $xml);
}