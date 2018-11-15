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
class Item extends \Magento\Framework\Api\AbstractExtensibleObject implements \Magestore\WebposPaypal\Api\Data\ItemInterface
{

    /**
     * Get name
     *
     * @api
     * @return string|null
     */
    public function getName(){
        return $this->_get(self::NAME);
    }

    /**
     * Set name
     *
     * @api
     * @param string $name
     * @return $this
     */
    public function setName($name){
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get qty
     *
     * @api
     * @return string|null
     */
    public function getQty(){
        return $this->_get(self::QTY);
    }

    /**
     * Set qty
     *
     * @api
     * @param string $qty
     * @return $this
     */
    public function setQty($qty){
        return $this->setData(self::QTY, $qty);
    }

    /**
     * Get  unit price
     *
     * @api
     * @return string|null
     */
    public function getUnitPrice(){
        return $this->_get(self::UNIT_PRICE);
    }

    /**
     * Set unit price
     *
     * @api
     * @param string $unitPrice
     * @return $this
     */
    public function setUnitPrice($unitPrice){
        return $this->setData(self::UNIT_PRICE, $unitPrice);
    }

    /**
     * Get tax percent
     *
     * @api
     * @return string|null
     */
    public function getTaxPercent(){
        return $this->_get(self::TAX_PERCENT);
    }

    /**
     * Set tax percent
     *
     * @api
     * @param string $taxPercent
     * @return $this
     */
    public function setTaxPercent($taxPercent){
        return $this->setData(self::TAX_PERCENT, $taxPercent);
    }
}