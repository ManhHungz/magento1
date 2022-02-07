<?php
declare(strict_types=1);

namespace Magestore\Multivendor\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Catalog\Api\ProductRepositoryInterface;
/**
 * Product collection resolver
 */
class Vendor implements ResolverInterface
{
    protected $productRepository;
    protected $searchCriteriaBuilder;
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }
    public function resolve(
       Field $field,
       $context,
       ResolveInfo $info,
       array $value = null,
       array $args = null
    ) {
       $productId = $this->getProductId($args);
       $productData = $this->getproductData($productId);
       return $productData;
    }
    /**
     * @param array $args
     * @return int
     * @throws GraphQlInputException
     */
    private function getProductId(array $args): int {
       if (!isset($args['id_product'])) {
           throw new GraphQlInputException(__('product id should be specified'));
       }
       return (int) $args['id_product'];
    }
    /**
     * @param int $productId
     * @return array
     * @throws GraphQlNoSuchEntityException
     */
    private function getproductData(int $productId): array
    {
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$listProduct = $objectManager->create('Magento\Catalog\Model\Product')->getCollection();
$productIdArray = [1, 2, 3];
            //$listProduct->getSelect()->where('e.entity_id in (?)',$productIdArray);
//$listProduct->addAttributeToFilter('entity_id', ['eq' => '2']);
$listProduct->addAttributeToFilter('entity_id', ['in' => $productIdArray]);
       //      $productModel = $listProduct->getFirstItem();
       //      var_dump($productModel->getData('sku'));die;

       // try {
       //     $searchCriteria = $this->searchCriteriaBuilder->addFilter('entity_id', $productId,'eq')->create();
       //      $productList = $this->productRepository->getList($searchCriteria)->getItems();
       //      $productOrder['allProducts'] = [];
       //      foreach($productList as $product) {
       //          $product_id = $product->getId();
       //          $productOrder['allProducts'][$product_id]['sku'] = $product->getSku();
       //          $productOrder['allProducts'][$product_id]['name'] = $product->getName();
       //          $productOrder['allProducts'][$product_id]['price'] = $product->getPrice();
       //     }
       // } catch (NoSuchEntityException $e) {
       //     throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
       // }
$proeucs = [];
foreach ($listProduct as $key => $product) {
  $proeucs[] = $product;
}
return $proeucs;
return $listProduct->getData();
       return $productOrder;
    }
}