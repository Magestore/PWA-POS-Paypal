<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\WebposPaypal\Model\Data;
use Magestore\WebposPaypal\Api\Data\DirectPaymentRequestInterface;

/**
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DirectPaymentRequest extends \Magento\Framework\DataObject implements DirectPaymentRequestInterface
{

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Magestore\Webpos\Model\ResourceModel\Sales\Order\Payment::class);
    }
    /**
     * @inheritdoc
     */
    public function getQuoteId() {
        return $this->getData(self::QUOTE_ID);
    }
    /**
     * @inheritdoc
     */
    public function setQuoteId($quoteId) {
        return $this->setData(self::QUOTE_ID, $quoteId);
    }

    /**
     * @inheritdoc
     */
    public function getMethod() {
        return $this->getData(self::METHOD);
    }
    /**
     * @inheritdoc
     */
    public function setMethod($method) {
        return $this->setData(self::METHOD, $method);
    }

    /**
     * @inheritdoc
     */
    public function getTitle() {
        return $this->getData(self::TITLE);
    }
    /**
     * @inheritdoc
     */
    public function setTitle($title) {
        return $this->setData(self::TITLE, $title);
    }
    /**
     * @inheritdoc
     */
    public function getAmountPaid() {
        return $this->getData(self::AMOUNT_PAID);
    }
    /**
     * @inheritdoc
     */
    public function setAmountPaid($amountPaid) {
        return $this->setData(self::AMOUNT_PAID, round($amountPaid, 4));
    }

    /**
     * @inheritdoc
     */
    public function getBaseAmountPaid() {
        return $this->getData(self::BASE_AMOUNT_PAID);
    }
    /**
     * @inheritdoc
     */
    public function setBaseAmountPaid($baseAmountPaid) {
        return $this->setData(self::BASE_AMOUNT_PAID, round($baseAmountPaid, 4));
    }

    /**
     * @inheritdoc
     */
    public function getReferenceNumber() {
        return $this->getData(self::REFERENCE_NUMBER);
    }
    /**
     * @inheritdoc
     */
    public function setReferenceNumber($referenceNumber) {
        return $this->setData(self::REFERENCE_NUMBER, $referenceNumber);
    }

    /**
     * @inheritdoc
     */
    public function getPaymentDate()
    {
        return $this->getData(self::PAYMENT_DATE);
    }
    /**
     * @inheritdoc
     */
    public function setPaymentDate($paymentDate)
    {
        return $this->setData(self::PAYMENT_DATE, $paymentDate);
    }

    /**
     * @inheritdoc
     */
    public function getIsPaid()
    {
        return $this->getData(self::IS_PAID);
    }
    /**
     * @inheritdoc
     */
    public function setIsPaid($isPaid)
    {
        return $this->setData(self::IS_PAID, $isPaid);
    }
    /**
     * @inheritdoc
     */
    public function getCardType() {
        return $this->getData(self::CARD_TYPE);
    }
    /**
     * @inheritdoc
     */
    public function setCardType($cardType) {
        return $this->setData(self::CARD_TYPE, $cardType);
    }

    public function getCcOwner()
    {
        return $this->getData(self::CC_OWNER);
    }

    public function setCcOwner($ccOwner)
    {
        return $this->setData(self::CC_OWNER, $ccOwner);
    }

    public function getCcType()
    {
        return $this->getData(self::CC_TYPE);
    }

    public function setCcType($ccType)
    {
        return $this->setData(self::CC_TYPE, $ccType);    }

    public function getCcNumber()
    {
        return $this->getData(self::CC_NUMBER);
    }

    public function setCcNumber($ccNumber)
    {
        return $this->setData(self::CC_NUMBER, $ccNumber);
    }

    public function getCcCid()
    {
        return $this->getData(self::CC_CID);
    }

    public function setCcCid($ccCid)
    {
        return $this->setData(self::CC_CID, $ccCid);
    }

    public function getCcExpMonth()
    {
        return $this->getData(self::CC_EXP_MONTH);
    }

    public function setCcExpMonth($ccExpMonth)
    {
        return $this->setData(self::CC_EXP_MONTH, $ccExpMonth);
    }

    public function getCcExpYear()
    {
        return $this->getData(self::CC_EXP_YEAR);
    }

    public function setCcExpYear($ccExpYear)
    {
        return $this->setData(self::CC_EXP_YEAR, $ccExpYear);
    }
}