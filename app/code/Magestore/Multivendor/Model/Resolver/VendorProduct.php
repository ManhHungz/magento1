<?php
declare(strict_types=1);

namespace Magestore\Multivendor\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class VendorProduct implements ResolverInterface
{
    protected $productInterface;
	protected $_productCollectionFactory;
    protected $_vendorcollection;
    protected $_productcollectionofvendor;
    protected $_objectManager;
    protected $_productFactory;

    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productInterface,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,     
        \Magento\Backend\Block\Template\Context $context,
        \Magestore\Multivendor\Model\ResourceModel\Vendor\Collection $vendorcollection,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magestore\Multivendor\Block\Adminhtml\Vendor\Edit\Tab\Product $productcollectionofvendor,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->productInterface = $productInterface;
		$this->_productCollectionFactory = $productCollectionFactory; 
        $this->_productFactory = $productFactory;   
        $this->_vendorcollection = $vendorcollection;
        $this->_productcollectionofvendor = $productcollectionofvendor;
        $this->_objectManager = $objectManager;
    }
    public function resolve(
       Field $field,
       $context,
       ResolveInfo $info,
       array $value = null,
       array $args = null
    ) {
       $venId = $this->getVendorId($args);
       $vendorData = $this->getVendorData($venId);
       return $vendorData;
    }
    /**
     * @param array $args
     * @return int
     * @throws GraphQlInputException
     */
    private function getVendorId(array $args):int {
       if (!isset($args['vendorid'])) {
           throw new GraphQlInputException(__('vendor id should be specified'));
       }
       return (int) $args['vendorid'];
    }
    /**
     * @param int $catId
     * @return array
     * @throws GraphQlNoSuchEntityException
     */
    public function getVendorData(int $venId):array{
        $vencollection = $this->_vendorcollection;
		$vendorproCollection = $vencollection->addFieldToFilter('vendor_id',['eq'=>$venId]);
		$venproduct['vendetail'] = [];

		foreach ($vendorproCollection as $ven ) {
			$ven_id = $ven->getId();
            $venproduct['vendetail'][$ven_id]['id'] = $ven->getId();
            $venproduct['vendetail'][$ven_id]['name'] = $ven->getName();
            $venproduct['vendetail'][$ven_id]['code'] = $ven->getVendorCode();
            $venproduct['vendetail'][$ven_id]['address'] = $ven->getAddress();
            $venproduct['vendetail'][$ven_id]['phone'] = $ven->getPhone();
            $venproduct['vendetail'][$ven_id]['status'] = $ven->getStatus();

		}
        
        $collectpro = $this->getProductCollection($venId);
        $collectofpro = [];
        foreach($collectpro as $col){
            $collectofpro[] = ['entity_id' => $col->getId(),
                            'attribute_set_id' => $col->getAttributeSetId(),
                            'type_id' => $col->getTypeId(),
                            'sku' => $col->getSku(),
                            ];
            
        }
        $venproduct['vendetail'][$ven_id]['vendorcollection'] = $collectofpro;
		//var_dump($collectofpro);
        return $venproduct;
        
    }
    public function getProductCollection($venId)
    {
        $vendorProductModel = $this->_objectManager->create('Magestore\Multivendor\Model\VendorProduct')->load($venId,'vendor_id');
            $productIds = $vendorProductModel->getProductIds();
            $productIdArray = explode(',', $productIds);
            $listProduct = $this->_productFactory->create()->getCollection();
            $listProduct->setPageSize(5)
                        ->setCurPage(1);
            $listProduct->getSelect()->where('e.entity_id in (?)',$productIdArray);
            
       //var_dump($listProduct->getData());
        return $listProduct;
    }
}