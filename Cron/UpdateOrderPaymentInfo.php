<?php
/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magestore\WebposPaypal\Cron;

use Magestore\Webpos\Api\Data\Checkout\Order\PaymentInterface;

class UpdateOrderPaymentInfo
{
    const POS_PAYPAL_ACTIVE = 1;
    const POS_PAYPAL_INACTIVE = 0;

    /**
     * @var \Magestore\WebposPaypal\Api\PaypalServiceInterface
     */
    protected $paypalService;

    /**
     * @var \Magestore\WebposPaypal\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magestore\Webpos\Model\ResourceModel\Sales\Order\Payment\CollectionFactory
     */
    protected $orderPaymentCollectionFactory;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var \Magestore\Webpos\Model\Sales\OrderRepository
     */
    protected $orderRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Directory\Model\CurrencyFactory
     */
    protected $currencyFactory;

    /**
     * @var \Magestore\Webpos\Api\Sales\Order\InvoiceRepositoryInterface
     */
    protected $invoiceRepositoryInterface;

    /**
     * UpdateOrderPaymentInfo constructor.
     * @param \Magestore\WebposPaypal\Helper\Data $helper
     * @param \Magestore\WebposPaypal\Api\PaypalServiceInterface $paypalService
     * @param \Magestore\Webpos\Model\ResourceModel\Sales\Order\Payment\CollectionFactory $orderPaymentCollectionFactory
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magestore\Webpos\Model\Sales\OrderRepository $orderRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Directory\Model\CurrencyFactory $currencyFactory
     * @param \Magestore\Webpos\Api\Sales\Order\InvoiceRepositoryInterface $invoiceRepositoryInterface
     */
    public function __construct(
        \Magestore\WebposPaypal\Helper\Data $helper,
        \Magestore\WebposPaypal\Api\PaypalServiceInterface $paypalService,
        \Magestore\Webpos\Model\ResourceModel\Sales\Order\Payment\CollectionFactory $orderPaymentCollectionFactory,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magestore\Webpos\Model\Sales\OrderRepository $orderRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magestore\Webpos\Api\Sales\Order\InvoiceRepositoryInterface  $invoiceRepositoryInterface
    ) {
        $this->helper = $helper;
        $this->paypalService = $paypalService;
        $this->orderPaymentCollectionFactory = $orderPaymentCollectionFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository = $orderRepository;
        $this->storeManager = $storeManager;
        $this->currencyFactory = $currencyFactory;
        $this->invoiceRepositoryInterface = $invoiceRepositoryInterface;
    }

    /**
     * Execute cron to process data
     */
    public function execute()
    {
        $payments = $this->getRemainingPayments();
        if($payments->getSize()) {
            foreach ($payments as $payment) {
                $this->checkPaypalInvoiceDataForOrder($payment);
            }
        }
    }

    /**
     * @param \Magestore\Webpos\Model\Checkout\Order\Payment $payment
     */
    protected function checkPaypalInvoiceDataForOrder($payment){
        $invoiceId = $payment->getData(PaymentInterface::POS_PAYPAL_INVOICE_ID);
        if($invoiceId){
            try{
                /** @var \PayPal\Api\Invoice $invoice */
                $invoice = $this->paypalService->getInvoice($invoiceId);
                if($this->paypalService->isInvoicePaid($invoice)){
                    //get paid summary data
                    $paidSummary = $this->paypalService->getInvoicePaidAmount($invoice);
                    $this->processPaidAmount($paidSummary, $payment);
                } else if($this->paypalService->isInvoiceCancelled($invoice)) {
                    $this->processCancelPayment($payment, $invoiceId);
                }

                $ppPayments = $invoice->getPayments();
                $transactionIds = array();

                if (empty($ppPayments)) {
                    return;
                }

                /** @var \PayPal\Api\PaymentDetail $ppPayment */
                foreach ($ppPayments as $ppPayment) {
                    $transactionIds[] = $ppPayment->getTransactionId();
                }

                if (!empty($transactionIds)) {
                    $payment->setTransactionId(implode(", ", $transactionIds));
                    $payment->setReferenceNumber(implode(", ", $transactionIds));
                }

                $payment->save();


            }catch (\Exception $e){
                $this->helper->addLog($e->getMessage());
            }
        }
    }

    /**
     * @return bool
     */
    protected function getRemainingPayments() {
        $paymentCollection = $this->orderPaymentCollectionFactory->create()
            ->addFieldToFilter('method', array(
                'in' => $this->helper->supportPayViaEmailPayments()
            ))
            ->addFieldToFilter('pos_paypal_active', self::POS_PAYPAL_ACTIVE)
            ->addFieldToFilter(PaymentInterface::IS_PAY_LATER, 1);
        return $paymentCollection;
    }

