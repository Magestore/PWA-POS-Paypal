<?php
/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magestore\WebposPaypal\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class GetPaymentAfter
 * @package Magestore\WebposPaypal\Observer
 */
class GetPaymentAfter implements ObserverInterface
{
    /**
     * @var \Magestore\WebposPaypal\Helper\Data
     */
    private $paypalHelper;
    /**
     * @var \Magestore\Webpos\Helper\Payment
     */
    private $paymentHelper;
    /**
     * @var \Magento\Payment\Helper\Data
     */
    private $corePaymentHelper;

    public function __construct()
    {
        $this->paypalHelper = \Magento\Framework\App\ObjectManager::getInstance()
            ->create('Magestore\WebposPaypal\Helper\Data');
        $this->paymentHelper = \Magento\Framework\App\ObjectManager::getInstance()
            ->create('Magestore\Webpos\Helper\Payment');
        $this->corePaymentHelper = \Magento\Framework\App\ObjectManager::getInstance()
            ->create('Magento\Payment\Helper\Data');
    }

    /**
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        $payments = $observer->getData('payments');
        $paymentList = $payments->getList();
        if($this->paypalHelper->isEnablePaypal()) {
            $paymentList[] = $this->getWebposPaypalDirectPayment()->getData();
        }

        $payments->setList($paymentList);

        return $this;
    }


    /**
     * @return mixed
     */
    public function getWebposPaypalDirectPayment()
    {
        /** @var array $paypalConfig */
        $paypalConfig = $this->paymentHelper->getStoreConfig('webpos/payment/paypal');
        $sortOrder = $paypalConfig['sort_order'];
        $sortOrder = $sortOrder ? (int)$sortOrder : 0;
        /** @var \Magestore\Webpos\Model\Payment\Payment $paymentModel */
        $paymentModel = \Magento\Framework\App\ObjectManager::getInstance()
            ->create('Magestore\Webpos\Model\Payment\Payment');
        $paymentModel->setCode('ppdirectpayment_integration');
        $paymentModel->setTitle($paypalConfig['title']);
        $paymentModel->setIsAllowPayViaEmail(intval($paypalConfig['enable_send_invoice']));
        $paymentModel->setInformation('');
        $paymentModel->setType('2');
        $paymentModel->setIsReferenceNumber(0);
        $paymentModel->setIsPayLater(0);
        $paymentModel->setMultiable(1);
        $paymentModel->setSortOrder($sortOrder);
        return $paymentModel;
    }

    /**
     * @return \Magestore\Webpos\Model\Payment\Payment
     */
    public function addWebposPaypalHere()
    {
        $isSandbox = $this->paymentHelper->getStoreConfig('webpos/payment/paypal/is_sandbox');
        $clientId = $this->paymentHelper->getStoreConfig('webpos/payment/paypal/client_id');
        $isDefault = ('paypal_integration' == $this->paymentHelper->getDefaultPaymentMethod()) ?
            \Magestore\Webpos\Api\Data\Payment\PaymentInterface::YES :
            \Magestore\Webpos\Api\Data\Payment\PaymentInterface::NO;
        $paymentModel = \Magento\Framework\App\ObjectManager::getInstance()
            ->create('Magestore\Webpos\Model\Payment\Payment');
        $paymentModel->setCode('paypal_here');
        $paymentModel->setIconClass('paypal_here');
        $paymentModel->setTitle(__('Paypal Here'));
        $paymentModel->setInformation('');
        $paymentModel->setType('2');
        $paymentModel->setIsDefault($isDefault);
        $paymentModel->setIsReferenceNumber(1);
        $paymentModel->setIsPayLater(0);
        $paymentModel->setMultiable(1);
        $paymentModel->setClientId($clientId);
        $paymentModel->setIsSandbox($isSandbox);
        $accessToken = $this->paymentHelper->getStoreConfig('webpos/payment/paypal/access_token');
        if($accessToken) {
            $paymentModel->setAccessToken($accessToken);
        }
        return $paymentModel;
    }
}