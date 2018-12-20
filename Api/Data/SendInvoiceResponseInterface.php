<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\WebposPaypal\Api\Data;

/**
 * Interface SendInvoiceResponseInterface
 * @package Magestore\WebposPaypal\Api|Data
 */
interface SendInvoiceResponseInterface
{

    /**#@+
     * Constants for field names
     */
    const ERROR = 'error';
    const MESSAGE = 'message';
    const INVOICE = 'invoice';

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
     * Get invoice
     *
     * @api
     * @return \Magestore\WebposPaypal\Api\Data\InvoiceInterface
     */
    public function getInvoice();

    /**
     * Set invoice
     *
     * @api
     * @param \Magestore\WebposPaypal\Api\Data\InvoiceInterface $invoice
     * @return $this
     */
    public function setInvoice($invoice);
}
