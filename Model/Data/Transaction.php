<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\WebposPaypal\Model\Data;

/**
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Transaction extends \Magento\Framework\Api\AbstractExtensibleObject implements \Magestore\WebposPaypal\Api\Data\TransactionInterface
{
    /**
     * Get total
     *
     * @api
     * @return float|null
     */
    public function getTotal(){
        return $this->_get(self::TOTAL);
    }

    /**
     * Set total
     *
     * @api
     * @param float $total
     * @return $this
     */
    public function setTotal($total){
        return $this->setData(self::TOTAL, $total);
    }

    /**
     * Get currency
     *
     * @api
     * @return string|null
     */
    public function getCurrency(){
        return $this->_get(self::CURRENCY);
    }

    /**
     * Set currency
     *
     * @api
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency){
        return $this->setData(self::CURRENCY, $currency);
    }

    /**
     * Get description
     *
     * @api
     * @return string|null
     */
    public function getDescription(){
        return $this->_get(self::DESCRIPTION);
    }

    /**
     * Set description
     *
     * @api
     * @param string $description
     * @return $this
     */
    public function setDescription($description){
        return $this->setData(self::DESCRIPTION, $description);
    }
}