<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magestore\WebposPaypal\Model\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 *
 * Authorize.net Payment Action Dropdown source
 */
class PaymentActions implements ArrayInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'Sale',
                'label' => __('Sale'),
            ],
            [
                'value' => "Authorization",
                'label' => __('Authorization')
            ],
            [
                'value' => "Order",
                'label' => __('Order')
            ]
        ];
    }
}