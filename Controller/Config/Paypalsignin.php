<?php
/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\WebposPaypal\Controller\Config;

/**
 * Class Paypalsignin
 * @package Magestore\WebposPaypal\Controller\Adminhtml\Config
 */
class Paypalsignin extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface  $resourceConfig
     */
    protected $resourceConfig;

    /**
     * @var \Magestore\WebposPaypal\Model\Paypal $paypal
     */
    protected $paypal;

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     */
    protected $cacheTypeList;

    /**
     * AbstractAction constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Config\ConfigResource\ConfigInterface  $resourceConfig
     * @param \Magestore\WebposPaypal\Model\Paypal $paypal
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface  $resourceConfig,
        \Magestore\WebposPaypal\Model\Paypal $paypal,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
    ){
        parent::__construct($context);
        $this->resourceConfig = $resourceConfig;
        $this->paypal = $paypal;
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * Store authorization code
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $authCode = $this->getRequest()->getParam('code');
        $tokenInfo = '';
        $response = '';
        if($authCode) {
            try {
                $tokenInfo = $this->paypal->getTokenInfo($authCode);
            } catch (\PayPal\Exception\PayPalConnectionException $ex) {
                $response = $ex->getCode().' '.$ex->getMessage();
            } catch (\Exception $ex) {
                $response = $ex->getMessage();
            }
            if($tokenInfo) {
                $accessToken = $tokenInfo->access_token;
                $refreshToken = $tokenInfo->refresh_token;
                if ($accessToken) {
                    $this->resourceConfig->saveConfig(
                        'webpos/payment/paypal/access_token',
                        $accessToken,
                        \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                        \Magento\Store\Model\Store::DEFAULT_STORE_ID
                    );
                }
                if ($refreshToken) {
                    $this->resourceConfig->saveConfig(
                        'webpos/payment/paypal/refresh_token',
                        $refreshToken,
                        \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                        \Magento\Store\Model\Store::DEFAULT_STORE_ID
                    );
                }
                $this->cacheTypeList->cleanType('config');
                $html = $this->getClosePopup();
                $response = $html;
            } else {
                $response = __('Please try again');
            }
        } else {
            $response = __('Please try again');
        }
        return $this->getResponse()->setBody($response);
    }

    /**
     * get closed popup html
     */
    public function getClosePopup()
    {
        $html = "<div id='my-timer'>".
                      __('Successfully, please save your magento config. Window will close in %1 seconds', '<b id=\'show-time\'>5</b>')
                ."</div>
                <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js'></script>
                <script type='text/javascript'> 
                    jQuery(function(){
                            window.setInterval(function() {
                                var timeCounter = jQuery('b[id=show-time]').html();
                                var updateTime = eval(timeCounter)- eval(1);
                                $('b[id=show-time]').html(updateTime);
                                if(updateTime == 0){
                                   window.close(); 
                                }
                            }, 1000);

                    });
            	</script>";
        return $html;
    }
}