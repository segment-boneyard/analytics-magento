<?php
/**
 * Atwix
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.

 * @category    Atwix Ext
 * @package     Atwix_Segmentio
 * @author      Atwix Core Team
 * @copyright   Copyright (c) 2014 Atwix (http://www.atwix.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Atwix_Segmentio_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CONFIG_PATH_SEGMENTIO_ENABLED = 'atwix_segmentio/general/enabled';

    /**
     * Wrapper for getting configuration value
     *
     * @param string $path
     * @param int $storeId
     * @return Mage_Core_Model_Config
     */
    public function getConfig($path, $storeId = null)
    {
        $config = Mage::getConfig($path, $storeId);

        return $config;
    }

    /**
     * Get country by IP if user have not logging
     *
     * @return string
     */
    public function getCountry()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
        $result  = "Unknown";
        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        $ipData = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

        if ($ipData && $ipData->geopluginCountryName != null) {
            $result = $ipData->geopluginCountryName;
        }

        return $result;
    }

    /**
     * Get category name for product
     *
     * @param $itemId
     * @return string
     */
    public function getCategoryItemProduct($itemId)
    {
        $product = Mage::getModel('catalog/product')->load($itemId);
        $categoryIds = $product->getCategoryIds();

        $categoryName = Mage::getModel('catalog/category')->load($categoryIds[0])->getName();

        return $categoryName;
    }
}

