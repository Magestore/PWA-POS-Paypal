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
class SendInvoiceResponse extends \Magento\Framework\Api\AbstractExtensibleObject implements
    \Magestore\WebposPaypal\Api\Data\SendInvoiceResponseInterface
{
    public function getError()
    {
        return $this->_get(self::ERROR);
    }

    public function setError($error)
    {
        return $this->setData(self::ERROR, $error);
    }

    public function getMessage()
    {
        return $this->_get(self::MESSAGE);
    }

    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }

    public function getInvoice()
    {
        return $this->_get(self::INVOICE);
    }

    public function setInvoice($invoice)
    {
        return $this->setData(self::INVOICE, $invoice);
    }
}
