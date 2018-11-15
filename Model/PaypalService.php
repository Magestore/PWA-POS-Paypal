<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magestore\WebposPaypal\Model;


use Magestore\WebposPaypal\Api\Data\SendInvoiceResponseInterface;
use PayPal\Exception\PayPalConnectionException;

class PaypalService implements \Magestore\WebposPaypal\Api\PaypalServiceInterface
{
    /**
     * @var \Magestore\WebposPaypal\Api\PaypalInterface
     */
    protected $paypal;

    /**
     * @var \Magestore\WebposPaypal\Api\Data\InvoiceInterface
     */
    protected $invoiceData;

    /**
     * @var \Magestore\WebposPaypal\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magestore\WebposPaypal\Model\Source\PhoneCode
     */
    protected $phoneSource;

    /**
     * @var string
     */
    protected $successUrl;

    /**
     * @var string
     */
    protected $cancelUrl;

    /**
     * @var \Magestore\WebposPaypal\Model\Data\SendInvoiceResponseFactory
     */
    protected $sendInvoiceResponseFactory;

    /**
     * PaypalService constructor.
     * @param \Magestore\WebposPaypal\Api\PaypalInterface $paypal
     * @param \Magestore\WebposPaypal\Helper\Data $helper
     * @param \Magestore\WebposPaypal\Model\Data\SendInvoiceResponseFactory $sendInvoiceResponseFactory
     * @param Source\PhoneCode $phoneSource
     * @param \Magestore\WebposPaypal\Api\Data\InvoiceInterface $invoiceData
     */
    public function __construct(
        \Magestore\WebposPaypal\Api\PaypalInterface $paypal,
        \Magestore\WebposPaypal\Helper\Data $helper,
        \Magestore\WebposPaypal\Model\Data\SendInvoiceResponseFactory $sendInvoiceResponseFactory,
        \Magestore\WebposPaypal\Model\Source\PhoneCode $phoneSource,
        \Magestore\WebposPaypal\Api\Data\InvoiceInterface $invoiceData
    ) {
        $this->paypal                     = $paypal;
        $this->helper                     = $helper;
        $this->sendInvoiceResponseFactory = $sendInvoiceResponseFactory;
        $this->phoneSource                = $phoneSource;
        $this->invoiceData                = $invoiceData;
        $this->initDefaultData();
    }

    /**
     * Init default data
     * @return \Magestore\WebposPaypal\Api\PaypalServiceInterface
     */
    public function initDefaultData(){
        $successUrl = $this->helper->getUrl('webpospaypal/payment/success');
        $cancelUrl = $this->helper->getUrl('webpospaypal/payment/cancel');
        $this->setSuccessUrl($successUrl);
        $this->setCancelUrl($cancelUrl);
        return $this;
    }

    /**
     * Get success url
     *
     * @api
     * @return string|null
     */
    public function getSuccessUrl(){
        return $this->successUrl;
    }

    /**
     * Set success url
     *
     * @api
     * @param string $url
     * @return $this
     */
    public function setSuccessUrl($url){
        $this->successUrl = $url;
    }

    /**
     * Get cancel url
     *
     * @api
     * @return string|null
     */
    public function getCancelUrl(){
        return $this->cancelUrl;
    }

    /**
     * Set cancel url
     *
     * @param string $url
     * @return $this|void
     */
    public function setCancelUrl($url){
        $this->cancelUrl = $url;
    }

    /**
     * @return bool
     */
    public function isEnable(){
        $hasSDK = $this->paypal->validateRequiredSDK();
        $configs = $this->paypal->getConfig();
        return ($hasSDK && $configs['enable'] && !empty($configs['client_id']) && !empty($configs['client_secret']))?true:false;
    }

    /**
     * @return string
     */
    public function getConfigurationError(){
        $message = '';
        $hasSDK = $this->paypal->validateRequiredSDK();
        $configs = $this->paypal->getConfig();
        if(!$hasSDK){
            $message = __('Paypal SDK not found, please go to the configuration to get the instruction to install the SDK');
        }else{
            if($configs['enable']){
                if(empty($configs['client_id']) || empty($configs['client_secret'])){
                    $message = __('Paypal application client id and client secret are required');
                }
            }else{
                $message = __('Paypal integration is disabled');
            }
        }
        return $message;
    }

