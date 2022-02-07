<?php
declare(strict_types=1);

namespace HungRewardPoint\CustomRewardPoint\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class RewardOrder implements ResolverInterface
{
    protected $rewardPointOrderModel;

    protected $collection;

    public function __construct(
        \Magento\Sales\Model\Order $collection,
        \Mageplaza\RewardPoints\Model\Order $rewardPointOrderModel
    ) {
        $this->rewardPointOrderModel = $rewardPointOrderModel;
        $this->collection = $collection;
    }

    public function resolve(
           Field $field,
           $context,
           ResolveInfo $info,
           array $value = null,
           array $args = null
        ) {
           $orderdata = $this->getRewardOrderdata();
           return $orderdata;
        }
        /**
         * @param int $catId
         * @return array
         * @throws GraphQlNoSuchEntityException
         */
        public function getRewardOrderdata(){
            $pointcollection = $this->collection->getCollection();
            $pointcollection->setOrder('entity_id','DESC')
                            ->setPageSize('5')
                            ->setCurPage('1');
            $vendorpoint = [];
            foreach($pointcollection as $po){
                $vendorpoint[] = [
                        'status' => $po->getStatus(),
                        'mp_reward_earn'=> $po->getMpRewardEarn(),
                        'mp_reward_spent'=> $po->getMpRewardSpent()
                    ];
                }
            return $vendorpoint;
            
//            var_dump($vendorpoint);
        }

        
    }
