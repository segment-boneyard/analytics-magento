<?php

ini_set('include_path',
    ini_get('include_path') . PATH_SEPARATOR . dirname(__FILE__) . '/../../app' . PATH_SEPARATOR . dirname(__FILE__));

/* One more for case whether .modman is being used */
ini_set('include_path',
    ini_get('include_path') . PATH_SEPARATOR . dirname(__FILE__) . '/../../../../app' . PATH_SEPARATOR . dirname(__FILE__));

require_once 'Mage.php';
require_once 'Service.php';

Mage::app('default');
session_start();

