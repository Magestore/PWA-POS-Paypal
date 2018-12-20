<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\WebposPaypal\Model\Data;

use Magestore\WebposPaypal\Api\Data\DirectRequestInterface;

/**
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DirectRequest extends \Magento\Framework\Api\AbstractExtensibleObject implements DirectRequestInterface
{
    /**
     * @return \Magestore\Webpos\Api\Data\Checkout\Order\ItemInterface[]|mixed|null
     */
    public function getItems()
    {
        return $this->_get(self::ITEMS);
    }

    /**
     * @param \Magestore\Webpos\Api\Data\Checkout\Order\ItemInterface[] $items
     * @return $this
     */
    public function setItems($items)
    {
        return $this->setData(self::ITEMS, $items);
    }

    /**
     * @return \Magestore\Webpos\Api\Data\Checkout\Order\AddressInterface[]|mixed|null
     */
    public function getAddresses()
    {
        return $this->_get(self::ADDRESSES);
    }

    /**
     * @param \Magestore\Webpos\Api\Data\Checkout\Order\AddressInterface[] $addresses
     * @return $this
     */
    public function setAddresses($addresses)
    {
        return $this->setData(self::ADDRESSES, $addresses);
    }

    /**
     * @return \Magestore\WebposPaypal\Api\Data\DirectPaymentRequestInterface[]|mixed|null
     */
    public function getPayments()
    {
        return $this->_get(self::PAYMENTS);
    }

    /**
     * @param \Magestore\WebposPaypal\Api\Data\DirectPaymentRequestInterface[] $payment
     * @return $this
     */
    public function setPayments($payment)
    {
        return $this->setData(self::PAYMENTS, $payment);
    }

    /**
     * @return mixed|null|string
     */
    public function getStoreCurrencyCode()
    {
        return $this->_get(self::STORE_CURRENCY_CODE);
    }

    /**
     * @param null|string $storeCurrencyCode
     * @return $this
     */
    public function setStoreCurrencyCode($storeCurrencyCode)
    {
        return $this->setData(self::STORE_CURRENCY_CODE, $storeCurrencyCode);
    }

    /**
     * Get customerEmail
     *
     * @return string|null
     */
    public function getCustomerEmail()
    {
        return $this->_get(self::CUSTOMER_EMAIL);
    }
    /**
     * Set customerEmail
     *
     * @param string|null $customerEmail
     * @return $this
     */
    public function setCustomerEmail($customerEmail)
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    /**
     * @return mixed|null|string
     */
    public function getOrderIncrementId()
    {
        return $this->_get(self::ORDER_INCREMENT_ID);
    }

    /**
     * @param null|string $orderIncrementId
     * @return $this
     */
    public function setOrderIncrementId($orderIncrementId)
    {
        return $this->setData(self::ORDER_INCREMENT_ID, $orderIncrementId);
    }
}
