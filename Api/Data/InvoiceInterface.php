<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\WebposPaypal\Api\Data;

/**
 * Interface InvoiceInterface
 * @package Magestore\WebposPaypal\Api|Data
 */
interface InvoiceInterface
{

    /**#@+
     * Constants for field names
     */
    const ID = 'id';
    const NUMBER = 'number';
    const QR_CODE = 'qr_code';
    /**#@-*/

    /**
     * Get id
     *
     * @api
     * @return string|null
     */
    public function getId();

    /**
     * Set id
     *
     * @api
     * @param string $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get number
     *
     * @api
     * @return string|null
     */
    public function getNumber();

    /**
     * Set invoice number
     *
     * @api
     * @param string $number
     * @return $this
     */
    public function setNumber($number);

    /**
     * Get qr code
     *
     * @api
     * @return string|null
     */
    public function getQrCode();

    /**
     * Set qr code
     *
     * @api
     * @param string $qrCode
     * @return $this
     */
    public function setQrCode($qrCode);
}
