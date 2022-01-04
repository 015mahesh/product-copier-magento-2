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
 * Class Queue
 * @package MP\ProductCopier\Model
 */
class Queue extends AbstractModel
{
    /**
     * Initialize resources
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('MP\ProductCopier\Model\ResourceModel\Queue');
    }

    /**
     * @param $entityId
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addToQueue($data)
    {
        if (empty($data)) {
            return;
        }
        $this->setData($data);
        $this->save();
        return;
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

    /**
     * @param $collectionArr
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function resetMultiReports($collectionArr)
    {
        if (empty($collectionArr)) {
            return;
        }
        $collectionIds = implode(', ', $collectionArr);
        $update_data = ['status' => 0, 'attempt' => 0, 'response' => ''];
        $this->getResource()->getConnection()->update(
            $this->getResource()->getMainTable(),
            $update_data,
            "{$this->getIdFieldName()} in ({$collectionIds})"
        );
    }
}
