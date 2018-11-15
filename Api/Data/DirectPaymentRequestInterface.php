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
interface DirectPaymentRequestInterface
{

    const QUOTE_ID = 'quote_id';
    const METHOD = 'method';
    const TITLE = 'title';
    const AMOUNT_PAID = 'amount_paid';
    const BASE_AMOUNT_PAID = 'base_amount_paid';
    const REFERENCE_NUMBER = 'reference_number';
    const PAYMENT_DATE = 'payment_date';
    const IS_PAID = 'is_paid';
    const CARD_TYPE = 'card_type';
    const CC_OWNER = 'cc_owner';
    const CC_TYPE = 'cc_type';
    const CC_NUMBER = 'cc_number';
    const CC_CID = 'cc_cid';
    const CC_EXP_MONTH = 'cc_exp_month';
    const CC_EXP_YEAR = 'cc_exp_year';

    /**
     * Get Quote Id
     *
     * @return string|null
     */
    public function getQuoteId();
    /**
     * Set Quote Id
     *
     * @param string $quoteId
     * @return $this
     */
    public function setQuoteId($quoteId);

    /**
     * Get Method
     *
     * @return string|null
     */
    public function getMethod();
    /**
     * Set Method
     *
     * @param string $method
     * @return $this
     */
    public function setMethod($method);

    /**
     * Get Title
     *
     * @return string|null
     */
    public function getTitle();
    /**
     * Set Title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);
    /**
     * Get Amount Paid
     *
     * @return float|null
     */
    public function getAmountPaid();
    /**
     * Set Amount Paid
     *
     * @param float $amountPaid
     * @return $this
     */
    public function setAmountPaid($amountPaid);

    /**
     * Get Base Amount Paid
     *
     * @return float|null
     */
    public function getBaseAmountPaid();
    /**
     * Set Base Amount Paid
     *
     * @param float $baseAmountPaid
     * @return $this
     */
    public function setBaseAmountPaid($baseAmountPaid);

    /**
     * Get Reference Number
     *
     * @return string|null
     */
    public function getReferenceNumber();
    /**
     * Set Reference Number
     *
     * @param string|null $referenceNumber
     * @return $this
     */
    public function setReferenceNumber($referenceNumber);

    /**
     * Get Payment Date
     *
     * @return string|null
     */
    public function getPaymentDate();
    /**
     * Set Payment Date
     *
     * @param string|null $paymentDate
     * @return $this
     */
    public function setPaymentDate($paymentDate);

    /**
     * Get Is Paid
     *
     * @return int|null
     */
    public function getIsPaid();
    /**
     * Set Is Paid
     *
     * @param int|null $isPaid
     * @return $this
     */
    public function setIsPaid($isPaid);

    /**
     * Get Card Type
     *
     * @return string|null
     */
    public function getCardType();
    /**
     * Set Card Type
     *
     * @param string $cardType
     * @return $this
     */
    public function setCardType($cardType);

    /**
     * Get Cc Owner
     *
     * @return string|null
     */
    public function getCcOwner();
    /**
     * Set Cc Owner
     *
     * @param string|null $ccOwner
     * @return $this
     */
    public function setCcOwner($ccOwner);

    /**
     * Get Cc Type
     *
     * @return string|null
     */
    public function getCcType();
    /**
     * Set Cc Type
     *
     * @param string|null $ccType
     * @return $this
     */
    public function setCcType($ccType);

    /**
     * Get Cc Number
     *
     * @return string|null
     */
    public function getCcNumber();
    /**
     * Set Cc Number
     *
     * @param string|null $ccNumber
     * @return $this
     */
    public function setCcNumber($ccNumber);

    /**
     * Get Cc Cid
     *
     * @return string|null
     */
    public function getCcCid();
    /**
     * Set Cc Cid
     *
     * @param string|null $ccCid
     * @return $this
     */
    public function setCcCid($ccCid);

    /**
     * Get Cc ExpMonth
     *
     * @return string|null
     */
    public function getCcExpMonth();
    /**
     * Set Cc ExpMonth
     *
     * @param string|null $ccExpMonth
     * @return $this
     */
    public function setCcExpMonth($ccExpMonth);

    /**
     * Get Cc ExpYear
     *
     * @return string|null
     */
    public function getCcExpYear();
    /**
     * Set Cc ExpYear
     *
     * @param string|null $ccExpYear
     * @return $this
     */
    public function setCcExpYear($ccExpYear);
}