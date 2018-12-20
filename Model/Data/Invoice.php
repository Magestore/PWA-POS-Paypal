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
class Invoice extends \Magento\Framework\Api\AbstractExtensibleObject implements \Magestore\WebposPaypal\Api\Data\InvoiceInterface
{

    /**
     * Get id
     *
     * @api
     * @return string|null
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * Set id
     *
     * @api
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Get number
     *
     * @api
     * @return string|null
     */
    public function getNumber()
    {
        return $this->_get(self::NUMBER);
    }

    /**
     * Set invoice number
     *
     * @api
     * @param string $number
     * @return $this
     */
    public function setNumber($number)
    {
        return $this->setData(self::NUMBER, $number);
    }

    /**
     * Get qr code
     *
     * @api
     * @return string|null
     */
    public function getQrCode()
    {
        return $this->_get(self::QR_CODE);
    }

    /**
     * Set qr code
     *
     * @api
     * @param string $qrCode
     * @return $this
     */
    public function setQrCode($qrCode)
    {
        return $this->setData(self::QR_CODE, $qrCode);
    }
}
