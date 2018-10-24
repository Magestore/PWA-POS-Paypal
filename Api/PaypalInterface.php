<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\WebposPaypal\Api;

/**
 * Interface PaypalInterface
 * @package Magestore\WebposPaypal\Api
 */
interface PaypalInterface
{
    /**
     * @return bool
     */
    public function validateRequiredSDK();

    /**
     * @return \PayPal\Rest\ApiContext
     */
    public function getApiContext();

    /**
     * @param string $successUrl
     * @param string $cancelUrl
     * @param \PayPal\Api\Transaction[] $transactions
     * @return string
     * @throws \Exception
     */
    public function createPayment($successUrl, $cancelUrl, $transactions);

    /**
     * @param string $subtotal
     * @param string $shipping
     * @param string $tax
     * @param string $total
     * @param string $currencyCode
     * @param string $description
     * @return \PayPal\Api\Transaction
     */
    public function createTransaction($subtotal, $shipping, $tax, $total, $currencyCode, $description = '');

    /**
     * @param string $paymentId
     * @param string $payerId
     * @return string
     * @throws \Exception
     */
    public function completePayment($paymentId, $payerId);

    /**
     * @param string $paymentId
     * @return string
     * @throws \Exception
     */
    public function completePaypalHerePayment($paymentId);

    /**
     * @return bool
     */
    public function canConnectToApi();

    /**
     * @param \PayPal\Api\MerchantInfo $merchantInfo
     * @param \PayPal\Api\BillingInfo $billingInfo
     * @param \PayPal\Api\ShippingInfo $shippingInfo
     * @param \PayPal\Api\PaymentTerm $paymentTerm
     * @param \PayPal\Api\InvoiceItem[] $items
     * @param string $note
     * @return \PayPal\Api\Invoice
     * @throws \Exception
     */
    public function createInvoiceObject($merchantInfo, $billingInfo, $shippingInfo, $paymentTerm, $items, $note = '');

    /**
     * @param \PayPal\Api\Invoice $invoice
     * @return mixed
     * @throws \Exception
     */
    public function createInvoice($invoice);

    /**
     * @param \PayPal\Api\Invoice $invoice
     * @return mixed
     * @throws \Exception
     */
    public function createInvoiceAndSend($invoice);

    /**
     * @param \PayPal\Api\Invoice $invoice
     * @return \Magestore\WebposPaypal\Model\Paypal
     * @throws \Exception
     */
    public function sendInvoice($invoice);

    /**
     * @param string $invoiceId
     * @return \Magestore\WebposPaypal\Model\Paypal
     * @throws \Exception
     */
    public function sendInvoiceById($invoiceId);

    /**
     * @param \PayPal\Api\Invoice $invoice
     * @return \PayPal\Api\Image
     * @throws \Exception
     */
    public function getInvoiceQrCode($invoice);

    /**
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @param string $businessName
     * @param \PayPal\Api\Phone $phone
     * @param \PayPal\Api\Address $address
     * @return \PayPal\Api\MerchantInfo
     */
    public function createMerchantInfo($email, $firstname, $lastname, $businessName, $phone, $address);

    /**
     * @param string $countryCode
     * @param string $number
     * @return \PayPal\Api\Phone
     */
    public function createPhone($countryCode, $number);

    /**
     * @param string $line1
     * @param string $line2
     * @param string $city
     * @param string $state
     * @param string $postalCode
     * @param string $countryCode
     * @return \PayPal\Api\Address
     */
    public function createAddress($line1, $line2, $city, $state, $postalCode, $countryCode);

    /**
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @param string $businessName
     * @param \PayPal\Api\Phone $phone
     * @param string $addtionalInfo
     * @param \PayPal\Api\InvoiceAddress $invoiceAddress
     * @return \PayPal\Api\BillingInfo
     */
    public function createBillingInfo($email, $firstname, $lastname, $businessName, $phone, $addtionalInfo = '', $invoiceAddress);

        /**
     * @param string $line1
     * @param string $line2
     * @param string $city
     * @param string $state
     * @param string $postalCode
     * @param string $countryCode
     * @return \PayPal\Api\InvoiceAddress
     */
    public function createInvoiceAddress($line1, $line2, $city, $state, $postalCode, $countryCode);

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $businessName
     * @param \PayPal\Api\Phone $phone
     * @param \PayPal\Api\InvoiceAddress $invoiceAddress
     * @return \PayPal\Api\ShippingInfo
     */
    public function createShippingInfo($firstname, $lastname, $businessName, $phone, $invoiceAddress);

    /**
     * @param string $termType
     * @param string $dueDate
     * @return \PayPal\Api\PaymentTerm
     */
    public function createPaymentTerm($termType, $dueDate);

    /**
     * @param string $percent
     * @return \PayPal\Api\Cost
     */
    public function createPercentCost($percent);

    /**
     * @param \PayPal\Api\Currency $amount
     * @return \PayPal\Api\Cost
     */
    public function createFixedCost($amount);

    /**
     * @param string $currencyCode
     * @param string|float $value
     * @return \PayPal\Api\Currency
     */
    public function createCurrency($currencyCode, $value);

    /**
     * @param string $percent
     * @param string $name
     * @return \PayPal\Api\Tax
     */
    public function createPercentTax($percent, $name = '');

    /**
     * @param \PayPal\Api\Currency $amount
     * @param string $name
     * @return \PayPal\Api\Tax
     */
    public function createFixedTax($amount, $name = '');

    /**
     * @param string $name
     * @param string $qty
     * @param \PayPal\Api\Currency $unitPrice
     * @return \PayPal\Api\InvoiceItem
     */
    public function createInvoiceItem($name, $qty, $unitPrice);

    /**
     * @param \PayPal\Api\Currency $other
     * @return \PayPal\Api\PaymentSummary
     */
    public function createPaymentSummary($other);

    /**
     * @param \PayPal\Api\Currency $amount
     * @param \PayPal\Api\Tax $tax
     * @return \PayPal\Api\ShippingCost
     */
    public function createShippingCost($amount, $tax);

    /**
     * @param string $invoiceId
     * @return \PayPal\Api\Invoice
     * @throws \Exception
     */
    public function getInvoice($invoiceId);

    /**
     * @param \PayPal\Api\Currency $amount
     * @param string $note
     * @param string $method
     * @return \PayPal\Api\PaymentDetail
     */
    public function createPaymentDetail($amount, $note = "", $method = "OTHER");

    /**
     * @param \PayPal\Api\Invoice $invoice
     * @param \PayPal\Api\PaymentDetail $paymentDetail
     * @return $this
     * @throws \Exception
     */
    public function recordPaymentForInvoice($invoice, $paymentDetail);

    /**
     * get access token
     *
     * @return string
     * @throws \Exception
     */
    public function getAccessToken();

    /**
     * @param \Magestore\WebposPaypal\Api\Data\DirectRequestInterface $request
     * @return \Magestore\WebposPaypal\Api\Data\DirectResponseInterface
     * @throws \Exception
     */
    public function directPayment($request);
}
