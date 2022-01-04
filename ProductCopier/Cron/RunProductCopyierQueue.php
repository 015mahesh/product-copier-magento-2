<?php
 /**
 * @package     MP\ProductCopier
 * @version     1.0.0
 * @author      Mahesh Patel
 * @copyright   Copyright © 2021 Mahesh Patel.
 */

namespace MP\ProductCopier\Cron;
use MP\ProductCopier\Helper\Config;

class RunProductCopyierQueue
{
    protected $processQueue;

    protected $isModuleEnable;

    protected $isCronEnable;

    protected $maxAttempt;

    protected $cronLimit;

    protected $logger;

    protected $productFactory;

    protected $productCopier;

    public function __construct(
        \MP\ProductCopier\Model\ResourceModel\Queue\CollectionFactory $processQueue,
        Config $moduleConfig,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\Product\Copier $productCopier
    ) {
        $this->processQueue = $processQueue;
        $this->moduleConfig = $moduleConfig;
        $this->productFactory = $productFactory;
        $this->productCopier = $productCopier;

        $this->isModuleEnable = $this->moduleConfig->getGeneralConfig('enable');
        $this->isCronEnable = $this->moduleConfig->getDeveloperConfig('enable_cron');
        $this->maxAttempt = 3;
        $this->cronLimit = 10;

        //Custom Cron
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/mp_product_copier.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $this->logger = $logger;
    }

    public function execute()
    {
        if($this->isModuleEnable && $this->isCronEnable){
            $queueCollection = $this->processQueue->create()
                    ->addFieldToFilter('status', array('in' => array(0,2)))
                    ->addFieldToFilter('attempt', array('lt' => $this->maxAttempt))
                    ->setPageSize($this->cronLimit)->setCurPage(1);
            if(count($queueCollection) > 0){
                foreach ($queueCollection as $process) {
                    try{
                        $entityType = $process->getEntityType();
                        $entityId = $process->getEntityId();
                        if($entityType == 'simple'){
                            $product = $this->getProduct($entityId);
                            if($product){
                                //Main Product Info
                                $mainProductName = $product->getName();
                                $mainProductSku = $product->getSku();
                                $mainProductCondition = $product->getCondition();
                                $mainProductUrl = $product->getUrl();
                                $mainProductMetaTitle = $product->getMetaTitle();

                                //Used Product Info
                                $usedProduct = $this->productCopier->copy($product);
                                $usedProduct->setName(str_replace("New","Used",$mainProductName));
                                $usedProduct->setSku(str_replace("New","Used",$mainProductSku));
                                $usedProduct->setCondition('used');
                                $usedProduct->setUrl(str_replace("new","used",$mainProductUrl));
                                $usedProduct->setMetaTitle(str_replace("new","used",$mainProductMetaTitle));
                                $usedProduct->save();
                                $this->logger->debug("Used Product Created SKU: ".$usedProduct->getSku());

                                //Used Product Info
                                $refurbished = $this->productCopier->copy($product);
                                $refurbished->setName(str_replace("New","Refurbished",$mainProductName));
                                $refurbished->setSku(str_replace("New","Refurbished",$mainProductSku));
                                $refurbished->setCondition('used');
                                $refurbished->setUrl(str_replace("new","refurbished",$mainProductUrl));
                                $refurbished->setMetaTitle(str_replace("new","refurbished",$mainProductMetaTitle));
                                $refurbished->save();
                                $this->logger->debug("Refurbished Product Created SKU: ".$refurbished->getSku());

                                $response['status']=1;
                                $response['response']='Success';
                                $this->updateProcess($process,$response);
                                $this->logger->debug("Product ID: $entityId : Processed");
                            } else { $this->logger->debug("Product ID: $entityId : Not Found in Magento"); }
                        } else{
                            $this->logger->debug("Given Entity Type has no defination in Product Copier module.");
                        }
                    }
                     catch(\Exception $e){
                        $this->logger->error("Entity ID: $entityId :".$e->getMessage());
                    }
                }
            }
        } else {
            $this->logger->debug("Product Copier module or Cron is Disabled. Please check Store > Configuration > MP Product Copier Configuration");
        }
    }

    public function getProduct($id)
    {
        return $this->productFactory->create()->load($id);
    }

    public function updateProcess($process, $data)
    {
        try{
            $attempt = (int)$process->getAttempt();
            $attempt += 1;
            $updated_at = date('Y-m-d H:i:s');
            $data['datetime'] = $updated_at;
            $data['attempt'] = $attempt;
            if($data['status'] == 0){
                $data['status'] = 2;
            }
            $process->setAttempt($data['attempt']);
            $process->setStatus($data['status']);
            $process->setDatetime($data['datetime']);
            $process->setResponse($data['response']);
            $processData = $process->getData();
            $process->save();  
        } catch(\Exception $e){
            $this->logger->error("Error while updating queue process:".$e->getMessage());
        }
    }
}