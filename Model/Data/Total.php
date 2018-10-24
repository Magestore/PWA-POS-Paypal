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
class Total extends \Magento\Framework\Api\AbstractExtensibleObject implements \Magestore\WebposPaypal\Api\Data\TotalInterface
{
    /**
     * Get code
     *
     * @api
     * @return string|null
     */
    public function getCode(){
        return $this->_get(self::CODE);
    }

    /**
     * Set code
     *
     * @api
     * @param string $code
     * @return $this
     */
    public function setCode($code){
        return $this->setData(self::CODE, $code);
    }

    /**
     * Get amount
     *
     * @api
     * @return string|null
     */
    public function getAmount(){
        return $this->_get(self::AMOUNT);
    }

    /**
     * Set amount
     *
     * @api
     * @param string $amount
     * @return $this
     */
    public function setAmount($amount){
        return $this->setData(self::AMOUNT, $amount);
    }
}