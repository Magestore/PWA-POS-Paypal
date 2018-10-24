<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\WebposPaypal\Api;

/**
 * Interface PaypalServiceInterface
 * @package Magestore\WebposPaypal\Api
 */
interface PaypalServiceInterface
{
    /**
     * Init default data
     * @return \Magestore\WebposPaypal\Api\PaypalServiceInterface
     */
    public function initDefaultData();

    /**
     * Get success url
     *
     * @api
     * @return string|null
     */
    public function getSuccessUrl();

    /**
     * Set success url
     *
     * @api
     * @param string $url
     * @return $this
     */
    public function setSuccessUrl($url);

    /**
     * Get cancel url
     *
     * @api
     * @return string|null
     */
    public function getCancelUrl();

    /**
     * Set cancel url
     *
     * @api
     * @param string $url
     * @return $this
     */
    public function setCancelUrl($url);

    /**
     * @return bool
     */
    public function isEnable();

    /**
     * @return string
     */
    public function getConfigurationError();

    /**
     * @return bool
     */
    public function canConnectToApi();

    /**
     * Validate configruation
     * @return \Magestore\WebposPaypal\Api\PaypalServiceInterface
     */
    public function validate();

    /**
     * @param Magestore\WebposPaypal\Api\Data\TransactionInterface $transaction
     * @return string
     */
    public function startPayment($transaction);

    /**
     * @param string $paymentId
     * @param string $payerId
     * @return string
     */
    public function finishPayment($paymentId, $payerId);

    /**
     * @param string $paymentId
     * @return string
     */
    public function finishPaypalHerePayment($paymentId);

    /**
     * @param \Magestore\Webpos\Api\Data\Checkout\Order\AddressInterface $billing
     * @param \Magestore\Webpos\Api\Data\Checkout\Order\AddressInterface $shipping
     * @param \Magestore\WebposPaypal\Api\Data\ItemInterface[] $items
     * @param \Magestore\WebposPaypal\Api\Data\TotalInterface[] $totals
     * @param string $totalPaid
     * @param string $currencyCode
     * @param string $note
     * @return \Magestore\WebposPaypal\Api\Data\SendInvoiceResponseInterface
     */
    public function createAndSendInvoiceToCustomer($billing, $shipping, $items, $totals, $totalPaid, $currencyCode, $note);

    /**
     * @param string $invoiceId
     * @return \PayPal\Api\Invoice
     * @throws \Exception
     */
    public function getInvoice($invoiceId);

    /**
     * @param \PayPal\Api\Invoice $invoice
     * @return bool
     */
    public function isInvoicePaid($invoice);

    /**
     * @param \PayPal\Api\Invoice $invoice
     * @return bool
     */
    public function isInvoiceCancelled($invoice);

    /**
     * @param \PayPal\Api\Invoice $invoice
     * @return bool
     */
    public function isInvoiceRefunded($invoice);

    /**
     * @param \PayPal\Api\Invoice $invoice
     * @return \PayPal\Api\PaymentSummary
     */
    public function getInvoicePaidAmount($invoice);

    /**
     * @param \PayPal\Api\Invoice $invoice
     * @return \PayPal\Api\PaymentSummary
     */
    public function getInvoiceRefundedAmount($invoice);

    /**
     * @param
     * @return string
     */
    public function getAccessToken();

    /**
     * @param \Magestore\WebposPaypal\Api\Data\DirectRequestInterface $request
     * @return \Magestore\WebposPaypal\Api\Data\DirectResponseInterface
     * @throws \Exception
     */
    public function directPayment($request);
    /**
     * @param \Magestore\WebposPaypal\Api\Data\DirectRequestInterface $request
     * @return \Magestore\WebposPaypal\Api\Data\DirectResponseInterface
     * @throws \Exception
     */
}
