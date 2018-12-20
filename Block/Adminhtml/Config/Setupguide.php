<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magestore\WebposPaypal\Block\Adminhtml\Config;

class Setupguide extends \Magento\Backend\Block\Template
{
    /**
     * @var string
     */
    protected $_template = 'Magestore_WebposPaypal::config/setupguide.phtml';

    /**
     * Get api test url
     * @return string
     */
    public function getTestApiUrl()
    {
        return $this->getUrl('webpospaypal/api/test');
    }
}
