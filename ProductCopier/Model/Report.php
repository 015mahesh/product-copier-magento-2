<?php
/**
 * @package     MP\ProductCopier
 * @version     1.0.0
 * @author      Mahesh Patel
 * @copyright   Copyright © 2021 Mahesh Patel.
 */

namespace MP\ProductCopier\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

/**
 * Class Report
 * @package MP\ProductCopier\Model
 */
class Report extends AbstractModel
{
    /**
     * Report constructor.
     *
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        Registry $registry
    ){
        parent::__construct($context, $registry);
    }

    /**
     * Initialize resources
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('MP\ProductCopier\Model\ResourceModel\Report');
    }

    /**
     * @param $collectionArr
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteMultiReports($collectionArr)
    {
        if (empty($collectionArr)) {
            return;
        }
        $collectionIds = implode(', ', $collectionArr);
        $this->getResource()->getConnection()->delete(
            $this->getResource()->getMainTable(),
            "{$this->getIdFieldName()} in ({$collectionIds})"
        );
    }
}
