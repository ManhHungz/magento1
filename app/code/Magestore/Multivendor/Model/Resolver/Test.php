<?php
declare(strict_types=1);

namespace Magestore\Multivendor\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class Test implements ResolverInterface
{
	 protected $productInterface;
	 protected $_productCollectionFactory;
	public function __construct(
		\Magento\Catalog\Api\ProductRepositoryInterface $productInterface,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,        
        array $data = []
	){
		$this->productInterface = $productInterface;
		$this->_productCollectionFactory = $productCollectionFactory;    
	}

	public function resolve(Field $file, $context, ResolveInfo $info,array $value=null, array $args=null){
		
		$productID = $this->getproductID($args);
		//die('aaa');
		$productsData = $this->getproductDATA($productID);
		return $productsData;
	}
	//productID return id product
	//productDATA return data product

	private function getproductID(array $args):int{
		if(!isset($args['id_product'])) {
			throw new GraphQlInputException(__('product id should be specified'));
		}
		return (int) $args['id_product'];
	}

	public function getproductDATA(int $productID):array{
		$collection = $this->_productCollectionFactory->create();//->load($productID);
		$collection->addAttributeToSelect('*');
		$productCollection = $collection->addFieldToFilter('entity_id',['gteq'=>$productID]);
		$productOrder['allProducts'] = [];
		foreach ($productCollection as $pro ) {
			$product_id = $pro->getId();
            $productOrder['allProducts'][$product_id]['sku'] = $pro->getSku();
            $productOrder['allProducts'][$product_id]['name'] = $pro->getName();
		}
		return $productOrder;
	}
}