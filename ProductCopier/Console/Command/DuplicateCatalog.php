<?php
/**
 * @package     MP\ProductCopier
 * @version     1.0.0
 * @author      Mahesh Patel
 * @copyright   Copyright © 2021 Mahesh Patel.
 */
namespace MP\ProductCopier\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use MP\ProductCopier\Model\QueueFactory;

/**
 * Class DuplicateCatalog
 */
class DuplicateCatalog extends Command
{
    const NAME = 'type';

    protected $productCollectionFactory;

    protected $logger;

    protected $queueModel;

    protected $QueueFactory;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        QueueFactory $QueueFactory,
        $name = null
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->queueFactory = $QueueFactory;
        parent::__construct($name);

        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/mp_product_copier.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $this->logger = $logger;
        $this->queueModel = $this->queueFactory->create();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('catalog:product:copier');
        $this->setDescription('Copy all simple products and update attributes.');
        $this->addOption(
            self::NAME,
            null,
            InputOption::VALUE_REQUIRED,
            'Name'
        );

        parent::configure();
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return null|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($name = $input->getOption(self::NAME)) {
            $output->writeln('<info>Selected Type is `' . $name . '`</info>');
        }

        $products = $this->getSimpleProducts();
        $this->logger->debug('Products found for copy batch: '.count($products));
        foreach ($products as $product) {
            try {
                $this->logger->debug('Product is being added to copy batch SKU: '.$product->getSku());
                $data = array(
                'entity_type' => 'simple',
                'entity_id' => $product->getId(),
                'response' => '',
                'attempt' => 0,
                'datetime'=>date('Y-m-d H:i:s'),
                'status' => 0
            );
            $this->queueModel->addToQueue($data);
            $this->logger->debug('Product added to copy batch SKU: '.$product->getSku());
            } catch (\Exception $e) {
                $this->logger->debug('Error while adding into batch SKU: '.$product->getSku());
                $this->logger->debug($e);
            }
        }

        $output->writeln('<info>All Simple Products were batched and will start processing after 5 minutes</info>');
        //$output->writeln('<error>An error encountered.</error>');
        $output->writeln('<comment>Check Catalog > Product Copier Batch Report > View Report</comment>');
        $output->writeln('<comment>Or Check Catalog > Inventory > Prodcuts</comment>');
    }

    private function getSimpleProducts()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('type_id', \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE);
        $collection->addAttributeToFilter('condition', 'new');
        $collection->getSelect()->order('created_at', \Magento\Framework\DB\Select::SQL_DESC);
        $collection->getSelect()->joinLeft(
            array('product_copier' => $collection->getTable('mp_product_copier_queue_list')),
            'e.entity_id = product_copier.entity_id',
            array('id')
        )->where("product_copier.id IS NULL");
        $collection->getSelect()->limit(5000);
        return $collection;
    }
}
