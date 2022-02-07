<?php

namespace Magestore\Multivendor\Plugin\Catalog;

class Layer
{
    protected $_request;
    protected $_storeManager;
    protected $_objectManager;
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        $this->_request = $request;
        $this->_storeManager = $storeManager;
        $this->_objectManager = $objectManager;
        $this->_productFactory = $productFactory;
    }

    public function afterGetProductCollection(\Magento\Catalog\Model\Layer $object, $listProduct)
    {
        if($this->_request->getRouteName() == 'multivendor'){
            $id = $this->_request->getParam('id');
            $vendorProductModel = $this->_objectManager->create('Magestore\Multivendor\Model\VendorProduct')->load($id,'vendor_id');
            $productIds = $vendorProductModel->getProductIds();
            $productIdArray = explode(',', $productIds);
            
            $listProduct = $this->_productFactory->create()->getCollection();
            $listProduct->getSelect()->where('e.entity_id in (?)',$productIdArray);
        }
        return $listProduct;
    }
}