<?php
/**
 * @package     MP\ProductCopier
 * @version     1.0.0
 * @author      Mahesh Patel
 * @copyright   Copyright © 2021 Mahesh Patel.
 */
namespace MP\ProductCopier\Helper;
use Magento\Framework\App\Helper\Context;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $storeManager;

    protected $scopeConfig;


    const PATH_GENERAL = 'mp_product_copier/general/';

    const PATH_DEVELOPER = 'mp_product_copier/developer/';

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager, 
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Context $context
    )
    {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
    
    public function getConfig($config_path,$store_id = 0)
    {   
        return $this->scopeConfig->getValue($config_path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE,$store_id);
    }

    public function getGeneralConfig($path, $store_id = null){
        if($store_id==null){
            $store_id=$this->getStoreId();
        }
        return $this->getConfig(self::PATH_GENERAL.$path,$store_id);
    }

    public function getDeveloperConfig($path, $store_id = null){
        if($store_id==null){
            $store_id=$this->getStoreId();
        }
        return $this->getConfig(self::PATH_DEVELOPER.$path,$store_id);
    }
}