    /**
     * Validate configruation
     * @return \Magestore\WebposPaypal\Api\PaypalServiceInterface
     * @throws \Exception
     */
    public function validate(){
        $isEnable = $this->isEnable();
        if (!$isEnable) {
            $message = $this->getConfigurationError();
            throw new \Magento\Framework\Exception\LocalizedException(
                __($message)
            );
        }
        return $this;
    }

    /**
     * @param \Magestore\WebposPaypal\Api\Data\TransactionInterface $transaction
     * @return string
     * @throws \Exception
     */
    public function startPayment($transaction){
        $this->validate();
        $successUrl = $this->getSuccessUrl();
        $cancelUrl = $this->getCancelUrl();
        $transactions = [];
        $subtotal = 0;
        $shipping = 0;
        $tax = 0;
        $total = $transaction->getTotal();
        $currencyCode = $transaction->getCurrency();
        $description = $transaction->getDescription();
        $transaction = $this->paypal->createTransaction($subtotal, $shipping, $tax, $total, $currencyCode, $description);
        $transactions[] = $transaction;
        $approvalUrl = $this->paypal->createPayment($successUrl, $cancelUrl, $transactions);
        return $approvalUrl;
    }

    /**
     * @param string $paymentId
     * @param string $payerId
     * @return string
     * @throws \Exception
     */
    public function finishPayment($paymentId, $payerId){
        return $this->paypal->completePayment($paymentId, $payerId);
    }

    /**
     * @param string $paymentId
     * @return string
     * @throws \Exception
     */
    public function finishPaypalHerePayment($paymentId){
        return $this->paypal->completePaypalHerePayment($paymentId);
    }

    /**
     * @return bool
     */
    public function canConnectToApi(){
        return $this->paypal->canConnectToApi();
    }

