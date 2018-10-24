<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magestore\WebposPaypal\Helper;
use Magestore\WebposPaypal\Model\Payment\Online\Paypal\DirectPaymentIntegration;

/**
 * Class Data
 * @package Magestore\WebposPaypal\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     *
     * @var \Magento\Framework\App\ObjectManager
     */
    protected $_objectManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_localeDate;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_dateTime;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $_enc;
    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Encryption\EncryptorInterface $enc
    ){
        $this->_storeManager = $storeManager;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_localeDate = $localeDate;
        $this->_dateTime = $dateTime;
        $this->_enc = $enc;
        parent::__construct($context);
    }

    /**
     *
     * @return Magento store
     */
    public function getStore(){
        return $this->_storeManager->getStore();
    }

    /**
     *
     * @param string $data
     * @return string
     */
    public function formatPrice($data){
        $checkoutHelper = $this->_objectManager->get('Magento\Checkout\Helper\Data');
        return $checkoutHelper->formatPrice($data);
    }

    /**
     *
     * @param string $path
     * @return string
     */
    public function getStoreConfig($path){
        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     *
     * @return string
     */
    public function getCurrentDatetime(){
        return $this->_dateTime->gmtDate();
    }

    /**
     * string class name
     * @return Model
     */
    public function getModel($class){
        return $this->_objectManager->get($class);
    }

    /**
     *
     * @param string $str
     * @return string
     */
    public function htmlEscape($str){
        return htmlspecialchars($str);
    }

    /**
     * @return array
     */
    public function supportPayViaEmailPayments() {
        return array(DirectPaymentIntegration::CODE);
    }

    /**
     * @param $code
     * @return bool
     */
    public function isInSupportPayViaEmailPayments($code) {
        return in_array($this->supportPayViaEmailPayments(), $code);
    }

    /**
     * @return array
     */
    public function getPaypalConfig() {
        $configData = array();
        $configItems = array(
            'enable',
            'client_id',
            'client_secret',
            'is_sandbox',
            'access_token',
            'refresh_token'
        );
        foreach ($configItems as $configItem) {
            $configData[$configItem] = $this->getStoreConfig('webpos/payment/paypal/' . $configItem);
        }
        return $configData;
    }

    /**
     * @return array|string
     */
    public function getPaypalPaymentsStandardConfig() {
        return $this->getStoreConfig(
            'payment_all_paypal/express_checkout/express_checkout_required/express_checkout_required_express_checkout'
        );
    }

    /**
     * @return array
     */
    public function getPaypalDirectPaymentConfig() {
        /** @var array $paypalPaymentsStandardConfig */
        $paypalPaymentsStandardConfig = $this->getStoreConfig(
            'webpos/payment/paypal'
        );

        $sandboxFlag = intval($paypalPaymentsStandardConfig['is_sandbox']);

        $config = array(
            "mode" => $sandboxFlag ? "sandbox" : "live",
            'log.LogEnabled' => true,
            'log.FileName' => '../PayPal.log',
            'log.LogLevel' => 'FINE',
            // Signature Credential
            "acct1.UserName" => $this->_enc->decrypt($paypalPaymentsStandardConfig['api_username']),
            "acct1.Password" => $this->_enc->decrypt($paypalPaymentsStandardConfig['api_password']),
            "acct1.Signature" => $this->_enc->decrypt($paypalPaymentsStandardConfig['api_signature']),
        );

        return $config;
    }

    /**
     * @return bool
     */
    public function isAllowCustomerPayWithEmail(){
        $enable = $this->getStoreConfig('webpos/payment/paypal/enable_send_invoice');
        return ($enable)?true:false;
    }

    /**
     * @return bool
     */
    public function isEnablePaypal(){
        $enable = $this->getStoreConfig('webpos/payment/paypal/enable');
        return ($enable)?true:false;
    }

    /**
     * @return bool
     */
    public function isAllowPaypalHere(){
        $enable = $this->getStoreConfig('webpos/payment/paypal/enable_paypalhere');
        return ($enable)?true:false;
    }

    /**
     * @return array
     */
    public function getMerchantInfo(){
        $configData = array();
        $configItems = array(
            'email',
            'firstname',
            'lastname',
            'buisiness_name',
            'phone',
            'street',
            'city',
            'state',
            'postal_code',
            'country_id'
        );
        foreach ($configItems as $configItem) {
            $configData[$configItem] = $this->getStoreConfig('webpos/payment/paypal/merchant_infomation/' . $configItem);
        }
        return $configData;
    }

    /**
     * @return string
     */
    public function getLogoUrl(){
        $helper = $this->_objectManager->get('Magestore\Webpos\Helper\Data');
        $url =$helper->getWebPosImages();
        return (strpos($url, 'https') === false)?'':$url;
    }

    /**
     * @return bool
     */
    public function isTaxCalculatedAfterDiscount(){
        $configData = $this->getStoreConfig('tax/calculation/apply_after_discount');
        return ($configData == 1)?true:false;
    }

    /**
     * @param string $path
     * @param array $params
     * @return string
     */
    public function getUrl($path, $params = array()){
        return $this->_getUrl($path, $params);
    }

    /**
     * @param $message
     * @param string $type
     */
    public function addLog($message, $type = ''){
        switch ($type){
            case 'info':
                $this->_logger->info($message);
                break;
            case 'debug':
                $this->_logger->debug($message);
                break;
            case 'info':
                $this->_logger->info($message);
                break;
            case 'notice':
                $this->_logger->notice($message);
                break;
            case 'warning':
                $this->_logger->warning($message);
                break;
            case 'error':
                $this->_logger->error($message);
                break;
            case 'emergency':
                $this->_logger->emergency($message);
                break;
            case 'critical':
                $this->_logger->critical($message);
                break;
            case 'alert':
                $this->_logger->alert($message);
                break;
            default:
                $this->_logger->error($message);
                break;
        }
    }
}