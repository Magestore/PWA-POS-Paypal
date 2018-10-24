<?php

/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magestore\WebposPaypal\Block\Adminhtml\Config;

class Redirect extends Paypalsignin
{
    /**
     * @var string
     */
    protected $_template = 'Magestore_WebposPaypal::config/redirect.phtml';
}