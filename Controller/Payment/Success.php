<?php
/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\WebposPaypal\Controller\Payment;
use Magestore\Webpos\Model\Exception;

/**
 * Class Success
 * @package Magestore\WebposPaypal\Controller\Payment
 */
class Success extends \Magestore\WebposPaypal\Controller\AbstractAction
{
    /**
     * @return string
     */
    public function execute()
    {
        $response = [
            'message' => '',
            'success' => true
        ];
        $paymentId = $this->getRequest()->getParam('paymentId');
        $payerId = $this->getRequest()->getParam('PayerID');
        $isApp = $this->getRequest()->getParam('isApp');
        if($isApp) {
            if ($paymentId) {
                $transactionId = $this->paypalService->finishPaypalHerePayment($paymentId, $payerId);
                $response['transactionId'] = $transactionId;
                $response['message'] = __('Payment has been completed');
            }
            if($response['transactionId']) {
                return $transactionId;
            } else {
                throw new \Magento\Framework\Exception\StateException(
                    __('Can not place order')
                );
            }
        } else {
            if ($paymentId && $payerId) {
                $transactionId = $this->paypalService->finishPayment($paymentId, $payerId);
                $response['transactionId'] = $transactionId;
                $response['message'] = __('Payment has been completed');
            }  else{
                $response['message'] = __('An error occur during the payment processing');
                $response['success'] = false;
            }
            $resultPage = $this->createPageResult();
            $blockInstance = $resultPage->getLayout()->getBlock('webpos_paypal_integration_success');
            $blockInstance->setData('api_response', \Zend_Json::encode($response));
            return $resultPage;
        }
    }
}
