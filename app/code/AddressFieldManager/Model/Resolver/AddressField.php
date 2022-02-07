<?php
declare(strict_types=1);

namespace Swissup\AddressFieldManager\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class AddressField implements ResolverInterface
{
    protected $addressfiled;

    public function __construct(
       \Magento\Eav\Model\Config $addressfiled
    ) {
        $this->addressfiled = $addressfiled;
    }

    public function resolve(
       Field $field,
       $context,
       ResolveInfo $info,
       array $value = null,
       array $args = null
    ) {
        $collection = $this ->addressfiled -> getEntityAttributes('2');
        // $eavfield = $collection -> getSource() -> getAllOptions();
        // $collection -> getAttributeCode();
        $attribute = [];
        foreach($collection as $eav){
            $attribute[] = [
                            'is_required'=>$eav->getData('is_required'),
                            'attribute_code'=>$eav->getData('attribute_code'),
                            'frontend_label'=>$eav->getData('frontend_label')];
        }
        return $attribute;
    }
}