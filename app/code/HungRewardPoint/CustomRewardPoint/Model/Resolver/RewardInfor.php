<?php
declare(strict_types=1);

namespace HungRewardPoint\CustomRewardPoint\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class RewardInfor implements ResolverInterface
{
	protected $_CustomersCollection;
    protected $helperData;
    public function __construct(
        \Mageplaza\RewardPoints\Model\AccountFactory $CustomersCollection,
        \Mageplaza\RewardPoints\Helper\Data $helperData    
        ) {
        $this->_CustomersCollection = $CustomersCollection;
        $this->helperData            = $helperData;
    }

    public function resolve(
       Field $field,
       $context,
       ResolveInfo $info,
       array $value = null,
       array $args = null
    ) {
        $cusdata = $this->getCusData();
        return $cusdata;
    }
    /**
     * @param int $catId
     * @return array
     * @throws GraphQlNoSuchEntityException
     */
    public function getCusData(){
        if (!$this->helperData->isEnabled()) {
            return [];
        }

        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        $customer = $value['model'];
        $cuscollection = $this->_CustomersCollection->create()->loadByCustomerId($customer->getId());
		$vencustomer = [];
        
        $vencustomer = [
                        'reward_id' =>$cuscollection->getRewardId(),
                        'customer_id' =>$cuscollection->getCustomerId(),
                        'point_balance'=> $cuscollection->getPointBalance(),
                        'point_spent'=> $cuscollection->getPointSpent(),
                        'point_earned'=> $cuscollection->getPointEarned(),
                        'notification_expire'=> $cuscollection->getNotificationExpire(),
                        'notification_update'=> $cuscollection->getNotificationUpdate()];
        return $vencustomer;
        // var_dump($vencustomer);
    }
}
