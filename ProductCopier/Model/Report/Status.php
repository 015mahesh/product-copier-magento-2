<?php
 /**
 * @package     MP\ProductCopier
 * @version     1.0.0
 * @author      Mahesh Patel
 * @copyright   Copyright © 2021 Mahesh Patel.
 */

namespace MP\ProductCopier\Model\Report;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Status
 * @package MP\ProductCopier\Model\Status
 */
class Status implements ArrayInterface
{

    /**@#+
     * constant
     */
    const ERROR_STATUS = 2;
    const SUCCESS_STATUS = 1;
    const PENDING_STATUS = 0;

    /**
     * Options array
     *
     * @var array
     */
    protected $_options = [
        self::ERROR_STATUS => 'Error',
        self::SUCCESS_STATUS => 'Success',
        self::PENDING_STATUS => 'Pending'
    ];

    /**
     * Return options array
     * @return array
     */
    public function toOptionArray()
    {
        $res = [];
        foreach ($this->toArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }
        return $res;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->_options;
    }

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::ERROR_STATUS => 'Success',
            self::SUCCESS_STATUS => 'Error',
            self::PENDING_STATUS => 'Pending'
        ];
    }
}
