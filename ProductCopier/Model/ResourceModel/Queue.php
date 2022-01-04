<?php
/**
 * @package     MP\ProductCopier
 * @version     1.0.0
 * @author      Mahesh Patel
 * @copyright   Copyright © 2021 Mahesh Patel.
 */
namespace MP\ProductCopier\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Queue
 *
 * @package MP\ProductCopier\Model\ResourceModel
 */
class Queue extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mp_product_copier_queue_list', 'id');
    }
}
