<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\WebposPaypal\Api\Data;

/**
 * Interface ItemInterface
 * @package Magestore\WebposPaypal\Api|Data
 */
interface ItemInterface
{

    /**#@+
     * Constants for field names
     */
    const NAME = 'name';
    const QTY = 'qty';
    const UNIT_PRICE = 'unit_price';
    const TAX_PERCENT = 'tax_percent';
    /**#@-*/

    /**
     * Get name
     *
     * @api
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     *
     * @api
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Get qty
     *
     * @api
     * @return string|null
     */
    public function getQty();

    /**
     * Set qty
     *
     * @api
     * @param string $qty
     * @return $this
     */
    public function setQty($qty);

    /**
     * Get  unit price
     *
     * @api
     * @return string|null
     */
    public function getUnitPrice();

    /**
     * Set unit price
     *
     * @api
     * @param string $unitPrice
     * @return $this
     */
    public function setUnitPrice($unitPrice);

    /**
     * Get tax percent
     *
     * @api
     * @return string|null
     */
    public function getTaxPercent();

    /**
     * Set tax percent
     *
     * @api
     * @param string $taxPercent
     * @return $this
     */
    public function setTaxPercent($taxPercent);

}