    /**
     * @param string $amount
     * @param string|null $fromCurrency
     * @param string|null $toCurrency
     * @return float
     */
    protected function currencyConvert($amount, $fromCurrency = null, $toCurrency = null)
    {
        if(!$fromCurrency){
            $fromCurrency = $this->storeManager->getStore()->getBaseCurrency();
        }
        if(!$toCurrency){
            $toCurrency = $this->storeManager->getStore()->getCurrentCurrency();
        }

        if (is_string($fromCurrency)) {
            $rateToBase = $this->currencyFactory->create()->load($fromCurrency)->getAnyRate($this->storeManager->getStore()->getBaseCurrency()->getCode());
        } elseif ($fromCurrency instanceof \Magento\Directory\Model\Currency) {
            $rateToBase = $fromCurrency->getAnyRate($this->storeManager->getStore()->getBaseCurrency()->getCode());
        }
        $rateFromBase = $this->storeManager->getStore()->getBaseCurrency()->getRate($toCurrency);

        if($rateToBase && $rateFromBase){
            $amount = $amount * $rateToBase * $rateFromBase;
        }
        return floatval($amount);
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @param string|float $amount
     * @param string $currencyCode
     * @return array
     */
    protected function getAmountsForOrder($order, $amount, $currencyCode){
        $data = ['amount' => 0, 'base_amount' => 0];
        $orderBaseCurrencyCode = $order->getBaseCurrencyCode();
        $orderCurrencyCode = $order->getOrderCurrencyCode();
        if($currencyCode == $orderBaseCurrencyCode){
            $data['base_amount'] = floatval($amount);
            $data['amount'] = $this->currencyConvert($amount, $orderBaseCurrencyCode, $orderCurrencyCode);
        }elseif($currencyCode == $orderCurrencyCode){
            $data['amount'] = floatval($amount);
            $data['base_amount'] = $this->currencyConvert($amount, $orderCurrencyCode, $orderBaseCurrencyCode);
        }else{
            $data['amount'] = $this->currencyConvert($amount, $currencyCode, $orderCurrencyCode);
            $data['base_amount'] = $this->currencyConvert($amount, $currencyCode, $orderBaseCurrencyCode);
        }
        return $data;
    }

    /**
     * @param \PayPal\Api\PaymentSummary $paidSummary
     * @param \Magestore\Webpos\Model\Payment\OrderPayment $payment
     * @throws \Exception
     */
    protected function processPaidAmount($paidSummary, $payment){
        $order = $this->orderRepository->getById($payment->getOrderId());
        if($order->getId()) {
            $paidAmount = 0;
            $basePaidAmount = 0;
            //calculate summary paid by paypal
            $paypalPaid = $paidSummary->getPaypal();
            if ($paypalPaid) {
                $paypalPaidAmounts = $this->getAmountsForOrder($order, $paypalPaid->getValue(), $paypalPaid->getCurrency());
                $basePaidAmount += $paypalPaidAmounts['base_amount'];
                $paidAmount += $paypalPaidAmounts['amount'];
            }
            //calculate summary paid by other
            $otherPaid = $paidSummary->getOther();
            if ($otherPaid) {
                $otherPaidAmounts = $this->getAmountsForOrder($order, $otherPaid->getValue(), $otherPaid->getCurrency());
                $basePaidAmount += $otherPaidAmounts['base_amount'];
                $paidAmount += $otherPaidAmounts['amount'];
            }
            if ($paypalPaid || $otherPaid) {
                $payment->setData('pos_paypal_active', self::POS_PAYPAL_INACTIVE);
                $payment->setData(PaymentInterface::IS_PAY_LATER, 0);
                $baseOrderRemaining = $order->getBaseGrandTotal() - $order->getBaseTotalPaid();
                //save order totals
                if ($basePaidAmount >= $baseOrderRemaining) {
                    $order->setBaseTotalPaid($order->getBaseGrandTotal());
                    $order->setTotalPaid($order->getGrandTotal());
                    $order->save();
                    try {
                        $this->invoiceRepositoryInterface->createInvoiceByOrder($order);
                    } catch(\Exception $e) {

                    }
                } else {
                    $order->setBaseTotalPaid($order->getBaseTotalPaid() + $basePaidAmount);
                    $order->setTotalPaid($order->getTotalPaid() + $paidAmount);
                    $order->setBaseTotalDue($order->getBaseGrandTotal() - $order->getBaseTotalPaid());
                    $order->setTotalDue($order->getGrandTotal() - $order->getTotalPaid());
                    $order->save();
                }
                $payment->save();

            }
        }
    }

    /**
     * @param \Magestore\Webpos\Model\Payment\OrderPayment $payment
     * @throws \Exception
     */
    protected function processCancelPayment($payment, $invoiceId){
        $order = $this->orderRepository->getById($payment->getOrderId());
        $payment->setData('pos_paypal_active', self::POS_PAYPAL_INACTIVE);
        $payment->save();
        $basePaidAmount = $payment->getBasePaymentAmount();
        $baseOrderRemaining = $order->getBaseGrandTotal() - $order->getBaseTotalPaid();
        $message = __('Paypal invoice #%1 was canceled', $invoiceId);
        $comment = new \Magento\Framework\DataObject();
        $comment->setComment($message);
        $comment->setIsVisibleOnFront(1);
        $comment->setCreatedAt('');
        $this->orderRepository->comment($order, $comment);
        if ($basePaidAmount >= $baseOrderRemaining) {
            try {
                $this->orderRepository->cancel($order);
            } catch(\Exception $e) {

            }
        }
    }
}
