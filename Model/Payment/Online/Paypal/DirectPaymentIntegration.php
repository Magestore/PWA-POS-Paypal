<?php
/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magestore\WebposPaypal\Model\Payment\Online\Paypal;

const PAYMENT_METHOD_CODE = 'ppdirectpayment_integration';

class DirectPaymentIntegration extends \Magestore\Payment\Model\Payment\AbstractMethod {

    const CODE = PAYMENT_METHOD_CODE;
    /**
     * Payment method code
     * @var string
     */
    protected $_code = PAYMENT_METHOD_CODE;
    /**
     * @var string
     */
    protected $enabledPath = 'webpos/payment/paypal/enable';
    /**
     * Class of form block
     * @var string
     */
    protected $_formBlockType = 'Magestore\Payment\Block\Payment\Method\CreditCard\CreditCard';

}