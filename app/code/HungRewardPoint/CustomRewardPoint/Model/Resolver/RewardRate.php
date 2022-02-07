<?php
declare(strict_types=1);

namespace HungRewardPoint\CustomRewardPoint\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class RewardRate implements ResolverInterface
{
    protected $_RateCollection;

    public function __construct(
        \Mageplaza\RewardPoints\Model\ResourceModel\Rate\Collection $RateCollection
    ) {
        $this->_RateCollection = $RateCollection;
    }

    public function resolve(
       Field $field,
       $context,
       ResolveInfo $info,
       array $value = null,
       array $args = null
    ) {
       $ratedata = $this->getRewardRateData();
       return $ratedata;
    }
    /**
     * @param int $catId
     * @return array
     * @throws GraphQlNoSuchEntityException
     */
    public function getRewardRateData(){
        $convertcollection = $this->_RateCollection;
        $convertcollection->addAttributeToSelect('*');
        // var_dump($convertcollection->getSize());
        // die;
		$vendorrate = [];
        
		foreach ($convertcollection as $rate ) {
            $vendorrate[]= ['rate_id'=> $rate->getRateId(),
                            'customer_group_ids'=> $rate->getCustomerGroupIds(),
                            'website_ids'=> $rate->getWebsiteIds(),
                            'direction'=> $rate->getDirection(),
                            'points'=> $rate->getPoints(),
                            'money'=> $rate->getMoney(),
                            'priority'=> $rate->getPriority()];
		}
        return $vendorrate;
        // var_dump($vendorrate);
    }

    
}
