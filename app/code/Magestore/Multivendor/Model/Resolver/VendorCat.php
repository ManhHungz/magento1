<?php
declare(strict_types=1);

namespace Magestore\Multivendor\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class VendorCat implements ResolverInterface
{
    protected $productInterface;
	protected $_productCollectionFactory;
    protected $catInterface;
    protected $_categoryFactory;
    protected $categoryRepository;
    protected $_catCollectionFactory;
    
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productInterface,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,     
        \Magento\Catalog\Api\CategoryRepositoryInterface $catInterface,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $catCollectionFactory, 
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        array $data = []
    ) {
        $this->productInterface = $productInterface;
		$this->_productCollectionFactory = $productCollectionFactory;    
        $this->catInterface = $catInterface;
        $this->_categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->_catCollectionFactory = $catCollectionFactory;
    }
    public function resolve(
       Field $field,
       $context,
       ResolveInfo $info,
       array $value = null,
       array $args = null
    ) {
       $catId = $this->getCatId($args);
       $catData = $this->getcatData($catId);
       return $catData;
    }
    /**
     * @param array $args
     * @return int
     * @throws GraphQlInputException
     */
    private function getCatId(array $args):int {
       if (!isset($args['category_id'])) {
           throw new GraphQlInputException(__('cat id should be specified'));
       }
       return (int) $args['category_id'];
    }
    /**
     * @param int $catId
     * @return array
     * @throws GraphQlNoSuchEntityException
     */
    public function getcatData(int $catId):array{
        $catcollection = $this->_categoryFactory->create()->load($catId);
        $category['items'] = [];
        $cat_id = $catcollection->getCategoryIds();
        $category['items'][$cat_id]['name']=$catcollection->getName();
        
        $subcat = $this->getSubCategory($catId);
        $Subcategory = [];
            foreach($subcat as $sub){
                $Subcategory[] = ['name' => $sub->getName()];
            }
            $category['items'][$cat_id]['catadd'] = $Subcategory;
        
        $child = $this->getProductCollection($catId);
        $products =  [];
            foreach($child as $childproduct){
                $products[] = ['name' => $childproduct->getName(), 'sku' => $childproduct->getSku()];
            }
            $category['items'][$cat_id]['productadd'] = $products;
        return $category ;
        //var_dump($category);
        
    }
    public function getProductCollection($catId)
    {
        $category = $this->_categoryFactory->create()->load($catId);
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoryFilter($category);
        //$collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
        $collection->addAttributeToFilter('status',\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
        $collection ->setPageSize(5)
                    ->setCurPage(1)
                    ->addAttributeToSort('entity_id', 'desc');
        return $collection;
    }
    public function getSubCategory($catId){
    $category = $this ->_categoryFactory -> create()-> load($catId);
        //Get category collection
        $collection = $category -> getCollection()
                                -> addIsActiveFilter()
                                -> addOrderField('name')
                                -> addIdFilter($category -> getChildren())
                                ->addAttributeToSort('entity_id', 'asc');
        //var_dump($collection->getSize());
        return $collection;
    }
}