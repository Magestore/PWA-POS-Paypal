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
class DirectResponse extends \Magento\Framework\Api\AbstractExtensibleObject implements
    \Magestore\WebposPaypal\Api\Data\DirectResponseInterface
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

    public function getTransactionId()
    {
        return $this->_get(self::TRANSACTION_ID);
    }

    public function setTransactionId($transactionId)
    {
        return $this->setData(self::TRANSACTION_ID, $transactionId);
    }
}
