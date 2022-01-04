<?php
 /**
 * @package     MP\ProductCopier
 * @version     1.0.0
 * @author      Mahesh Patel
 * @copyright   Copyright © 2021 Mahesh Patel.
 */
namespace MP\ProductCopier\Model\ResourceModel\Report;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @package MP\ProductCopier\Model\ResourceModel\Report
 */
class Collection extends AbstractCollection
{
    /**
     * ID Field Name
     *
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'mp_processqueue_report_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'report_collection';


    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('MP\ProductCopier\Model\Report', 'MP\ProductCopier\Model\ResourceModel\Report');
    }
}
