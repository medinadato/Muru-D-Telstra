<?php

/**
 * MDN Solutions
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://magento.mdnsolutions.com/license
 *
 * @category   MDN Solutions
 * @package    MDN_SMS
 * @author     Renato Medina <medina@mdnsolutions.com>
 * @copyright  Copyright (c) 2003 - 2015 MDN Solutions (http://magento.mdnsolutions.com)
 * @license    http://magento.mdnsolutions.com/license
 */
class MDN_SMS_Block_Adwords extends Mage_Core_Block_Template
{

    /**
     * 
     * @return string
     */
    public function isEnabled()
    {
        return Mage::getStoreConfig('mdn_sms/telstra/enable');
    }

    /**
     * 
     * @return string
     */
    public function getAppKey()
    {
        return Mage::getStoreConfig('mdn_sms/telstra/app_key');
    }

    /**
     * 
     * @return string
     */
    public function getAppSecret()
    {
        return Mage::getStoreConfig('mdn_sms/telstra/app_secret');
    }

}
