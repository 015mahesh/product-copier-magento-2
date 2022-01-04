<?php
 /**
 * @package     MP\ProductCopier
 * @version     1.0.0
 * @author      Mahesh Patel
 * @copyright   Copyright © 2021 Mahesh Patel.
 */
namespace MP\ProductCopier\Controller\Adminhtml\Report;

use MP\ProductCopier\Controller\Adminhtml\Report as ReportController;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Controller\ResultFactory;
use MP\ProductCopier\Model\QueueFactory as ReportFactory;
use MP\ProductCopier\Model\ResourceModel\Queue\CollectionFactory as ReportCollectionFactory;

/**
 * Class MassDelete
 *
 * @package MP\ProductCopier\Controller\Adminhtml\Report
 */
class MassDelete extends ReportController
{
    /**
     * Mass Action Filter
     *
     * @var Filter
     */
    protected $_filter;

    /**
     * Report Collection
     *
     * @var ReportCollectionFactory
     */
    protected $_collectionFactory;

    /**
     * Report Model
     *
     * @var ReportFactory $_reportFactory
     */
    protected $_reportFactory;

    /**
     * @param Context                 $context
     * @param ReportFactory           $reportFactory
     * @param LayoutFactory           $layoutFactory
     * @param PageFactory             $resultPageFactory
     * @param Filter                  $filter
     * @param ForwardFactory          $resultForwardFactory
     * @param ReportCollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        ReportFactory $reportFactory,
        LayoutFactory $layoutFactory,
        PageFactory $resultPageFactory,
        Filter $filter,
        ForwardFactory $resultForwardFactory,
        ReportCollectionFactory $collectionFactory
    ) {
        $this->_reportFactory = $reportFactory;
        $this->_filter            = $filter;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $reportFactory, $layoutFactory, $resultPageFactory, $resultForwardFactory);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collectionFilter = $this->_filter->getCollection($this->_collectionFactory->create());
        /** @var \MP\ProductCopier\Model\Report $reportModel */
        $reportModel = $this->_reportFactory->create();
        $collectionSize = $collectionFilter->getSize();
        $collectionArr = [];
        $maxRecord = 5000;
        $count = 0;
        $lastItemId = $collectionFilter->getLastItem()->getId();
        foreach ($collectionFilter as $item) {
            $count++;
            /** @var \MP\ProductCopier\Model\Map $item */
            $collectionArr[] = $item->getId();
            if ($count >= $maxRecord || $item->getId() == $lastItemId) {
                $reportModel->deleteMultiReports($collectionArr);
                $collectionArr = [];
                $count = 0;
            }
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $collectionSize));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MP_ProductCopier::processqueue');
    }
}
