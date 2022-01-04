<?php
 /**
 * @package     MP\ProductCopier
 * @version     1.0.0
 * @author      Mahesh Patel
 * @copyright   Copyright © 2021 Mahesh Patel.
 */

namespace MP\ProductCopier\Controller\Adminhtml\Report;
use MP\ProductCopier\Controller\Adminhtml\Report as ReportController;

/**
 * Class Index
 *
 * @package MP\ProductCopier\Controller\Adminhtml\Report
 */
class Index extends ReportController
{
    /**
     * execute the action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->_setPageData();
        return $this->getResultPage();
    }
}