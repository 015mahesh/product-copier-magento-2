<?php
/**
 * @package     MP\ProductCopier
 * @version     1.0.0
 * @author      Mahesh Patel
 * @copyright   Copyright © 2021 Mahesh Patel.
 */
namespace MP\ProductCopier\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
 
/**
 * Class Conditions
 * @package MP\ProductCopier\Model\Config\Source
 */
class Conditions extends AbstractSource
{
    /**
     * @var array
     */
    protected $_options;
    /**
     * @var DataObject
     */
    protected $_converter;
 
    /**
     * @param DataObject $converter
     */
    public function __construct(
        DataObject $converter
    ) {
        $this->_converter = $converter;
    }
 
    /**
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('New'), 'value'=>'new'],
                ['label' => __('Used'), 'value'=>'used'],
                ['label' => __('Refurbished'), 'value'=>'refurbished']
            ];
        }
        return $this->_options;
    }
 
    /**
     * @param int|string $value
     * @return bool|mixed|string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getOptionText($value)
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }

        return false;
    }
}