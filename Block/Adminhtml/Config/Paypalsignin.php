<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magestore\WebposPaypal\Block\Adminhtml\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Paypalsignin extends Field
{
    /**
     * @var string
     */
    protected $_template = 'Magestore_WebposPaypal::config/paypalsignin.phtml';

    /**
     * @var \Magestore\WebposPaypal\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Url
     */
    protected $urlHelper;

    /**
     * @param Context $context
     * @param \Magestore\WebposPaypal\Helper\Data
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magestore\WebposPaypal\Helper\Data $helper,
        \Magento\Framework\Url $urlHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
        $this->urlHelper = $urlHelper;
    }

    /**
     * Remove scope label
     *
     * @param  AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Return element html
     *
     * @param  AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * Return frontend url
     *
     * @return string
     */
    public function getFrontendUrl($routePath, $routeParams)
    {
        return $this->urlHelper->getUrl($routePath, $routeParams);
    }

    /**
     * Return ajax url for collect button
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->getFrontendUrl('webpospaypal/config/paypalsignin', ['_nosid'=>true]);
    }

    /**
     * Return client Id
     *
     * @return string
     */
    public function getClientId()
    {
        $config = $this->helper->getPaypalConfig();
        $clientId = isset($config['client_id']) ? $config['client_id'] : '';
        return $clientId;
    }

    /**
     * Get Paypal login url
     *
     * @return string
     */
    public function getPaypalLoginUrl()
    {
        $paypalConfig = $this->helper->getPaypalConfig();
        $isSandBox = $paypalConfig['is_sandbox'];
        if ($isSandBox) {
            $url = 'https://www.sandbox.paypal.com/signin/authorize';
        } else {
            $url = 'https://www.paypal.com/signin/authorize';
        }
        return $url;
    }

    /**
     * Generate collect button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'collect_button',
                'label' => __('Sign In'),
            ]
        );

        return $button->toHtml();
    }
}
