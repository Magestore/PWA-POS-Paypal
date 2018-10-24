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
interface DirectResponseInterface
{

    /**#@+
     * Constants for field names
     */
    const ERROR = 'error';
    const MESSAGE = 'message';
    const TRANSACTION_ID = 'transaction_id';

    /**
     * Get error
     *
     * @api
     * @return boolean
     */
    public function getError();

    /**
     * Set error
     *
     * @api
     * @param boolean $error
     * @return $this
     */
    public function setError($error);

    /**
     * Get Message
     *
     * @api
     * @return string
     */
    public function getMessage();

    /**
     * Set message
     *
     * @api
     * @param string $message
     * @return $this
     */
    public function setMessage($message);

    /**
     * Get transactionId
     *
     * @api
     * @return string
     */
    public function getTransactionId();

    /**
     * Set transactionId
     *
     * @api
     * @param string $transactionId
     * @return $this
     */
    public function setTransactionId($transactionId);


}
