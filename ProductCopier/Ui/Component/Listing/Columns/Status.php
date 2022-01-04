<?php
/**
 * @package     MP\ProductCopier
 * @version     1.0.0
 * @author      Mahesh Patel
 * @copyright   Copyright © 2021 Mahesh Patel.
 */

namespace MP\ProductCopier\Ui\Component\Listing\Columns;

use Magento\Ui\Component\Listing\Columns\Column;
use MP\ProductCopier\Model\Report\Status as LogStatus;

/**
 * Class Link
 * @package MP\ProductCopier\Ui\Component\Listing\Columns
 */
class Status extends Column
{

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $class = $label ='undefined';
                if ($item['status'] && $item['status'] == LogStatus::SUCCESS_STATUS) {
                    $class = 'notice';
                    $label = 'Success';
                } 
                if($item['status'] == LogStatus::PENDING_STATUS) {
                    $class = 'minor';
                    $label = 'Pending';
                } 
                if($item['status'] && $item['status'] == LogStatus::ERROR_STATUS) {
                    $class = 'critical';
                    $label = 'Error';
                }
                $item['status'] = '<span class="grid-severity-'
                    . $class .'">'. $label .'</span>';
            }
        }
        return $dataSource;
    }
}
