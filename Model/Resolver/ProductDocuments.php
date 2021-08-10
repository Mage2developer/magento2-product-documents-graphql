<?php
declare(strict_types=1);

namespace Mage2\ProductDocs\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mage2\ProductDocs\Model\ResourceModel\Documents\CollectionFactory;

class ProductDocuments implements ResolverInterface
{
    private $documentsCollectionFactory;

    public function __construct(
        CollectionFactory $documentsCollectionFactory
    ) {
        $this->documentsCollectionFactory = $documentsCollectionFactory;
    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $this->checkArguments($args);
        $documentData = $this->getDocumentData($args);

        return $documentData;
    }

    private function checkArguments(array $args): array
    {
        if (!isset($args['product_id']) && !isset($args['store_id'])) {
            throw new GraphQlInputException(__('"product id and store id should be specified'));
        }

        return $args;
    }

    private function getDocumentData($args) {
        $items = [];
        try {
            $collection = $this->documentsCollectionFactory->create();
            $collection->addFieldToFilter('product_ids', ['finset' => $args['product_id']]);
            $collection->addFieldToFilter(
                'store_ids',
                [
                    ['eq' => 0],
                    ['finset' => $args['store_id']]
                ]
            );
            $collection->setOrder('sort_order', 'ASC');

            $totalCount = $collection->getSize();

            if ($totalCount > 0) {
                $items = $collection->getData();
            }
        }
        catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        $documentData = ['totalCount' => $totalCount, 'items' => $items];
        return $documentData;
    }
}
