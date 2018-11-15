<?php
/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\WebposPaypal\Controller\Payment;

/**
 * Class Cancel
 * @package Magestore\WebposPaypal\Controller\Payment
 */
class Cancel extends \Magestore\WebposPaypal\Controller\AbstractAction
{
    /**
     * @return \Magento\Framework\Controller\Result\Page $resultPage
     */
    public function execute()
    {
        return $this->createPageResult();
    }
}
