<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\WebposPaypal\Api\Data;

/**
 * Interface TotalInterface
 * @package Magestore\WebposPaypal\Api|Data
 */
interface TotalInterface
{

    /**#@+
     * Constants for field names
     */
    const CODE = 'code';
    const AMOUNT = 'amount';
    /**#@-*/

    /**
     * Get total code
     *
     * @api
     * @return string|null
     */
    public function getCode();

    /**
     * Set total code
     *
     * @api
     * @param string $code
     * @return $this
     */
    public function setCode($code);

    /**
     * Get total amount
     *
     * @api
     * @return string|null
     */
    public function getAmount();

    /**
     * Set total amount
     *
     * @api
     * @param string $amount
     * @return $this
     */
    public function setAmount($amount);
}
