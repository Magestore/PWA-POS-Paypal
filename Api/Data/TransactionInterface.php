<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\WebposPaypal\Api\Data;

/**
 * Interface TransactionInterface
 * @package Magestore\WebposPaypal\Api|Data
 */
interface TransactionInterface
{

    /**#@+
     * Constants for field names
     */
    const TOTAL = 'total';
    const CURRENCY = 'currency';
    const DESCRIPTION = 'description';
    /**#@-*/

    /**
     * Get total
     *
     * @api
     * @return float|null
     */
    public function getTotal();

    /**
     * Set total
     *
     * @api
     * @param float $total
     * @return $this
     */
    public function setTotal($total);

    /**
     * Get currency
     *
     * @api
     * @return string|null
     */
    public function getCurrency();

    /**
     * Set currency
     *
     * @api
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency);

    /**
     * Get description
     *
     * @api
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     *
     * @api
     * @param string $description
     * @return $this
     */
    public function setDescription($description);
}