    /**
     * @param \Magestore\Webpos\Api\Data\Checkout\Order\AddressInterface $billing
     * @param \Magestore\Webpos\Api\Data\Checkout\Order\AddressInterface $shipping
     * @param \Magestore\WebposPaypal\Api\Data\ItemInterface[] $items
     * @param \Magestore\WebposPaypal\Api\Data\TotalInterface[] $totals
     * @param string $totalPaid
     * @param string $currencyCode
     * @param string $note
     * @return \Magestore\WebposPaypal\Api\Data\SendInvoiceResponseInterface
     * @throws \Exception
     */
    public function createAndSendInvoiceToCustomer($billing, $shipping, $items, $totals, $totalPaid, $currencyCode, $note){
        // validate SDK installed and some configuration, thow exception if error
        /** @var SendInvoiceResponseInterface $sendInvoiceResponse */
        $sendInvoiceResponse = $this->sendInvoiceResponseFactory->create();

        $this->validate();
        $enable = $this->helper->isAllowCustomerPayWithEmail();
        if($enable){
            $invoice = null;

            try {
                // prepare merchant info object
                $merchantInfoConfig = $this->helper->getMerchantInfo();
                $merchantPhoneCode = $this->phoneSource->getPhoneCodeByCountry($merchantInfoConfig['country_id']);
                $merchantPhone = $this->paypal->createPhone($merchantPhoneCode, $merchantInfoConfig['phone']);
                $merchantAddress = $this->paypal->createAddress(
                    $merchantInfoConfig['street'],
                    '',
                    $merchantInfoConfig['city'],
                    $merchantInfoConfig['state'],
                    $merchantInfoConfig['postal_code'],
                    $merchantInfoConfig['country_id']
                );
                $merchantInfo = $this->paypal->createMerchantInfo(
                    $merchantInfoConfig['email'],
                    $merchantInfoConfig['firstname'],
                    $merchantInfoConfig['lastname'],
                    $merchantInfoConfig['buisiness_name'],
                    $merchantPhone,
                    $merchantAddress
                );

                // prepare billing info data
                $billingAddtionalInfo = '';
                $billingStreet = $billing->getStreet();
                $billingAddress = $this->paypal->createInvoiceAddress(
                    (isset($billingStreet[0]))?$billingStreet[0]:'',
                    (isset($billingStreet[1]))?$billingStreet[1]:'',
                    ($billing->getPostcode())?$billing->getCity():__("None"),
                    ($billing->getRegion())?$billing->getRegion():__("None"),
                    ($billing->getPostcode())?$billing->getPostcode():__("NaN"),
                    $billing->getCountryId()
                );
                $billingPhoneCode = $this->phoneSource->getPhoneCodeByCountry($billing->getCountryId());
                $billingPhone = $this->paypal->createPhone($billingPhoneCode, $billing->getTelephone());
                $billingName = $billing->getFirstname().' '.$billing->getLastname();

                //prepare shipping info data
                $shippingStreet = $shipping->getStreet();
                $shippingAddress = $this->paypal->createInvoiceAddress(
                    (isset($shippingStreet[0]))?$shippingStreet[0]:'',
                    (isset($shippingStreet[1]))?$shippingStreet[1]:'',
                    ($shipping->getPostcode())?$shipping->getCity():__("None"),
                    ($shipping->getRegion())?$shipping->getRegion():__("None"),
                    ($shipping->getPostcode())?$shipping->getPostcode():__("NaN"),
                    $shipping->getCountryId()
                );
                $shippingPhoneCode = $this->phoneSource->getPhoneCodeByCountry($shipping->getCountryId());
                $shippingPhone = $this->paypal->createPhone($shippingPhoneCode, $shipping->getTelephone());
                $shippingName = $shipping->getFirstname().' '.$shipping->getLastname();

                // create required objects to create invoice
                $billingInfo = $this->paypal->createBillingInfo($billing->getEmail(), $billing->getFirstname(), $billing->getLastname(), $billingName, $billingPhone, $billingAddtionalInfo, $billingAddress);
                $shippingInfo = $this->paypal->createShippingInfo($shipping->getFirstname(), $shipping->getLastname(), $shippingName, $shippingPhone, $shippingAddress);
                $paymentTerm = $this->paypal->createPaymentTerm("NET_45");
                $invoiceItems = [];
                foreach ($items as $item) {
                    $unitPrice = $this->paypal->createCurrency($currencyCode, $item->getUnitPrice());
                    $invoiceItem = $this->paypal->createInvoiceItem($item->getName(), $item->getQty(), $unitPrice);
                    if($item->getTaxPercent() > 0){
                        $itemTax = $this->paypal->createPercentTax($item->getTaxPercent(), __('Tax'));
                        $invoiceItem->setTax($itemTax);
                    }
                    $invoiceItems[] = $invoiceItem;
                }

                // create invoiceo object
                $invoice = $this->paypal->createInvoiceObject($merchantInfo, $billingInfo, $shippingInfo, $paymentTerm, $invoiceItems, $note);

                // set some totals data
                if(!empty($totals)){
                    $discount = 0;
                    foreach ($totals as $total){
                        $amount = $total->getAmount();
                        $code = $total->getCode();
                        if($code == 'grandtotal'){
                            $grandTotal = $this->paypal->createCurrency($currencyCode, $amount);
                            $invoice->setTotalAmount($grandTotal);
                        }
                        if($code == 'shipping' && $amount > 0){
                            $shippingTaxCurrency = $this->paypal->createCurrency($currencyCode, 0);
                            $shippingTax = $this->paypal->createFixedTax($shippingTaxCurrency, __('Tax'));
                            $shippingCurrency = $this->paypal->createCurrency($currencyCode, $amount);
                            $shippingCost = $this->paypal->createShippingCost($shippingCurrency, $shippingTax);
                            $invoice->setShippingCost($shippingCost);
                        }
                        if($amount && $amount < 0){
                            $discount -= floatval($amount);
                        }
                    }
                    if($discount > 0){
                        $discountCurrency = $this->paypal->createCurrency($currencyCode, $discount);
                        $discountCost = $this->paypal->createFixedCost($discountCurrency);
                        $invoice->setDiscount($discountCost);
                    }
                }

                // set tax configuration
                $taxCalculatedAfterDiscount = $this->helper->isTaxCalculatedAfterDiscount();
                $invoice->setTaxCalculatedAfterDiscount($taxCalculatedAfterDiscount);

                // sync invoice object to server and send email to payer
                /** @var \PayPal\Api\Invoice $invoice */
                $invoice = $this->paypal->createInvoice($invoice);

                // set total paid for invoice
                if(!empty($totalPaid)){
                    $totalPaid = $this->paypal->createCurrency($currencyCode, $totalPaid);
                    $paymentDetail = $this->paypal->createPaymentDetail($totalPaid, __("Paid via Magento POS system"));
                    $this->paypal->recordPaymentForInvoice($invoice, $paymentDetail);
                }

                $this->paypal->sendInvoice($invoice);
            }
            catch (PayPalConnectionException $e) {
                $errorJson = $e->getData() ? json_decode($e->getData(), true) : array();
                $message = !empty($errorJson)
                    ? ( !empty($errorJson['message']) ? $errorJson['message'] : $errorJson['error_description'] )
                    : $e->getMessage();
                $sendInvoiceResponse
                    ->setError(true)
                    ->setMessage($message);

                return $sendInvoiceResponse;
            }
            catch (\Exception $e) {
                $sendInvoiceResponse
                    ->setError(true)
                    ->setMessage($e->getMessage());

                return $sendInvoiceResponse;
            }

            $this->invoiceData
                ->setNumber($invoice->getNumber())
                ->setId($invoice->getId())
            ;

            $sendInvoiceResponse
                ->setError(false)
                ->setInvoice($this->invoiceData);
            return $sendInvoiceResponse;
        }


        $sendInvoiceResponse
            ->setError(true)
            ->setMessage('Connection failed. Please contact admin to check the configuration of API');

        return $sendInvoiceResponse;
    }

