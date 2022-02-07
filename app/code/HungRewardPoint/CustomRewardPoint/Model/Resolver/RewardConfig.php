<?php
declare(strict_types=1);

namespace HungRewardPoint\CustomRewardPoint\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class RewardConfig implements ResolverInterface
{
    protected $ConfigRepository;

    public function __construct(
       \Mageplaza\RewardPoints\Model\ConfigRepository $ConfigRepository
    ) {
        $this->_ConfigRepository = $ConfigRepository;
    }

    public function resolve(
       Field $field,
       $context,
       ResolveInfo $info,
       array $value = null,
       array $args = null
    ) {
        $store   = $context->getExtensionAttributes()->getStore();
        $storeId = $store->getId();
        $storeConfigs = $this->_ConfigRepository->getStoreConfigs($storeId);
        // $storeConfigs['earning']['earn_from_tax'] = $storeConfigs['earning']['earn_from'];

        return $storeConfigs;
        // var_dump($storeConfigs);
    }

    // /**
    //  * @param array $args
    //  * @return int
    //  * @throws GraphQlInputException
    //  */
    // private function getRewardOrderId(array $args):int {
    //    if (!isset($args['order_id'])) {
    //        throw new GraphQlInputException(__('order id should be specified'));
    //    }
    //    return (int) $args['order_id'];
    // }
    /**
     * @param int $catId
     * @return array
     * @throws GraphQlNoSuchEntityException
     */
    // public function getRewardConfigdata(){
        
    //     //return $vendorconfig;
    // }

    
}
