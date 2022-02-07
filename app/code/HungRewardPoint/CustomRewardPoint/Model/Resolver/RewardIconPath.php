<?php
declare(strict_types=1);

namespace HungRewardPoint\CustomRewardPoint\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class RewardIconPath implements ResolverInterface
{
    protected $_IconInterface;

    public function __construct(
       \Mageplaza\RewardPoints\Helper\Point $IconInterface
    ) {
        $this->_IconInterface = $IconInterface;
    }

    public function resolve(
       Field $field,
       $context,
       ResolveInfo $info,
       array $value = null,
       array $args = null
    ) {
       $icondata = $this->getRewardIcondata();
       return $icondata;
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
    public function getRewardIcondata(){
        $typeicon = $this->_IconInterface;
        $vendorpathicon = [];
        $vendorpathicon = ['path' => $typeicon->getIconUrl()];
        //var_dump($vendorpathicon);
        return $vendorpathicon;
    }

    
}
