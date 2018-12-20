<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\WebposPaypal\Api\Data;

/**
 * Interface DirectResponseInterface
 * @package Magestore\WebposPaypal\Api|Data
 */
interface DirectRequestInterface
{

    /**#@+
     * Constants for field names
     */
    const ITEMS = 'items';
    const PAYMENTS = 'payment';
    const ADDRESSES = 'addresses';
    const STORE_CURRENCY_CODE = 'store_currency_code';
    const CUSTOMER_EMAIL = 'customer_email';
    const ORDER_INCREMENT_ID = 'order_increment_id';

    /**
     * Get Items
     *
     * @return \Magestore\Webpos\Api\Data\Checkout\Order\ItemInterface[]|null
     */
    public function getItems();
    /**
     * Set Items
     *
     * @param \Magestore\Webpos\Api\Data\Checkout\Order\ItemInterface[] $items
     * @return $this
     */
    public function setItems($items);

    /**
     * Get Addresses
     *
     * @return \Magestore\Webpos\Api\Data\Checkout\Order\AddressInterface[]|null
     */
    public function getAddresses();
    /**
     * Set Addresses
     *
     * @param \Magestore\Webpos\Api\Data\Checkout\Order\AddressInterface[] $addresses
     * @return $this
     */
    public function setAddresses($addresses);

    /**
     * Get Payment
     *
     * @return \Magestore\WebposPaypal\Api\Data\DirectPaymentRequestInterface[]|null
     */
    public function getPayments();
    /**
     * Set Payment
     *
     * @param \Magestore\WebposPaypal\Api\Data\DirectPaymentRequestInterface[] $payment
     * @return $this
     */
    public function setPayments($payment);

    /**
     * Get Store Currency Code
     *
     * @return string|null
     */
    public function getStoreCurrencyCode();
    /**
     * Set Store Currency Code
     *
     * @param string|null $storeCurrencyCode
     * @return $this
     */
    public function setStoreCurrencyCode($storeCurrencyCode);

    /**
     * Get customerEmail
     *
     * @return string|null
     */
    public function getCustomerEmail();
    /**
     * Set customerEmail
     *
     * @param string|null $customerEmail
     * @return $this
     */
    public function setCustomerEmail($customerEmail);

    /**
     * Get OrderIncrementId
     *
     * @return string|null
     */
    public function getOrderIncrementId();
    /**
     * Set OrderIncrementId
     *
     * @param string|null $orderIncrementId
     * @return $this
     */
    public function setOrderIncrementId($orderIncrementId);
}