    /**
     * @param $invoice
     * @return \Magestore\WebposPaypal\Api\Data\InvoiceInterface
     * @throws \Exception
     */
    protected function getInvoiceData($invoice){
        $data = $this->invoiceData;
        if($invoice instanceof \PayPal\Api\Invoice){
            $data->setId($invoice->getId());
            $data->setNumber($invoice->getNumber());
            $data->setQrCode($this->paypal->getInvoiceQrCode($invoice));
        }else{
            $data->setId('');
            $data->setNumber('');
            $data->setQrCode('');
        }
        return $data;
    }

    /**
     * @param string $invoiceId
     * @return \PayPal\Api\Invoice
     * @throws \Exception
     */
    public function getInvoice($invoiceId){
        return $this->paypal->getInvoice($invoiceId);
    }

    /**
     * @param \PayPal\Api\Invoice $invoice
     * @return bool
     */
    public function isInvoicePaid($invoice){
        $paidStatus = ['PAID', 'MARKED_AS_PAID', 'PARTIALLY_PAID'];
        $status = $invoice->getStatus();
        return (in_array($status, $paidStatus))?true:false;
    }

    /**
     * @param \PayPal\Api\Invoice $invoice
     * @return bool
     */
    public function isInvoiceCancelled($invoice){
        $cancelledStatus = ['CANCELLED'];
        $status = $invoice->getStatus();
        return (in_array($status, $cancelledStatus))?true:false;
    }

    /**
     * @param \PayPal\Api\Invoice $invoice
     * @return bool
     */
    public function isInvoiceRefunded($invoice){
        $refundedStatus = ['REFUNDED', 'PARTIALLY_REFUNDED', 'MARKED_AS_REFUNDED'];
        $status = $invoice->getStatus();
        return (in_array($status, $refundedStatus))?true:false;
    }

    /**
     * @param \PayPal\Api\Invoice $invoice
     * @return \PayPal\Api\PaymentSummary
     */
    public function getInvoicePaidAmount($invoice){
        return $invoice->getPaidAmount();
    }

    /**
     * @param \PayPal\Api\Invoice $invoice
     * @return \PayPal\Api\PaymentSummary
     */
    public function getInvoiceRefundedAmount($invoice){
        return $invoice->getRefundedAmount();
    }

    /**
     * @return string
     */
    public function getAccessToken(){
        return $this->paypal->getAccessToken();
    }

    /**
     * @param \Magestore\WebposPaypal\Api\Data\DirectRequestInterface $request
     * @return \Magestore\WebposPaypal\Api\Data\DirectResponseInterface
     * @throws \Exception
     */
    public function directPayment($request) {
        return $this->paypal->directPayment($request);
    }
}
