<?php
/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magestore\WebposPaypal\Model\Payment\Online\Paypal;

use Magento\Paypal\Model\Config;
use Magento\Store\Model\ScopeInterface;
use Magento\Payment\Model\InfoInterface;
use Magento\Framework\Exception\LocalizedException;

class PayflowproIntegration extends \Magento\Paypal\Model\Payflow\Transparent
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = 'payflowpro_integration';
    public function getConfigPaymentAction()
    {
        return '';
    }
    /**
     * Check whether payment method can be used
     *
     * @param \Magento\Quote\Api\Data\CartInterface|Quote|null $quote
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        return $this->isActive();
    }
    public function isActive($storeId = null)
    {
        return false;
    }
    public function isActiveWebpos($storeId = null)
    {
        $pathPayflowPro = 'payment/' . Config::METHOD_PAYFLOWPRO . '/active';
        $pathPaymentPro = 'payment/' . Config::METHOD_PAYMENT_PRO . '/active';
        return (bool)(int) $this->_scopeConfig->getValue($pathPayflowPro, ScopeInterface::SCOPE_STORE, $storeId)
            || (bool)(int) $this->_scopeConfig->getValue($pathPaymentPro, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Performs authorize transaction
     *
     * @param InfoInterface|Object $payment
     * @param float $amount
     * @return $this
     * @throws InvalidTransitionException
     * @throws LocalizedException
     */
    public function authorize(InfoInterface $payment, $amount)
    {
        // change amount
        $amount = (float)$payment->getAdditionalInformation('paypalProAmount');
        if (!$amount) {
            $amount = 0;
        }

        /** @var Payment $payment */
        $request = $this->buildBasicRequest();

        /** @var \Magento\Sales\Model\Order $order */
        $order = $payment->getOrder();
        $this->addRequestOrderInfo($request, $order);
        $request = $this->fillCustomerContacts($order, $request);

        $token = $payment->getAdditionalInformation(self::PNREF);
        $request->setData('trxtype', self::TRXTYPE_AUTH_ONLY);
        $request->setData('origid', $token);
        $request->setData('amt', $this->formatPrice($amount));
        $request->setData('currency', $order->getBaseCurrencyCode());
        $request->setData('taxamt', $this->formatPrice($order->getBaseTaxAmount()));
        $request->setData('freightamt', $this->formatPrice($order->getBaseShippingAmount()));

        $response = $this->postRequest($request, $this->getConfig());
        $this->processErrors($response);

        try {
            $this->getResponceValidator()->validate($response, $this);
        } catch (LocalizedException $exception) {
            $payment->setParentTransactionId($response->getData(self::PNREF));
            $this->void($payment);
            throw new LocalizedException(__('Error processing payment. Please try again later.'));
        }

        $this->setTransStatus($payment, $response);

        $this->createPaymentToken($payment, $token);

        $payment->unsAdditionalInformation(self::CC_DETAILS);
        $payment->unsAdditionalInformation(self::PNREF);

        return $this;
    }
}
